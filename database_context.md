# Database Schema


This document provides a detailed overview of the database schema for the application.

## Tables

### `users`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `email` | `varchar(255)` | `unique` |
| `email_verified_at` | `timestamp` | `nullable` |
| `password` | `varchar(255)` | |
| `remember_token` | `varchar(100)` | `nullable` |
| `role` | `varchar(255)` | `default('user')` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |
| `tenant_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `role` | `varchar(255)` | `default('user')` |
| `doctor_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `two_factor_enabled` | `tinyint(1)` | `default(0)` |
| `two_factor_method` | `varchar(20)` | `nullable` |
| `two_factor_secret` | `text` | `nullable` |
| `two_factor_recovery_codes` | `text` | `nullable` |
| `password_changed_at` | `timestamp` | `nullable` |
| `login_attempts` | `smallint` | `unsigned`, `default(0)` |
| `locked_until` | `timestamp` | `nullable` |

### `sessions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `varchar(255)` | `primary key` |
| `user_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `ip_address` | `varchar(45)` | `nullable` |
| `user_agent` | `text` | `nullable` |
| `payload` | `longtext` | |
| `last_activity` | `int` | `index` |

### `cache_locks`

| Column | Type | Modifiers |
| --- | --- | --- |
| `key` | `varchar(255)` | `primary key` |
| `owner` | `varchar(255)` | |
| `expiration` | `int` | `index` |

### `tenants`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `domain` | `varchar(255)` | `unique` |
| `database` | `varchar(255)` | `nullable`, `default(null)` |
| `status` | `enum('trial', 'active', 'suspended', 'expired')` | `default('trial')` |
| `trial_ends_at` | `datetime` | `nullable` |
| `business_type` | `varchar(255)` | `default('clinic')` |
| `outlets` | `int` | `default(1)` |
| `plan_id` | `bigint` | `unsigned`, `nullable` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `will_expire_at` | `datetime` | `nullable` |
| `owner_name` | `varchar(255)` | `nullable` |
| `owner_email` | `varchar(255)` | `nullable` |
| `enabled_modules` | `json` | `nullable` |
| `phone` | `varchar(255)` | `nullable` |
| `city` | `varchar(255)` | `nullable` |
| `location` | `varchar(255)` | `nullable` |
| `web_access_url` | `varchar(255)` | `nullable` |
| `deleted_at` | `timestamp` | `nullable` |
| `on_trial` | `tinyint(1)` | `default(1)` |

### `domains`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `domain` | `varchar(255)` | `unique` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `patients`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `name` | `varchar(255)` | |
| `phone` | `varchar(255)` | |
| `cnic` | `varchar(255)` | `nullable` |
| `age` | `varchar(255)` | |
| `gender` | `enum('male', 'female', 'other')` | |
| `address` | `text` | `nullable` |
| `emergency_contact` | `varchar(255)` | `nullable` |
| `blood_group` | `varchar(255)` | `nullable` |
| `allergies` | `text` | `nullable` |
| `medical_history` | `longtext` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `doctors`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `name` | `varchar(255)` | |
| `specialization` | `varchar(255)` | `nullable` |
| `consultation_fee` | `decimal(10, 2)` | `default(0)` |
| `phone` | `varchar(255)` | `nullable` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |
| `daily_patient_limit` | `int` | `default(30)` |

### `services`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `name` | `varchar(255)` | |
| `description` | `text` | `nullable` |
| `fee` | `decimal(10, 2)` | `default(0)` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `tokens`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `patient_id` | `bigint` | `unsigned`, `foreign key` |
| `doctor_id` | `bigint` | `unsigned`, `foreign key` |
| `service_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `token_number` | `varchar(255)` | |
| `status` | `enum('waiting', 'in-progress', 'completed', 'cancelled')` | `default('waiting')` |
| `is_walk_in` | `tinyint(1)` | `default(1)` |
| `called_at` | `timestamp` | `nullable` |
| `completed_at` | `timestamp` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `invoices`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `patient_id` | `bigint` | `unsigned`, `foreign key` |
| `token_id` | `bigint` | `unsigned`, `foreign key` |
| `doctor_fee` | `decimal(10, 2)` | `default(0)` |
| `service_fee` | `decimal(10, 2)` | `default(0)` |
| `total_amount` | `decimal(10, 2)` | `default(0)` |
| `discount` | `decimal(10, 2)` | `default(0)` |
| `status` | `enum('paid', 'unpaid', 'partial')` | `default('unpaid')` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `medicines`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `name` | `varchar(255)` | |
| `brand_name` | `varchar(255)` | `nullable` |
| `generic_name` | `varchar(255)` | |
| `category` | `varchar(255)` | `nullable` |
| `stock_quantity` | `int` | `default(0)` |
| `sale_price` | `decimal(10, 2)` | `default(0)` |
| `purchase_price` | `decimal(10, 2)` | `default(0)` |
| `expiry_date` | `date` | `nullable` |
| `batch_number` | `varchar(255)` | `nullable` |
| `barcode` | `varchar(255)` | `nullable`, `index` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |
| `unit_name` | `varchar(255)` | `default('Unit')` |

