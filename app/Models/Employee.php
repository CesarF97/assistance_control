<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'ci', 'rif', 'email', 'phone', 'qr'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->qr = 'https://t3.gstatic.com/licensed-image?q=tbn:ANd9GcSh-wrQu254qFaRcoYktJ5QmUhmuUedlbeMaQeaozAVD4lh4ICsGdBNubZ8UlMvWjKC';
        });
    }

    public function assistances()
    {
        return $this->hasMany(Assistance::class);
    }
}
