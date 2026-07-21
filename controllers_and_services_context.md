# Controllers, Services, and Core Logic Context

This document contains the source code for various controllers, services, repositories, and core logic files that were previously missing from the context.

## `app/Scopes/TenantScope.php`

```php
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (app()->has('currentTenant') && app('currentTenant') !== null) {
            $builder->where($model->getTable() . '.tenant_id', app('currentTenant')->id);
        }
    }
}
```

## `app/Traits/BelongsToTenant.php`

```php
<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (app()->has('currentTenant') && app('currentTenant') !== null) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
```

## `app/Http/Controllers/Platform/AuthController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PlatformSessionService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('platform.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('platform')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('platform.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('platform')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('platform.login');
    }

    public function logoutAllDevices(Request $request)
    {
        $password = $request->input('password');
        $user = Auth::guard('platform')->user();

        if (!Auth::guard('platform')->validate(['email' => $user->email, 'password' => $password])) {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }

        PlatformSessionService::logoutOtherDevices($user, $password);

        return back()->with('status', 'Successfully logged out of all other devices.');
    }
}
```

## `app/Http/Controllers/Platform/PasswordController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\PlatformPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('platform.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        PlatformPasswordService::sendResetLink($request->only('email'));
        return back()->with('status', 'Password reset link sent!');
    }

    public function showResetForm($token)
    {
        return view('platform.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        PlatformPasswordService::resetPassword($request->all());

        return redirect()->route('platform.login')->with('status', 'Your password has been reset!');
    }

    public function showChangeForm()
    {
        return view('platform.settings.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = auth('platform')->user();

        if (!PlatformPasswordService::changePassword($user, $request->current_password, $request->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password or is a previously used password.']);
        }

        return back()->with('status', 'Password changed successfully!');
    }
}
```

## `app/Http/Controllers/Platform/VerificationController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function __construct()
    {
        $this->middleware('platform.auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return $request->user('platform')->hasVerifiedEmail()
                        ? redirect()->route('platform.dashboard')
                        : view('platform.auth.verify-email');
    }

    public function resend(Request $request)
    {
        if ($request->user('platform')->hasVerifiedEmail()) {
            return redirect()->route('platform.dashboard');
        }

        $request->user('platform')->sendEmailVerificationNotification();

        return back()->with('resent', 'A fresh verification link has been sent to your email address.');
    }
}
```

## `app/Http/Controllers/Platform/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformInvoice;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $trialTenants = Tenant::where('status', 'trial')->count();
        $totalRevenue = PlatformInvoice::where('status', 'paid')->sum('total');

        return view('platform.dashboard', compact('totalTenants', 'activeTenants', 'trialTenants', 'totalRevenue'));
    }
}
```

## `app/Http/Controllers/Platform/PlanController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('platform.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_days' => 'nullable|integer',
        ]);

        Plan::create($data);
        return back()->with('status', 'Plan created successfully.');
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_days' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $plan->update($data);
        return back()->with('status', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('status', 'Plan deleted successfully.');
    }
}
```

## `app/Http/Controllers/Platform/TenantController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('plan')->latest()->paginate(15);
        return view('platform.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('subscriptions', 'invoices');
        return view('platform.tenants.show', compact('tenant'));
    }

    public function store(Request $request)
    {
        // Logic to create a new tenant from platform
        return back()->with('status', 'Tenant created successfully.');
    }

    public function update(Request $request, Tenant $tenant)
    {
        // Logic to update tenant details
        return back()->with('status', 'Tenant updated successfully.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->update(['is_active' => !$tenant->is_active]);
        $status = $tenant->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Tenant has been {$status}.");
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('platform.tenants.index')->with('status', 'Tenant deleted successfully.');
    }

    public function renew(Request $request, Tenant $tenant)
    {
        // Logic to renew subscription
        return back()->with('status', 'Tenant subscription renewed.');
    }

    public function toggleModule(Request $request, Tenant $tenant)
    {
        // Logic to enable/disable a module for a tenant
        return back()->with('status', 'Module status updated.');
    }

    public function addSubscriptionLog(Request $request, Tenant $tenant)
    {
        // Logic to add a manual subscription log
        return back()->with('status', 'Subscription log added.');
    }
}
```

## `app/Http/Controllers/Platform/InvoiceController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformInvoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = PlatformInvoice::with('tenant')->latest()->paginate(20);
        return view('platform.invoices.index', compact('invoices'));
    }

    public function show(PlatformInvoice $invoice)
    {
        $invoice->load('tenant', 'subscription');
        return view('platform.invoices.show', compact('invoice'));
    }
}
```

## `app/Http/Controllers/Platform/SettingController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = PlatformSetting::all()->pluck('value', 'key');
        return view('platform.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            PlatformSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('status', 'Settings updated successfully.');
    }
}
```

## `app/Http/Controllers/Platform/AuditLogController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('admin')->latest()->paginate(50);
        return view('platform.audit-logs.index', compact('logs'));
    }
}
```

## `app/Http/Controllers/Platform/SessionController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\PlatformSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = PlatformSessionService::getSessions(Auth::guard('platform')->user());
        return view('platform.sessions.index', compact('sessions'));
    }

    public function destroy($sessionId)
    {
        PlatformSessionService::deleteSession($sessionId);
        return back()->with('status', 'Session terminated.');
    }

    public function killAll()
    {
        PlatformSessionService::deleteAllSessions(Auth::guard('platform')->user());
        return back()->with('status', 'All sessions have been terminated.');
    }
}
```

## `app/Http/Controllers/Tenant/AuthController.php`

```php
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountLockService;
use App\Services\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('tenentViews.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && AccountLockService::isLocked($user)) {
            $remaining = AccountLockService::getRemainingLockMinutes($user);
            LoginLogService::logFailedLogin($request, 'Account Locked');
            return back()->withErrors(['email' => "Your account is locked. Please try again in {$remaining} minutes."]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            AccountLockService::resetAttempts($user);
            LoginLogService::logSuccessfulLogin($request);
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.dashboard'));
        }

        if ($user) {
            $isLocked = AccountLockService::recordFailedAttempt($user);
            if ($isLocked) {
                LoginLogService::logFailedLogin($request, 'Account Locked');
                return back()->withErrors(['email' => 'Your account has been locked due to too many failed login attempts.']);
            }
        }

        LoginLogService::logFailedLogin($request, 'Invalid Credentials');
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenantView.login');
    }
}
```

## `app/Http/Controllers/DoctorController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index()
    {
        $doctors = $this->doctorService->getAllDoctors();
        return view('doctors.index', compact('doctors'));
    }

    public function store(Request $request)
    {
        $this->doctorService->createDoctor($request->all());
        return back()->with('status', 'Doctor created successfully.');
    }

    public function update(Request $request, Doctor $doctor)
    {
        $this->doctorService->updateDoctor($doctor, $request->all());
        return back()->with('status', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $this->doctorService->deleteDoctor($doctor);
        return back()->with('status', 'Doctor deleted successfully.');
    }

    public function toggleStatus(Doctor $doctor)
    {
        $this->doctorService->toggleStatus($doctor);
        return back()->with('status', 'Doctor status updated.');
    }
}
```

## `app/Http/Controllers/PatientController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(15);
        return view('patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'age' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'cnic' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Patient::create($data);
        return back()->with('status', 'Patient registered successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $patients = Patient::where('name', 'LIKE', "%{$query}%")
                           ->orWhere('phone', 'LIKE', "%{$query}%")
                           ->get();
        return response()->json($patients);
    }

    public function showHistory(Patient $patient)
    {
        $patient->load(['tokens.doctor', 'tokens.invoice', 'tokens.prescription.items.medicine']);
        return view('patients.history', compact('patient'));
    }
}
```

## `app/Http/Controllers/TokenController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = Token::with(['patient', 'doctor'])->whereDate('created_at', today())->get();
        return view('tokens.index', compact('tokens'));
    }

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('tokens.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $todayCount = Token::where('doctor_id', $data['doctor_id'])->whereDate('created_at', today())->count();
        $data['token_number'] = $todayCount + 1;

        Token::create($data);

        return redirect()->route('tokens.index')->with('status', 'Token generated successfully.');
    }

    public function doctorDashboard()
    {
        $doctor = auth()->user()->doctor;
        if (!$doctor) {
            abort(403, 'You are not assigned as a doctor.');
        }

        $tokens = Token::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['waiting', 'in-progress'])
            ->orderBy('token_number')
            ->get();

        $currentToken = $tokens->where('status', 'in-progress')->first();

        return view('tokens.doctor-dashboard', compact('tokens', 'currentToken', 'doctor'));
    }

    public function callNextToken()
    {
        $doctor = auth()->user()->doctor;
        $currentToken = Token::where('doctor_id', $doctor->id)->where('status', 'in-progress')->first();
        if ($currentToken) {
            return back()->withErrors(['call' => 'Please complete the current token first.']);
        }

        $nextToken = Token::where('doctor_id', $doctor->id)
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->orderBy('token_number')
            ->first();

        if ($nextToken) {
            $nextToken->update(['status' => 'in-progress', 'called_at' => now()]);
        }

        return back();
    }

    public function completeToken($id)
    {
        $token = Token::findOrFail($id);
        $token->update(['status' => 'completed', 'completed_at' => now()]);
        return back();
    }
}
```

## `app/Http/Controllers/InvoiceController.php` (Tenant)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Token;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function store($token_id)
    {
        $token = Token::with(['doctor', 'service'])->findOrFail($token_id);

        $invoice = Invoice::create([
            'patient_id' => $token->patient_id,
            'token_id' => $token->id,
            'doctor_fee' => $token->doctor->consultation_fee ?? 0,
            'service_fee' => $token->service->fee ?? 0,
            'total_amount' => ($token->doctor->consultation_fee ?? 0) + ($token->service->fee ?? 0),
        ]);

        return redirect()->route('invoices.show', $invoice);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('patient', 'token.doctor');
        return view('invoices.show', compact('invoice'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        return back()->with('status', 'Invoice marked as paid.');
    }
}
```

