<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permissions middleware route pe lagega
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'consultation_fee' => 'required|numeric|min:0',
            'daily_patient_limit' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}