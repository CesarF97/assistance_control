<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use App\Http\Requests\AssistanceRequest;
use App\Models\Employee;
use App\Repositories\AssistanceRepository;

class AssistanceController extends Controller
{
    protected $assistanceRepository;
    
    public function __construct(AssistanceRepository $assistanceRepository)
    {
        $this->assistanceRepository = $assistanceRepository;
    }
    public function index()
    {
        //
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

    public function update(Request $request, assistance $assistance)
    {
        //
    }


    public function destroy(assistance $assistance)
    {
        //
    }
}
