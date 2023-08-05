<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Employee();
    }

    public function all()
    {
        return $this->model->with('assistances')->all();
    }

    public function getPaginated(int $quantity = 10)
    {
        return $this->model->with('assistances')->paginate($quantity);
    }

    public function destroy(Employee $employee)
    {
        return $employee->delete();
    }

    public function save(Employee $employee){
        return $employee->save();
    }
}