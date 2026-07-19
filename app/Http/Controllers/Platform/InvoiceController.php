<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformInvoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = PlatformInvoice::with('tenant')->latest()->get();
        return view('platform.invoices.index', compact('invoices'));
    }

    public function show(PlatformInvoice $invoice)
    {
        $invoice->load('tenant', 'subscription.plan');
        return view('platform.invoices.show', compact('invoice'));
    }
}