<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Token;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Generate bill for a completed token
    public function store(Request $request, $token_id)
    {
        // Find the token (Security scope ensures it belongs to the current clinic)
        $token = Token::findOrFail($token_id);

        // Check if token is actually completed
        if ($token->status !== 'completed') {
            return redirect()->back()->with('error', 'Bill can only be generated for completed checkups.');
        }

        // Prevent duplicate invoices for the same token
        if ($token->invoice) {
            return redirect()->back()->with('error', 'Bill for this token is already generated!');
        }

        // 1. Get Doctor Fee
        $doctorFee = $token->doctor->consultation_fee ?? 0;

        // 2. Get Service Fee (if any service was selected)
        $serviceFee = 0;
        if ($token->service) {
            $serviceFee = $token->service->fee ?? 0;
        }

        // 3. Calculate Total
        $totalAmount = $doctorFee + $serviceFee;

        // 4. Create the Invoice
        // Note: tenant_id is NOT added here because our BelongsToTenant trait adds it automatically!
        Invoice::create([
            'patient_id' => $token->patient_id,
            'token_id'   => $token->id,
            'doctor_fee' => $doctorFee,
            'service_fee'=> $serviceFee,
            'total_amount'=> $totalAmount,
            'status'     => 'unpaid',
        ]);

        return redirect()->back()->with('success', "Bill of Rs. {$totalAmount} generated successfully for Token {$token->token_number}!");
    }

        // Display the invoice receipt
    public function show(Invoice $invoice)
    {
        // Load relationships so we can display doctor and service names
        $invoice->load(['patient', 'token.doctor', 'token.service']);
        
        // Get current clinic details to show on top of the receipt
        $clinic = app('currentTenant');

        return view('invoices.show', compact('invoice', 'clinic'));
    }

        // Mark the invoice as paid
    public function markAsPaid(Invoice $invoice)
    {
        // Update status to paid
        $invoice->update([
            'status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Payment of Rs. ' . number_format($invoice->total_amount, 2) . ' received successfully!');
    }
}