<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Helper: Sirf tab add karo jab index exist na kare
        $addIndex = function (string $table, string $column, string $indexName = null) {
            $indexName = $indexName ?: "{$table}_{$column}_index";
            $exists = collect(DB::select("SHOW INDEXES FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
            if (!$exists) {
                Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            }
        };

        $addCompoundIndex = function (string $table, array $columns, string $indexName) {
            $exists = collect(DB::select("SHOW INDEXES FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
            if (!$exists) {
                Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                    $table->index($columns, $indexName);
                });
            }
        };

        $addUnique = function (string $table, string $column, string $indexName = null) {
            $indexName = $indexName ?: "{$table}_{$column}_unique";
            $exists = collect(DB::select("SHOW INDEXES FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
            if (!$exists) {
                Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
                    $table->unique($column, $indexName);
                });
            }
        };

        // ── Patients ──
        $addIndex('patients', 'phone');
        $addIndex('patients', 'cnic');
        $addIndex('patients', 'name');
        $addIndex('patients', 'gender');

        // ── Tokens ──
        $addIndex('tokens', 'status');
        $addIndex('tokens', 'token_number');
        $addCompoundIndex('tokens', ['doctor_id', 'status'], 'tokens_doctor_status_index');
        $addCompoundIndex('tokens', ['status', 'created_at'], 'tokens_status_created_index');

        // ── Sales ──
        $addIndex('sales', 'status');
        $addUnique('sales', 'sale_number', 'sales_sale_number_unique');
        $addIndex('sales', 'payment_method');

        // ── Invoices ──
        $addIndex('invoices', 'status');

        // ── Medicines ──
        $addIndex('medicines', 'generic_name');
        $addIndex('medicines', 'name');
        $addCompoundIndex('medicines', ['is_active', 'stock_quantity'], 'medicines_active_stock_index');

        // ── Doctors ──
        $addIndex('doctors', 'is_active');
        $addIndex('doctors', 'specialization');

        // ── Tenant Activity Logs ──
        $addIndex('tenant_activity_logs', 'created_at');
        $addCompoundIndex('tenant_activity_logs', ['tenant_id', 'action'], 'tal_tenant_action_index');
        $addCompoundIndex('tenant_activity_logs', ['tenant_id', 'user_id'], 'tal_tenant_user_index');
    }

    public function down(): void
    {
        // Down mein drop karna risky nahi hai — agar index nahi hai to error aata hai
        // Is liye down ko bhi safe banate hain
        $dropIndex = function (string $table, string $indexName) {
            $exists = collect(DB::select("SHOW INDEXES FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
            if ($exists) {
                Schema::table($table, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            }
        };

        $dropIndex('patients', 'patients_phone_index');
        $dropIndex('patients', 'patients_cnic_index');
        $dropIndex('patients', 'patients_name_index');
        $dropIndex('patients', 'patients_gender_index');
        $dropIndex('tokens', 'tokens_status_index');
        $dropIndex('tokens', 'tokens_token_number_index');
        $dropIndex('tokens', 'tokens_doctor_status_index');
        $dropIndex('tokens', 'tokens_status_created_index');
        $dropIndex('sales', 'sales_status_index');
        $dropIndex('sales', 'sales_sale_number_unique');
        $dropIndex('sales', 'sales_payment_method_index');
        $dropIndex('invoices', 'invoices_status_index');
        $dropIndex('medicines', 'medicines_generic_name_index');
        $dropIndex('medicines', 'medicines_name_index');
        $dropIndex('medicines', 'medicines_active_stock_index');
        $dropIndex('doctors', 'doctors_is_active_index');
        $dropIndex('doctors', 'doctors_specialization_index');
        $dropIndex('tenant_activity_logs', 'tenant_activity_logs_created_at_index');
        $dropIndex('tenant_activity_logs', 'tal_tenant_action_index');
        $dropIndex('tenant_activity_logs', 'tal_tenant_user_index');
    }
};