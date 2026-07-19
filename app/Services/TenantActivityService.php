<?php

namespace App\Services;

use App\Models\TenantActivityLog;
use Illuminate\Http\Request;

class TenantActivityService
{
    // Simple log
    public static function log(string $action, string $description = null, $subject = null): TenantActivityLog
    {
        return TenantActivityLog::log($action, $description, $subject);
    }

    // Login log
    public static function logLogin(): TenantActivityLog
    {
        return self::log('login', 'User logged in');
    }

    // Logout log
    public static function logLogout(): TenantActivityLog
    {
        return self::log('logout', 'User logged out');
    }

    // Patient created
    public static function logPatientCreated($patient): TenantActivityLog
    {
        return self::log('patient.create', "Created patient: {$patient->name}", $patient);
    }

    // Token created
    public static function logTokenCreated($token): TenantActivityLog
    {
        return self::log('token.create', "Created token #{$token->token_number}", $token);
    }

    // Token completed
    public static function logTokenCompleted($token): TenantActivityLog
    {
        return self::log('token.complete', "Completed token #{$token->token_number}", $token);
    }

    // Prescription created
    public static function logPrescriptionCreated($prescription): TenantActivityLog
    {
        return self::log('prescription.create', "Created prescription #{$prescription->id}", $prescription);
    }

    // Invoice generated
    public static function logInvoiceGenerated($invoice): TenantActivityLog
    {
        return self::log('invoice.generate', "Generated invoice of Rs. {$invoice->total_amount}", $invoice);
    }

    // Invoice paid
    public static function logInvoicePaid($invoice): TenantActivityLog
    {
        return self::log('invoice.pay', "Received payment Rs. {$invoice->total_amount}", $invoice);
    }

    // Sale completed
    public static function logSaleCompleted($sale): TenantActivityLog
    {
        return self::log('sale.complete', "Completed sale #{$sale->sale_number} (Rs. {$sale->total_amount})", $sale);
    }

    // Doctor created
    public static function logDoctorCreated($doctor): TenantActivityLog
    {
        return self::log('doctor.create', "Added doctor: {$doctor->name}", $doctor);
    }

    // Doctor updated
    public static function logDoctorUpdated($doctor): TenantActivityLog
    {
        return self::log('doctor.update', "Updated doctor: {$doctor->name}", $doctor);
    }

    // Doctor deleted
    public static function logDoctorDeleted($doctor): TenantActivityLog
    {
        return self::log('doctor.delete', "Deleted doctor: {$doctor->name}", $doctor);
    }
}