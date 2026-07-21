<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Medicine;
use App\Services\TenantActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display the POS Screen
     */
    public function index()
    {
        $categories = Medicine::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('pos.index', compact('categories'));
    }

    /**
     * Search medicines for the cart
     */
    public function searchItems(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $medicines = Medicine::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "{$q}%")
                    ->orWhere('generic_name', 'like', "{$q}%")
                    ->orWhere('brand_name', 'like', "{$q}%")
                    ->orWhere('barcode', $q); // Exact match for barcode
            })
            ->where('stock_quantity', '>', 0) // ✅ FIX: Out of stock na dikhaye
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get();

        return response()->json($medicines);
    }

    /**
     * Process the Cart & Complete the Sale
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart'            => 'required|array|min:1',
            'cart.*.id'       => 'required|exists:medicines,id',
            'cart.*.quantity' => 'required|integer|min:1|max:1000',
            'payment_method'  => 'required|in:cash,card,bank_transfer,jazzcash,easypaisa,other',
            'discount_type'   => 'nullable|in:amount,percent',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_percentage'  => 'nullable|numeric|min:0|max:100',
            'patient_id'      => 'nullable|exists:patients,id',
        ]);

        // ✅ FIX: Clean exception handling with proper JSON response
        try {
            $sale = DB::transaction(function () use ($validated, $request) {

                $subtotal = 0;
                $cartItemsData = [];

                // ── Step 1: Validate Stock & Prepare Cart Data ──
                foreach ($validated['cart'] as $item) {
                    // ✅ FIX: Lock row + check stock in one query (prevents race condition)
                    $medicine = Medicine::where('id', $item['id'])
                        ->where('stock_quantity', '>=', $item['quantity'])
                        ->lockForUpdate()
                        ->first();

                    if (!$medicine) {
                        $actual = Medicine::find($item['id']);
                        $available = $actual ? $actual->stock_quantity : 0;
                        throw new \InvalidArgumentException(
                            "Insufficient stock for {$actual->name}. Requested: {$item['quantity']}, Available: {$available}"
                        );
                    }

                    $itemTotal = $medicine->sale_price * $item['quantity'];
                    $subtotal += $itemTotal;

                    $cartItemsData[] = [
                        'itemable_type' => Medicine::class,
                        'itemable_id'   => $medicine->id,
                        'item_name'     => $medicine->name,
                        'unit_price'    => $medicine->sale_price,
                        'unit_name'     => $medicine->unit_name ?: 'Unit', // ✅ FIX: Fallback
                        'quantity'      => $item['quantity'],
                        'total_price'   => $itemTotal,
                    ];

                    // ✅ FIX: Decrement after lock (safe from race conditions)
                    $medicine->decrement('stock_quantity', $item['quantity']);
                }

                // ── Step 2: Calculate Tax & Discount ──
                $taxPercentage = $validated['tax_percentage'] ?? 0;
                $discountValue = $validated['discount_amount'] ?? 0;
                $discountType  = $validated['discount_type'] ?? 'amount';

                $taxAmount = round(($subtotal * $taxPercentage) / 100, 2);

                // Smart discount: amount ya percent
                $discountAmount = ($discountType === 'percent')
                    ? round(($subtotal * $discountValue) / 100, 2)
                    : round($discountValue, 2);

                // Prevent over-discount
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }

                $totalAmount = round(($subtotal + $taxAmount) - $discountAmount, 2);

                // ── Step 3: Generate Unique Sale Number ──
                // ✅ FIX: Date-based + daily sequence (no collisions)
                $today = now()->format('Ymd');
                $lastSale = Sale::where('sale_number', 'like', "POS-{$today}%")
                    ->orderBy('sale_number', 'desc')
                    ->value('sale_number');

                $nextNum = $lastSale
                    ? (int) substr($lastSale, -4) + 1
                    : 1;

                $saleNumber = "POS-{$today}-" . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                // ── Step 4: Create Sale Record ──
                $sale = Sale::create([
                    'patient_id'      => $validated['patient_id'] ?? null,
                    'user_id'         => auth()->id(),
                    'sale_number'     => $saleNumber,
                    'subtotal'        => $subtotal,
                    'tax_percentage'  => $taxPercentage,
                    'tax_amount'      => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount'    => $totalAmount,
                    'payment_method'  => $validated['payment_method'],
                    'status'          => 'completed',
                ]);

                // ── Step 5: Attach Items ──
                foreach ($cartItemsData as $itemData) {
                    $sale->items()->create($itemData);
                }

                return $sale;
            });

            // ✅ FIX: Activity log (outside transaction — only on success)
            TenantActivityService::logSaleCompleted($sale);

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale completed successfully!',
            ]);

        } catch (\InvalidArgumentException $e) {
            // Stock errors — user's fault
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // DB errors — likely duplicate sale number (very rare with new format)
            return response()->json([
                'success' => false,
                'message' => 'Database error. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            // Anything else
            return response()->json([
                'success' => false,
                'message' => app()->isLocal() ? $e->getMessage() : 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the final receipt
     */
    public function showReceipt(Sale $sale)
    {
        $sale->load('items', 'user', 'patient');
        $clinic = app('currentTenant');

        return view('pos.receipt', compact('sale', 'clinic'));
    }
}