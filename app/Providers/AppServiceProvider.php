<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\PatientPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\TokenPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PrescriptionPolicy;
use App\Policies\SalePolicy;
use App\Policies\MedicinePolicy;
use App\Policies\BranchPolicy;
use App\Policies\ServicePolicy;
use App\Policies\StaffPolicy;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Token;
use App\Models\Invoice;
use App\Models\Prescription;
use App\Models\Sale;
use App\Models\Medicine;
use App\Models\Branch;
use App\Models\Service;




class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Policy Registration ──
        $this->registerPolicies();

        // ── Gates ──

        // Tenant Owner Gate — apne tenant pe full access
        Gate::define('is-tenant-owner', function (User $user) {
            return $user->role === 'owner';
        });

        // Branch Access Gate — kisi bhi branch ka data dekh sakta hai
        Gate::define('access-all-branches', function (User $user) {
            return $user->can('branch.access-all');
        });

        // Financial Reports Gate
        Gate::define('view-financial-reports', function (User $user) {
            return $user->can('report.financial');
        });

        // Patient Reports Gate
        Gate::define('view-patient-reports', function (User $user) {
            return $user->can('report.patient');
        });

        // Inventory Reports Gate
        Gate::define('view-inventory-reports', function (User $user) {
            return $user->can('report.inventory');
        });

        // Manage Settings Gate
        Gate::define('manage-tenant-settings', function (User $user) {
            return $user->can('setting.manage');
        });

        // Export Data Gate
        Gate::define('export-data', function (User $user) {
            return $user->can('data.export');
        });

        // Impersonate User Gate (future)
        Gate::define('impersonate-user', function (User $user) {
            return $user->can('user.impersonate');
        });
    }

    /**
     * Sab policies ko ek jagah register karo
     */
    private function registerPolicies(): void
    {
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(Doctor::class, DoctorPolicy::class);
        Gate::policy(Token::class, TokenPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Prescription::class, PrescriptionPolicy::class);
        Gate::policy(Sale::class, SalePolicy::class);
        Gate::policy(Medicine::class, MedicinePolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(User::class, StaffPolicy::class);
        // ActivityLog ke liye model-level policy zruri nahi, direct can() check
    }
}