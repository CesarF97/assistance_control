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
}
