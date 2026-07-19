<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    protected $model;

    public function __construct(Doctor $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->latest()->paginate(10);
    }

    public function create(array $data): Doctor
    {
        return $this->model->create($data);
    }

    public function findOrFail($id): Doctor
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, array $data): Doctor
    {
        $doctor = $this->findOrFail($id);
        $doctor->update($data);
        return $doctor;
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }
    
    // Active doctors drop down ke liye
    public function getActiveDoctors()
    {
        return $this->model->where('is_active', true)->get();
    }
}