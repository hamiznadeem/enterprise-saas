<?php

namespace App\Services;

use App\Repositories\DoctorRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Doctor;

class DoctorService
{
    protected $repository;

    // Dependency Injection 
    public function __construct(DoctorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDoctors()
    {
        return $this->repository->getAll();
    }

    public function createDoctor(array $data): Doctor
    {
        // Agar yahan koi complex business logic hota (jaise check karna ke kya account balance hai etc)
        // toh yahan likhte. Abhi simple hai isliye direct repository ko bhej rahe.
        return $this->repository->create($data);
    }

    public function getActiveDoctorsForDropdown()
    {
        return $this->repository->getActiveDoctors();
    }

        public function updateDoctor($id, array $data): Doctor
    {
        return $this->repository->update($id, $data);
    }

        public function deleteDoctor($id): bool
    {
        return $this->repository->delete($id);
    }

        public function toggleDoctorStatus($id)
    {
        $doctor = $this->repository->findOrFail($id);
        $this->repository->update($id, ['is_active' => !$doctor->is_active]);
    }
}