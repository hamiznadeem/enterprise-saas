<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Token;


class PatientController extends Controller
{
    // 1. Yeh function screen dikhayega (Table + Search bar + Modal Form)
    public function index()
    {
        // NEW: Database se patients fetch karke view ko bhej rahe hain
        $patients = Patient::latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    // 2. Yeh function naya patient save karega
    public function store(Request $request)
    {
        // Form ka data validate karein (sab fields zaroori hain)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'cnic' => 'nullable|string|max:15',
            'age' => 'required|string|max:3',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'blood_group' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        // Data save karein (Yahan BelongsToTenant trait automatically tenant_id laga dega)
        Patient::create($validated);
        
        \App\Services\TenantActivityService::logPatientCreated(Patient::latest()->first());

        // Wapas usi page par bhej dein with success message
        return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');
    }

    // 3. Yeh function search karega purane patient ko
    public function search(Request $request)
    {
        // NEW: Nayi UI 'q' parameter bhejti hai AJAX mein
        $search = $request->get('q', '');
        
        if (empty($search)) {
            return response()->json([]);
        }
        
        // Current tenant ke andar hi search karega (Security guard automatically lagega)
        $patients = Patient::where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('cnic', 'like', "%{$search}%")
                            ->latest()
                            ->take(20)
                            ->get();

        // Results ko JSON format mein bhejega (AJAX ke liye)
        return response()->json($patients);
    }

    // View complete patient history (Visits, Prescriptions, Invoices)
    public function showHistory(Patient $patient)
    {
        // NEW: Patient model par directly tokens load kar rahe hain (Nayi UI isko expect karti hai)
        $patient->load(['tokens.doctor', 'tokens.service', 'tokens.prescription.items.medicine', 'tokens.invoice']);
        
        return view('patients.history', compact('patient'));
    }

}