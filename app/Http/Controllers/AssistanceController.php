<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Assistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AssistanceRequest;
use App\Http\Resources\AssitanceResource;
use App\Repositories\AssistanceRepository;

class AssistanceController extends Controller
{
    protected $assistanceRepository;
    
    public function __construct(AssistanceRepository $assistanceRepository)
    {
        $this->assistanceRepository = $assistanceRepository;
    }

    public function getByEmployee(int $employee_id)
    {
        $assistances = AssitanceResource::collection($this->assistanceRepository->getByEmployeeId($employee_id));
        return response()->json($assistances);
    }

    public function store(AssistanceRequest $request)
    {
        $employee = Employee::whereCi($request->dni)->first();
        
        $assistance = Assistance::make([
            'employee_id' => $employee->id,
            'type' => $request->type
        ]);
        
        $this->assistanceRepository->save($assistance);
        
        return back()->with('success', 'Accion Realizada Exitosamente');
    }

    public function destroy(assistance $assistance)
    {
        try {
            $this->assistanceRepository->destroy($assistance);
            return back()->with('success', 'Asistencia eliminado exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'Ha ocurrido un error');
        }
    }
}
