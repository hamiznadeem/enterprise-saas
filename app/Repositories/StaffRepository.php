<?php

namespace App\Repositories;

use App\Models\User;

class StaffRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getStaffList()
    {
        // Super Admin ko list nahi karna (Admin panel handle karega)
        return $this->model->where('role', '!=', 'super_admin')->latest()->paginate(10);
    }

    public function createStaff(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStaff($id, array $data)
    {
        $staff = $this->model->findOrFail($id);
        $staff->update($data);
        return $staff;
    }

    public function deleteStaff($id)
    {
        return $this->model->destroy($id);
    }
}