<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Medicine;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $todayPatients = Patient::whereDate('created_at', today())->count();
        $waitingTokens = Token::where('status', 'waiting')->count();
        $inProgressTokens = Token::where('status', 'in-progress')->count();
        
        $todayRevenue = Invoice::whereDate('created_at', today())->sum('total_amount');
        
        $lowStockCount = Medicine::where('stock_quantity', '<=', 10)->count();
        $expiringSoonCount = Medicine::whereBetween('expiry_date', [now(), now()->addDays(30)])->count();

        $recentTokens = Token::with(['patient', 'doctor'])
            ->latest()
            ->take(8)
            ->get();

        $todayCompleted = Token::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        return view('tenantView.dashboard', compact(
            'todayPatients',
            'waitingTokens',
            'inProgressTokens',
            'todayRevenue',
            'lowStockCount',
            'expiringSoonCount',
            'recentTokens',
            'todayCompleted'
        ));
    }
}