<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $fillable = [
        'placa',
        'tipo',
    ];

    public function registros()
    {
        return $this->hasMany(RegistroParqueadero::class);
    }
}