## `app/Http/Controllers/PrescriptionController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\Token;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function create($token_id)
    {
        $token = Token::with('patient')->findOrFail($token_id);
        return view('prescriptions.create', compact('token'));
    }

    public function store(Request $request, $token_id)
    {
        $token = Token::findOrFail($token_id);
        $data = $request->validate([
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string',
            'items.*.days' => 'required|integer',
            'items.*.instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $token->patient_id,
            'doctor_id' => $token->doctor_id,
            'token_id' => $token->id,
            'diagnosis' => $data['diagnosis'],
            'notes' => $data['notes'],
        ]);

        $prescription->items()->createMany($data['items']);

        return redirect()->route('prescriptions.show', $prescription);
    }

    public function show(Prescription $prescription)
    {
        $prescription->load('patient', 'doctor', 'items.medicine');
        return view('prescriptions.show', compact('prescription'));
    }

    public function searchMedicine(Request $request)
    {
        $query = $request->input('query');
        $medicines = Medicine::where('name', 'LIKE', "%{$query}%")->where('stock_quantity', '>', 0)->get();
        return response()->json($medicines);
    }
}
```

## `app/Http/Controllers/PosController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function searchItems(Request $request)
    {
        $query = $request->input('query');
        $medicines = Medicine::where('name', 'LIKE', "%{$query}%")->orWhere('barcode', $query)->get();
        $services = Service::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json(['medicines' => $medicines, 'services' => $services]);
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            // ... other fields like discount, tax etc.
        ]);

        $sale = DB::transaction(function () use ($data, $request) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total_amount' => $data['total_amount'],
                'sale_number' => 'SALE-' . time(),
                // ...
            ]);

            foreach ($data['items'] as $item) {
                $sale->items()->create([
                    'itemable_id' => $item['id'],
                    'itemable_type' => $item['type'] === 'medicine' ? Medicine::class : Service::class,
                    'item_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                if ($item['type'] === 'medicine') {
                    Medicine::find($item['id'])->decrement('stock_quantity', $item['quantity']);
                }
            }
            return $sale;
        });

        return redirect()->route('pos.receipt', $sale);
    }

    public function showReceipt(Sale $sale)
    {
        $sale->load('items');
        return view('pos.receipt', compact('sale'));
    }
}
```

## `app/Http/Controllers/PharmacyController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        $lowStock = Medicine::where('stock_quantity', '<', 10)->get();
        $expired = Medicine::where('expiry_date', '<', now())->get();
        $expiringSoon = Medicine::whereBetween('expiry_date', [now(), now()->addMonth()])->get();

        return view('pharmacy.dashboard', compact('lowStock', 'expired', 'expiringSoon'));
    }
}
```

## `app/Http/Controllers/StaffController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index()
    {
        $staff = $this->staffService->getAllStaff();
        return view('staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $this->staffService->createStaff($request->all());
        return back()->with('status', 'Staff member created successfully.');
    }

    public function update(Request $request, User $staff)
    {
        $this->staffService->updateStaff($staff, $request->all());
        return back()->with('status', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        $this->staffService->deleteStaff($staff);
        return back()->with('status', 'Staff member deleted successfully.');
    }

    public function toggleStatus(User $staff)
    {
        $this->staffService->toggleStatus($staff);
        return back()->with('status', 'Staff member status updated.');
    }
}
```

## `app/Http/Controllers/TrialController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrialController extends Controller
{
    public function showForm()
    {
        return view('trial.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($data) {
            $tenant = Tenant::create([
                'name' => $data['name'],
                'domain' => $data['domain'],
                'owner_name' => $data['owner_name'],
                'owner_email' => $data['owner_email'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $tenant->users()->create([
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make($data['password']),
                'role' => 'admin',
            ]);
        });

        return redirect()->route('landing')->with('status', 'Your trial account has been created! You can now log in.');
    }
}
```

## `app/Http/Controllers/DashboardController.php` (Tenant)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Token;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $todayTokens = Token::whereDate('created_at', today())->count();
        $waitingTokens = Token::whereDate('created_at', today())->where('status', 'waiting')->count();
        $totalPatients = Patient::count();

        return view('tenentViews.dashboard', compact('todayTokens', 'waitingTokens', 'totalPatients'));
    }
}
```

## `app/Http/Controllers/LandingController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }
}
```

## `app/Repositories/DoctorRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    public function getAll()
    {
        return Doctor::latest()->get();
    }

    public function create(array $data)
    {
        return Doctor::create($data);
    }

    public function update(Doctor $doctor, array $data)
    {
        $doctor->update($data);
        return $doctor;
    }

    public function delete(Doctor $doctor)
    {
        return $doctor->delete();
    }
}
```

## `app/Repositories/StaffRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffRepository
{
    public function getAll()
    {
        // Assuming 'admin' is the owner, so we exclude them.
        return User::where('role', '!=', 'admin')->latest()->get();
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'staff',
        ]);
    }

    public function update(User $staff, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $staff->update($data);
        return $staff;
    }

    public function delete(User $staff)
    {
        return $staff->delete();
    }
}
```

## `app/Services/DoctorService.php`

```php
<?php

