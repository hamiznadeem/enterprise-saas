<?php

namespace App\Services;

use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStaffList()
    {
        return $this->repository->getStaffList();
    }

    public function createStaff(array $data)
    {
        // Password ko hash karna zaroori hai
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        // Agar role nahi bheja toh default receptionist
        $data['role'] = $data['role'] ?? 'receptionist';
        
        return $this->repository->createStaff($data);
    }

    public function updateStaff($id, array $data)
    {
        // Agar password field blank hai toh update mat karo (warna rahega nahi)
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->repository->updateStaff($id, $data);
    }

    public function deleteStaff($id)
    {
        return $this->repository->deleteStaff($id);
    }

public function toggleStaffStatus($id)
{
    // Pehle current status nikalo
    $staff = \App\Models\User::findOrFail($id);
    
    // Status ko opposite kar do (1 ko 0, 0 ko 1)
    $newStatus = !$staff->is_active;
    
    // Ab update karo
    $this->repository->updateStaff($id, ['is_active' => $newStatus]);
}
}