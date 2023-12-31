<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