namespace App\Services;

use App\Models\Doctor;
use App\Repositories\DoctorRepository;

class DoctorService
{
    protected $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAllDoctors()
    {
        return $this->doctorRepository->getAll();
    }

    public function createDoctor(array $data)
    {
        // Add validation or other business logic here
        return $this->doctorRepository->create($data);
    }

    public function updateDoctor(Doctor $doctor, array $data)
    {
        return $this->doctorRepository->update($doctor, $data);
    }

    public function deleteDoctor(Doctor $doctor)
    {
        // Add logic to handle related records if needed
        return $this->doctorRepository->delete($doctor);
    }

    public function toggleStatus(Doctor $doctor)
    {
        $doctor->is_active = !$doctor->is_active;
        $doctor->save();
        return $doctor;
    }
}
```

## `app/Services/StaffService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\StaffRepository;

class StaffService
{
    protected $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function getAllStaff()
    {
        return $this->staffRepository->getAll();
    }

    public function createStaff(array $data)
    {
        // Add validation or other business logic here
        return $this->staffRepository->create($data);
    }

    public function updateStaff(User $staff, array $data)
    {
        return $this->staffRepository->update($staff, $data);
    }

    public function deleteStaff(User $staff)
    {
        return $this->staffRepository->delete($staff);
    }

    public function toggleStatus(User $staff)
    {
        $staff->is_active = !$staff->is_active;
        $staff->save();
        return $staff;
    }
}
```

## `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
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
use Illuminate\View\View;

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // This is the default Breeze registration.
        // For this project, trial registration is handled by TrialController.
        // This can be used for direct user registration if needed in the future.

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
```