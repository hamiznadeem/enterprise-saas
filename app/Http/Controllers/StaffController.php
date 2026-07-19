<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use App\Http\Requests\StoreStaffRequest;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $service;

    // Service ka automatically object ban jayega (Dependency Injection)
    public function __construct(StaffService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('staff.index', [
            'staff' => $this->service->getStaffList()
        ]);
    }

    public function store(StoreStaffRequest $request)
    {
        $this->service->createStaff($request->validated());
        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    public function update(StoreStaffRequest $request, $staff)
    {
        $this->service->updateStaff($staff, $request->validated());
        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy($staff)
    {
        $this->service->deleteStaff($staff);
        return redirect()->route('staff.index')->with('success', 'Staff member removed!');
    }

    public function toggleStatus($staff)
    {
        $this->service->toggleStaffStatus($staff);
        return back()->with('success', 'Staff status updated!');
    }
}