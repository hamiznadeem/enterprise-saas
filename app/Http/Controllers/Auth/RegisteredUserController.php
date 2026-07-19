<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Tenant;
use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
        public function store(Request $request): RedirectResponse | JsonResponse
    {
        // 1. Form ka data validate karein (sab fields zaroori hain)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'business_type' => ['required', 'string', 'max:255'],
            'outlets' => ['required', 'integer', 'min:1'],
        ]);

        $tenant = Tenant::create([
            'name' => $request->company_name,
            'domain' => \Illuminate\Support\Str::slug($request->company_name),
            'database' => env('DB_DATABASE'),
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
            'business_type' => $request->business_type,
            'outlets' => $request->outlets,
        ]);

        // Phir us Tenant ka Domain banayein (Future ke liye jab subdomain par chalayenge)
        Domain::create([
            'tenant_id' => $tenant->id,
            'domain' => Str::slug($request->company_name) . '.localhost.com',
        ]);

        // Ab User (Owner) banayein aur usko Tenant se attach karein
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id, // Yahan user ko clinic se jod rahe hain
            'role' => 'owner', // Is user ka role owner hai
        ]);

        // ==========================================

        // 3. User ko automatically login kara dein
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            // Agar email send nahi hua toh koi baat nahi, clinic toh ban gayi
        }
        Auth::login($user);

         // 3. User ko automatically login kara dein
        event(new Registered($user));
        Auth::login($user);

        // ==========================================
        // 4. SMART RETURN (Landing Page ke liye)
        // ==========================================
        
        // Agar request Landing Page (AJAX) se aayi hai
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'redirect_url' => route('dashboard')
            ]);
        }

        // Agar koi direct register page par aaya tha
        return redirect(route('dashboard'));
    }
}
