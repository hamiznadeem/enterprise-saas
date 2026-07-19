<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Token;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // Show the prescription form for the current patient
    public function create($token_id)
    {
        $token = Token::findOrFail($token_id);
        
        // Only allow prescription for patients who are currently with the doctor (in-progress)
        if ($token->status !== 'in-progress') {
            return redirect()->back()->with('error', 'Prescription can only be written for the current patient.');
        }

        // If prescription already exists, show it instead of creating a new one
        if ($token->prescription) {
            return redirect()->route('prescriptions.show', $token->prescription->id);
        }

        return view('prescriptions.create', compact('token'));
    }

       // SMART SEARCH: Called via AJAX when doctor types in the medicine box
    public function searchMedicine(Request $request)
    {
        $search = $request->get('q');

        // Kam az kam 2 characters type karne do
        if (empty($search) || strlen($search) < 2) {
            return response()->json([]);
        }

        // Search karo aur sirf 10 results lo (Dropdown ko clean rakhne ke liye)
        $medicines = Medicine::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "{$search}%") // Shuru se match
                     ->orWhere('generic_name', 'like', "{$search}%"); // Shuru se match
            })
            ->limit(10)
            ->get();

        // Data ko JS ke format mein map karo
        $data = $medicines->map(function ($med) {
            $alternatives = [];
            
            // Agar stock 0 hai toh alternatives dhundho
            if ($med->stock_quantity == 0 && !empty($med->generic_name)) {
                $alts = Medicine::where('generic_name', $med->generic_name)
                    ->where('id', '!=', $med->id)
                    ->where('stock_quantity', '>', 0)
                    ->limit(3)
                    ->get();
                    
                foreach ($alts as $alt) {
                    $alternatives[] = [
                        'id' => $alt->id,
                        'name' => $alt->name,
                        'stock' => $alt->stock_quantity
                    ];
                }
            }

            return [
                'id' => $med->id,
                'name' => $med->name,
                'generic_name' => $med->generic_name,
                'stock' => $med->stock_quantity, // JS ko 'stock' chahiye
                'alternatives' => $alternatives
            ];
        });

        return response()->json($data);
    }

    // Save the prescription and selected medicines
    public function store(Request $request, $token_id)
    {
        $token = Token::findOrFail($token_id);

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1', // At least one medicine required
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        // Create the main prescription record
        $prescription = Prescription::create([
            'patient_id' => $token->patient_id,
            'doctor_id' => \App\Models\Doctor::find(auth()->user()->doctor_id)->id ?? null,
            'token_id'   => $token->id,
            'diagnosis'  => $validated['diagnosis'],
            'notes'      => $validated['notes'],
        ]);

        // Save each medicine as a prescription item
        foreach ($validated['medicines'] as $medData) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id'     => $medData['id'],
                'dosage'          => $medData['dosage'],
                'days'            => $medData['days'],
                'instructions'    => $medData['instructions'] ?? null,
            ]);
        }

        return redirect()->route('prescriptions.show', $prescription->id)->with('success', 'Prescription saved successfully!');
    }



        // Display the saved prescription slip
    public function show(Prescription $prescription)
    {
        // Load relationships so we can display medicine names
        $prescription->load(['items.medicine', 'patient', 'doctor']);
        
        // Get current clinic details to show on top of the slip
        $clinic = app('currentTenant');

        return view('prescriptions.show', compact('prescription', 'clinic'));
    }

    
}