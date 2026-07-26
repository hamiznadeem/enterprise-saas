<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display the System Setup & Settings page.
     */
    public function index()
    {
        return view('tenantView.settings.setup');
    }

    /**
     * Handle updating settings (Static demonstration mode).
     */
    public function update(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            $tabName = $request->input('tab_name', 'Settings');
            return response()->json([
                'success' => true,
                'message' => "{$tabName} saved successfully!"
            ]);
        }

        return back()->with('success', 'System settings updated successfully!');
    }
}
