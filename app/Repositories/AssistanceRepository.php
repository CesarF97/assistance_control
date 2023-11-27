<?php

namespace App\Repositories;

use App\Models\Assistance;

class AssistanceRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Assistance();
    }

    public function all()
    {
        return $this->model->with('assistances')->all();
    }

    public function getPaginated(int $quantity = 10)
    {
        return $this->model->with('employee')->paginate($quantity);
    }
    public function save(Assistance $assistance)
    {
        return $assistance->save();
    }

    public function getByEmployeeId(int $id)
    {
        return $this->model->where('employee_id', $id)->get();
    }

    public function destroy(Assistance $assistance)
    {
        return $assistance->delete();
    }

    public function getByEmployeeIdBetweenDates($employee_id, $date_from, $date_to)
    {
        return $this->model->with('employee')->where('employee_id', $employee_id)->whereDate('created_at', '>=', $date_from)
            ->whereDate('created_at', '<=', $date_to)
            ->get();
    }
}