### `prescriptions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `patient_id` | `bigint` | `unsigned`, `foreign key` |
| `doctor_id` | `bigint` | `unsigned`, `foreign key` |
| `token_id` | `bigint` | `unsigned`, `foreign key` |
| `diagnosis` | `text` | `nullable` |
| `notes` | `text` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `prescription_items`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `prescription_id` | `bigint` | `unsigned`, `foreign key` |
| `medicine_id` | `bigint` | `unsigned`, `foreign key` |
| `dosage` | `varchar(255)` | `default('1-1-1')` |
| `days` | `int` | `default(3)` |
| `instructions` | `varchar(255)` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `sales`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `patient_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `user_id` | `bigint` | `unsigned`, `foreign key` |
| `sale_number` | `varchar(255)` | |
| `subtotal` | `decimal(10, 2)` | `default(0)` |
| `tax_percentage` | `decimal(10, 2)` | `default(0)` |
| `tax_amount` | `decimal(10, 2)` | `default(0)` |
| `discount_amount` | `decimal(10, 2)` | `default(0)` |
| `total_amount` | `decimal(10, 2)` | `default(0)` |
| `payment_method` | `varchar(255)` | `default('cash')` |
| `status` | `varchar(255)` | `default('completed')` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `sale_items`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `sale_id` | `bigint` | `unsigned`, `foreign key` |
| `itemable_type` | `varchar(255)` | |
| `itemable_id` | `bigint` | `unsigned` |
| `item_name` | `varchar(255)` | |
| `unit_price` | `decimal(10, 2)` | |
| `unit_name` | `varchar(255)` | `default('Unit')` |
| `quantity` | `int` | `default(1)` |
| `total_price` | `decimal(10, 2)` | |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `platform_admins`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `email` | `varchar(255)` | `unique` |
| `email_verified_at` | `timestamp` | `nullable` |
| `password` | `varchar(255)` | |
| `role` | `varchar(255)` | `default('platform_admin')` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `remember_token` | `varchar(100)` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |
| `login_attempts` | `smallint` | `unsigned`, `default(0)` |
| `locked_until` | `timestamp` | `nullable` |

### `plans`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `slug` | `varchar(255)` | `unique` |
| `description` | `text` | `nullable` |
| `price` | `decimal(8, 2)` | `default(0)` |
| `billing_cycle` | `varchar(255)` | `default('monthly')` |
| `trial_days` | `int` | `default(0)` |
| `limits` | `json` | `nullable` |
| `features` | `json` | `nullable` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `tenant_subscriptions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `plan_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `type` | `varchar(255)` | `default('trial')` |
| `amount` | `decimal(8, 2)` | `default(0)` |
| `notes` | `text` | `nullable` |
| `starts_at` | `datetime` | `nullable` |
| `ends_at` | `datetime` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `platform_invoices`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `subscription_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `invoice_number` | `varchar(255)` | `unique` |
| `amount` | `decimal(8, 2)` | |
| `tax` | `decimal(8, 2)` | `default(0)` |
| `total` | `decimal(8, 2)` | |
| `status` | `varchar(255)` | `default('paid')` |
| `due_date` | `datetime` | `nullable` |
| `paid_at` | `datetime` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `audit_logs`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `admin_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `action` | `varchar(255)` | |
| `subject_type` | `varchar(255)` | `nullable` |
| `subject_id` | `bigint` | `unsigned`, `nullable` |
| `description` | `varchar(255)` | `nullable` |
| `properties` | `json` | `nullable` |
| `ip_address` | `varchar(255)` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `platform_settings`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `key` | `varchar(255)` | `unique` |
| `value` | `text` | `nullable` |
| `group` | `varchar(255)` | `default('general')` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `platform_sales`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `platform_invoice_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `total` | `decimal(8, 2)` | `default(0)` |
| `status` | `varchar(255)` | `default('completed')` |
| `payment_method` | `varchar(255)` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `permissions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `guard_name` | `varchar(255)` | |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `roles`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `name` | `varchar(255)` | |
| `guard_name` | `varchar(255)` | |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `model_has_permissions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `permission_id` | `bigint` | `unsigned`, `foreign key` |
| `model_type` | `varchar(255)` | |
| `model_id` | `bigint` | `unsigned` |

