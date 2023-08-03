<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    protected $employeeRepository;
    
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $employees = $this->employeeRepository->getPaginated();
        return Inertia::render('Employees/Index', compact('employees'));
    }


    public function store(Request $request)
    {
        //
    }

    public function show(Employee $employee)
    {
        //
    }

    public function edit(Employee $employee)
    {
        //
    }

    public function update(Request $request, Employee $employee)
    {
        //
    }

    public function destroy(Employee $employee)
    {
        try {
            $this->employeeRepository->destroy($employee);
            return back()->with('success', 'Empleado eliminadoe exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'Este empleado no puede ser eliminado porque ya posee asistencias registradas');
        }
    }
}
