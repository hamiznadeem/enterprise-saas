<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    // Display the POS Screen
    public function index()
    {
        // Get unique categories for the filter buttons (e.g., Painkiller, Antibiotic)
        $categories = Medicine::where('is_active', true)
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();

        return view('pos.index', compact('categories'));
    }

    public function searchItems(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Enterprise Search Logic: 
        $medicines = Medicine::where(function ($query) use ($q) {
                $query->where('name', 'like', "{$q}%")
                    ->orWhere('generic_name', 'like', "{$q}%")
                    ->orWhere('brand_name', 'like', "{$q}%")
                    ->orWhere('barcode', 'like', "{$q}%");
            })
            ->orderBy('name', 'asc') // Alphabetical order mein arrange karega
            ->limit(20)
            ->get();

        return response()->json($medicines);
    }


    // Process the Cart & Complete the Sale
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:medicines,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        // START DATABASE TRANSACTION (If anything fails, everything rolls back safely)
        return DB::transaction(function () use ($validated, $request) {
            
            $subtotal = 0;
            $cartItemsData = [];

            // 1. Validate Stock & Prepare Cart Data
            foreach ($validated['cart'] as $item) {
                $medicine = Medicine::find($item['id']);

                if ($medicine->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$medicine->name}! Available: {$medicine->stock_quantity}");
                }

                $itemTotal = $medicine->sale_price * $item['quantity'];
                $subtotal += $itemTotal;

                // Prepare data for sale_items table using Polymorphic relationship
                $cartItemsData[] = [
                    'itemable_type' => Medicine::class, // Scalable: Tomorrow this could be Product::class
                    'itemable_id'   => $medicine->id,
                    'item_name'     => $medicine->name, // SNAPSHOT: Freeze the name
                    'unit_price'    => $medicine->sale_price, // SNAPSHOT: Freeze the price
                    'unit_name'     => $medicine->unit_name,
                    'quantity'      => $item['quantity'],
                    'total_price'   => $itemTotal,
                ];

                // DEDUCT STOCK: Subtract sold quantity from inventory
                $medicine->decrement('stock_quantity', $item['quantity']);
            }

            // 2. Calculate Tax & Final Total
            $taxPercentage = $validated['tax_percentage'] ?? 0;
            $discountValue = $validated['discount_amount'] ?? 0;
            
            $taxAmount = ($subtotal * $taxPercentage) / 100;
            
            // SMART DISCOUNT: Check if discount_type was sent (we will send it from JS)
            $discountType = $request->input('discount_type', 'amount');
            $discountAmount = ($discountType === 'percent') ? ($subtotal * $discountValue) / 100 : $discountValue;
            
            // Prevent negative total
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }

            $totalAmount = ($subtotal + $taxAmount) - $discountAmount;

            // 3. Generate Unique Sale Number
            $lastSaleCount = Sale::where('tenant_id', app('currentTenant')->id)->count();
            $saleNumber = 'POS-' . str_pad($lastSaleCount + 1, 5, '0', STR_PAD_LEFT);

            // 4. Create the Main Sale Record
            $sale = Sale::create([
                'patient_id'     => $validated['patient_id'] ?? null,
                'user_id'        => auth()->id(),
                'sale_number'    => $saleNumber,
                'subtotal'       => $subtotal,
                'tax_percentage' => $taxPercentage,
                'tax_amount'     => $taxAmount,
                'discount_amount'=> $discountAmount,
                'total_amount'   => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status'         => 'completed',
            ]);

            // 5. Attach Items to the Sale
            foreach ($cartItemsData as $itemData) {
                $sale->items()->create($itemData);
            }

            // Return success with Sale ID to redirect to receipt
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale completed successfully!'
            ]);
        });
    }

        // Display the final receipt after successful payment
    public function showReceipt(Sale $sale)
    {
        // Load items to show on receipt
        $sale->load('items');
        $clinic = app('currentTenant');
        
        return view('pos.receipt', compact('sale', 'clinic'));
    }
}