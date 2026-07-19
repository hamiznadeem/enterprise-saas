<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Doctor\UpdateDoctorRequest;

class DoctorController extends Controller
{
    protected $service;

    // Service automatically inject ho jayegi
    public function __construct(DoctorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $doctors = $this->service->getDoctors();
        return view('doctors.index', compact('doctors'));
    }

    public function store(StoreDoctorRequest $request)
    {
        // Form Request ne validate kar liya, ab bas service ko bhej do
        $this->service->createDoctor($request->validated());
        
        return redirect()->back()->with('success', 'Doctor added successfully!');
    }

        public function update(UpdateDoctorRequest $request, $id)
    {
        $this->service->updateDoctor($id, $request->validated());
        return redirect()->back()->with('success', 'Doctor updated successfully!');
    }

    public function destroy($id)
    {
        $this->service->deleteDoctor($id);
        return redirect()->back()->with('success', 'Doctor deleted successfully!');
    }

        public function toggleStatus($id)
    {
        $this->service->toggleDoctorStatus($id);
        return back()->with('success', 'Doctor status updated!');
    }
}