<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route parameter se ID le lo (yeh object ya string dono ho sakta hai)
        $staffId = $this->route('staff');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staffId,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|in:receptionist,doctor,cashier,pharmacist,manager',
        ];

        // POST (Create) hone par password required, PUT (Edit) hone par optional
        if ($this->isMethod('PUT')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }
}