### `model_has_roles`

| Column | Type | Modifiers |
| --- | --- | --- |
| `role_id` | `bigint` | `unsigned`, `foreign key` |
| `model_type` | `varchar(255)` | |
| `model_id` | `bigint` | `unsigned` |

### `role_has_permissions`

| Column | Type | Modifiers |
| --- | --- | --- |
| `permission_id` | `bigint` | `unsigned`, `foreign key` |
| `role_id` | `bigint` | `unsigned`, `foreign key` |

### `login_logs`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `user_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `tenant_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `email` | `varchar(255)` | `index` |
| `ip_address` | `varchar(45)` | `index` |
| `user_agent` | `text` | `nullable` |
| `device_type` | `varchar(20)` | `nullable`, `index` |
| `browser` | `varchar(50)` | `nullable` |
| `browser_version` | `varchar(20)` | `nullable` |
| `os` | `varchar(50)` | `nullable` |
| `os_version` | `varchar(20)` | `nullable` |
| `status` | `enum('success', 'failed')` | `index` |
| `reason` | `varchar(255)` | `nullable`, `index` |
| `created_at` | `timestamp` | `nullable`, `index` |

### `user_branches`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `user_id` | `bigint` | `unsigned`, `foreign key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `branch_name` | `varchar(255)` | |
| `branch_code` | `varchar(255)` | `nullable`, `unique` |
| `address` | `varchar(255)` | `nullable` |
| `phone` | `varchar(255)` | `nullable` |
| `is_default` | `tinyint(1)` | `default(0)` |
| `is_active` | `tinyint(1)` | `default(1)` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `tenant_activity_logs`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `tenant_id` | `bigint` | `unsigned`, `foreign key` |
| `user_id` | `bigint` | `unsigned`, `nullable`, `foreign key` |
| `action` | `varchar(255)` | `index` |
| `description` | `varchar(255)` | `nullable` |
| `subject_type` | `varchar(255)` | `nullable` |
| `subject_id` | `bigint` | `unsigned`, `nullable` |
| `ip_address` | `varchar(255)` | `nullable` |
| `user_agent` | `varchar(255)` | `nullable` |
| `properties` | `json` | `nullable` |
| `created_at` | `timestamp` | `nullable` |
| `updated_at` | `timestamp` | `nullable` |

### `platform_password_history`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `platform_admin_id` | `bigint` | `unsigned`, `foreign key` |
| `password` | `varchar(255)` | |
| `created_at` | `timestamp` | `nullable` |

### `password_reset_tokens`

| Column | Type | Modifiers |
| --- | --- | --- |
| `email` | `varchar(255)` | `primary key` |
| `token` | `varchar(255)` | |
| `created_at` | `timestamp` | `nullable` |

### `cache`

| Column | Type | Modifiers |
| --- | --- | --- |
| `key` | `varchar(255)` | `primary key` |
| `value` | `mediumtext` | |
| `expiration` | `int` | `index` |

### `jobs`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `queue` | `varchar(255)` | `index` |
| `payload` | `longtext` | |
| `attempts` | `tinyint` | `unsigned` |
| `reserved_at` | `int` | `unsigned`, `nullable` |
| `available_at` | `int` | `unsigned` |
| `created_at` | `int` | `unsigned` |

### `job_batches`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `varchar(255)` | `primary key` |
| `name` | `varchar(255)` | |
| `total_jobs` | `int` | |
| `pending_jobs` | `int` | |
| `failed_jobs` | `int` | |
| `failed_job_ids` | `longtext` | |
| `options` | `mediumtext` | `nullable` |
| `cancelled_at` | `int` | `nullable` |
| `created_at` | `int` | |
| `finished_at` | `int` | `nullable` |

### `failed_jobs`

| Column | Type | Modifiers |
| --- | --- | --- |
| `id` | `bigint` | `unsigned`, `auto-increment`, `primary key` |
| `uuid` | `varchar(255)` | `unique` |
| `connection` | `text` | |
| `queue` | `text` | |
| `payload` | `longtext` | |
| `exception` | `longtext` | |
| `failed_at` | `timestamp` | `default(current_timestamp)` |

### `platform_password_resets`

| Column | Type | Modifiers |
| --- | --- | --- |
| `email` | `varchar(255)` | `index` |
| `token` | `varchar(255)` | |
| `created_at` | `timestamp` | `nullable` |
