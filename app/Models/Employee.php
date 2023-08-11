<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'ci', 'rif', 'email', 'phone', 'qr'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $image = QrCode::generate($model->ci);
            $output_file = '/images/qr-code/'.$model->id.'.svg';
            Storage::disk('public')->put($output_file, $image);
            $model->qr = '../storage/images/qr-code/'.$model->id.'.svg';
            $model->save();
        });
    }

    public function assistances()
    {
        return $this->hasMany(Assistance::class);
    }
}
