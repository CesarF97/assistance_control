<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'last_name', 'ci', 'rif', 'email', 'phone'];

    public function assistances()
    {
        return $this->hasMany(Assistance::class);
    }
}