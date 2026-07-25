<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        if (!$tenant) {
            $this->command->warn('No tenant found. Run trial registration first.');
            return;
        }

        $user = User::where('tenant_id', $tenant->id)->first();
        if (!$user) {
            $this->command->warn('No user found for this tenant.');
            return;
        }

        // ── Branches ──
        $branches = [
            ['branch_name' => 'Main Branch', 'branch_code' => 'MB-001', 'address' => '123 Medical Street', 'phone' => '0300-1234567'],
            ['branch_name' => 'Downtown Branch', 'branch_code' => 'DB-001', 'address' => '456 Health Avenue', 'phone' => '0300-7654321'],
        ];

        foreach ($branches as $b) {
            UserBranch::firstOrCreate(
                ['tenant_id' => $tenant->id, 'branch_code' => $b['branch_code']],
                array_merge($b, [
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'is_active' => true,
                    'is_default' => $b['branch_code'] === 'MB-001',
                ])
            );
        }

        // ── Doctors ──
        $doctors = [
            ['name' => 'Dr. Ahmed Khan', 'specialization' => 'General Physician'],
            ['name' => 'Dr. Sara Ali', 'specialization' => 'Dermatologist'],
            ['name' => 'Dr. Usman Tariq', 'specialization' => 'Cardiologist'],
            ['name' => 'Dr. Fatima Noor', 'specialization' => 'Pediatrician'],
        ];

        foreach ($doctors as $d) {
            Doctor::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $d['name']],
                array_merge($d, ['tenant_id' => $tenant->id, 'is_active' => true])
            );
        }

        // ── Medicines ──
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'brand_name' => 'Panadol', 'generic_name' => 'Paracetamol', 'category' => 'Analgesic', 'sale_price' => 50, 'purchase_price' => 30, 'stock_quantity' => 500, 'expiry_date' => '2026-12-31', 'batch_number' => 'B-001', 'unit_name' => 'Tablet'],
            ['name' => 'Amoxicillin 250mg', 'brand_name' => 'Amoxil', 'generic_name' => 'Amoxicillin', 'category' => 'Antibiotic', 'sale_price' => 120, 'purchase_price' => 80, 'stock_quantity' => 200, 'expiry_date' => '2026-10-15', 'batch_number' => 'B-002', 'unit_name' => 'Capsule'],
            ['name' => 'Omeprazole 20mg', 'brand_name' => 'Losec', 'generic_name' => 'Omeprazole', 'category' => 'Antacid', 'sale_price' => 80, 'purchase_price' => 50, 'stock_quantity' => 300, 'expiry_date' => '2027-03-01', 'batch_number' => 'B-003', 'unit_name' => 'Capsule'],
            ['name' => 'Cetirizine 10mg', 'brand_name' => 'Zyrtec', 'generic_name' => 'Cetirizine', 'category' => 'Antihistamine', 'sale_price' => 35, 'purchase_price' => 20, 'stock_quantity' => 8, 'expiry_date' => '2026-09-30', 'batch_number' => 'B-004', 'unit_name' => 'Tablet'],
            ['name' => 'Metformin 500mg', 'brand_name' => 'Glucophage', 'generic_name' => 'Metformin', 'category' => 'Antidiabetic', 'sale_price' => 90, 'purchase_price' => 60, 'stock_quantity' => 5, 'expiry_date' => '2026-11-20', 'batch_number' => 'B-005', 'unit_name' => 'Tablet'],
            ['name' => 'Ibuprofen 400mg', 'brand_name' => 'Brufen', 'generic_name' => 'Ibuprofen', 'category' => 'NSAID', 'sale_price' => 65, 'purchase_price' => 40, 'stock_quantity' => 400, 'expiry_date' => '2027-01-15', 'batch_number' => 'B-006', 'unit_name' => 'Tablet'],
            ['name' => 'Azithromycin 500mg', 'brand_name' => 'Zithromax', 'generic_name' => 'Azithromycin', 'category' => 'Antibiotic', 'sale_price' => 180, 'purchase_price' => 120, 'stock_quantity' => 150, 'expiry_date' => '2026-08-10', 'batch_number' => 'B-007', 'unit_name' => 'Tablet'],
            ['name' => 'Losartan 50mg', 'brand_name' => 'Cozaar', 'generic_name' => 'Losartan', 'category' => 'Antihypertensive', 'sale_price' => 110, 'purchase_price' => 70, 'stock_quantity' => 250, 'expiry_date' => '2027-05-01', 'batch_number' => 'B-008', 'unit_name' => 'Tablet'],
        ];

        foreach ($medicines as $m) {
            Medicine::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $m['name']],
                array_merge($m, ['tenant_id' => $tenant->id, 'is_active' => true])
            );
        }

        // ── Patients ──
        $patients = [
            ['name' => 'Muhammad Hamiz', 'phone' => '0300-1111111', 'age' => 22, 'gender' => 'Male', 'blood_group' => 'B+', 'address' => 'Street 5, Lahore'],
            ['name' => 'Ayesha Siddiqui', 'phone' => '0312-2222222', 'age' => 30, 'gender' => 'Female', 'blood_group' => 'O+', 'address' => 'Block C, Karachi'],
            ['name' => 'Ali Raza', 'phone' => '0333-3333333', 'age' => 45, 'gender' => 'Male', 'blood_group' => 'A+', 'address' => 'Model Town, Lahore'],
            ['name' => 'Zainab Khan', 'phone' => '0345-4444444', 'age' => 28, 'gender' => 'Female', 'blood_group' => 'AB+', 'address' => 'Gulberg, Islamabad'],
            ['name' => 'Hassan Mehmood', 'phone' => '0301-5555555', 'age' => 55, 'gender' => 'Male', 'blood_group' => 'O-', 'address' => 'DHA Phase 5, Karachi'],
            ['name' => 'Sana Fatima', 'phone' => '0321-6666666', 'age' => 35, 'gender' => 'Female', 'blood_group' => 'B-', 'address' => 'F-10, Islamabad'],
        ];

        foreach ($patients as $p) {
            Patient::firstOrCreate(
                ['tenant_id' => $tenant->id, 'phone' => $p['phone']],
                array_merge($p, ['tenant_id' => $tenant->id])
            );
        }

        $this->command->info('Sample data seeded successfully!');
        $this->command->info('- 2 Branches');
        $this->command->info('- 4 Doctors');
        $this->command->info('- 8 Medicines (2 low stock)');
        $this->command->info('- 6 Patients');
    }
}