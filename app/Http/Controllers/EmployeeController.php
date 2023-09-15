<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Resources\AssitanceResource;
use App\Models\Employee;
use App\Repositories\AssistanceRepository;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    protected $employeeRepository;
    protected $assistanceRepository;
    
    public function __construct(EmployeeRepository $employeeRepository, AssistanceRepository $assistanceRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->assistanceRepository = $assistanceRepository;
    }

    public function index()
    {
        $employees = $this->employeeRepository->getPaginated();
        return Inertia::render('Employees/Index', compact('employees'));
    }


    public function store(EmployeeStoreRequest $request)
    {
        $this->employeeRepository->save(Employee::make($request->validated()));
    }

    public function update(EmployeeStoreRequest $request, Employee $employee)
    {
        $this->employeeRepository->save($employee->fill($request->validated()));
    }

    public function destroy(Employee $employee)
    {
        try {
            $this->employeeRepository->destroy($employee);
            return back()->with('success', 'Empleado eliminado exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'Este empleado no puede ser eliminado porque ya posee asistencias registradas');
        }
    }

    public function showAssistances(Employee $employee)
    {
        return Inertia::render('Employees/ShowAssistances', compact('employee'));
    }
}
