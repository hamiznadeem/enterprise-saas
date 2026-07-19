<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    // Pharmacy Inventory Dashboard (Alerts)
    public function index()
    {
        // THRESHOLDS: You can change these limits later
        $lowStockThreshold = 5; // Alert if 5 or less units left
        $expiryDaysThreshold = 30; // Alert if expiring within 30 days

        // 1. Get Low Stock Medicines
        $lowStockMedicines = Medicine::where('is_active', true)
            ->where('stock_quantity', '<=', $lowStockThreshold)
            ->orderBy('stock_quantity', 'asc') // Lowest stock first
            ->get();

        // 2. Get Expiring Soon Medicines (Including already expired)
        $expiringMedicines = Medicine::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays($expiryDaysThreshold))
            ->orderBy('expiry_date', 'asc') // Expiring soonest first
            ->get();

        return view('pharmacy.dashboard', compact('lowStockMedicines', 'expiringMedicines'));
    }
}