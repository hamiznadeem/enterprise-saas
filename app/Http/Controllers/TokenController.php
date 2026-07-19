<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    // Show the token generation form to the receptionist
    public function create()
    {
        // Fetch only the records belonging to the current logged-in clinic
        $patients = Patient::all();
        $doctors = Doctor::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        return view('tokens.create', compact('patients', 'doctors', 'services'));
    }


       // Show the live queue of tokens for the receptionist
    public function index()
    {
        // Get all tokens for the current clinic, ordered by newest first
        $tokens = Token::with(['patient', 'doctor', 'service'])
                        ->orderBy('id', 'desc')
                        ->get();

        return view('tokens.index', compact('tokens'));
    }

      // Doctor's personal dashboard to see their queue
    public function doctorDashboard()
    {
        // BYPASS: Directly find the doctor from the doctors table
        $doctorProfile = \App\Models\Doctor::find(auth()->user()->doctor_id);

        if (!$doctorProfile) {
            abort(403, 'You are not assigned as a doctor in this system.');
        }

        // Get tokens specifically for THIS doctor, ordered by oldest first (FIFO Queue)
        $tokens = Token::where('doctor_id', $doctorProfile->id)
                        ->with(['patient', 'service'])
                        ->whereIn('status', ['waiting', 'in-progress'])
                        ->orderBy('id', 'asc') // Oldest token gets priority
                        ->get();

        // FIX: Variable ka naam $currentPatient se $currentToken kar diya
        $currentToken = $tokens->firstWhere('status', 'in-progress');

               // Separate the waiting tokens
        $waitingTokens = $tokens->where('status', 'waiting');

        return view('tokens.doctor-dashboard', compact('currentToken', 'waitingTokens', 'doctorProfile'));
    }


       // Action: Doctor clicks "Call Next Patient"
    public function callNextToken()
    {
        // BYPASS: Directly find the doctor
        $doctorProfile = \App\Models\Doctor::find(auth()->user()->doctor_id);

        if (!$doctorProfile) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }

        // Find the OLDEST waiting token for this doctor
        $nextToken = Token::where('doctor_id', $doctorProfile->id)
                            ->where('status', 'waiting')
                            ->orderBy('id', 'asc')
                            ->first();

        if ($nextToken) {
            // Update token status to in-progress and save the current time
            $nextToken->update([
                'status' => 'in-progress',
                'called_at' => now(),
            ]);

            return redirect()->back()->with('success', "Patient {$nextToken->patient->name} ({$nextToken->token_number}) called successfully!");
        }

        // If no one is waiting
        return redirect()->back()->with('info', 'No patients in the waiting queue right now.');
    }


        // Action: Doctor clicks "Complete Patient" after checkup
    public function completeToken($id)
    {
        // Find the token (Security scope will automatically ensure it belongs to this clinic)
        $token = Token::findOrFail($id);

        // Update status to completed and save the finish time
        $token->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Patient {$token->patient->name} checkup completed!");
    }


      // Handle the form submission and generate the token
       // Handle the form submission and generate the token
    public function store(Request $request)
    {
        // Validate the new form data
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_age' => 'required|numeric|min:0|max:150',
            'patient_gender' => 'required|in:male,female,other',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
        ]);

        // 1. Check if patient with this phone number already exists
        $patient = \App\Models\Patient::firstWhere('phone', $validated['patient_phone']);

        // 2. If not, create a new patient
        if (!$patient) {
            $patient = \App\Models\Patient::create([
                'name' => $validated['patient_name'],
                'phone' => $validated['patient_phone'],
                'age' => $validated['patient_age'],
                'gender' => $validated['patient_gender'],
            ]);
        }

        // Get the selected doctor
        $doctor = Doctor::findOrFail($validated['doctor_id']);

        // CHECK DAILY LIMIT: Count how many tokens this doctor has TODAY
        $todayTokensCount = Token::where('doctor_id', $doctor->id)
            ->whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->count();

        // If limit is reached, stop and go back with an error message
        if ($todayTokensCount >= $doctor->daily_patient_limit) {
            return redirect()->back()->with('error', "Sorry! Dr. {$doctor->name} has reached their daily limit of {$doctor->daily_patient_limit} patients.");
        }

        // Generate a unique Token Number
        $lastTokenCount = Token::where('tenant_id', app('currentTenant')->id)->count();
        $tokenNumber = 'T-' . str_pad($lastTokenCount + 1, 3, '0', STR_PAD_LEFT);

        // Create the token with the newly found/created patient ID
        Token::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $validated['service_id'] ?? null,
            'token_number' => $tokenNumber,
            'status' => 'waiting',
            'is_walk_in' => true,
        ]);

        // Redirect back with a success message
        return redirect()->route('tokens.create')->with('success', "Token {$tokenNumber} generated successfully for Dr. {$doctor->name}!");
    }



}