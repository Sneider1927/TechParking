<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RegistroParqueadero extends Model
{
    protected $fillable = [
        'vehiculo_id',
        'user_id',
        'hora_entrada',
        'hora_salida',
        'valor_total',
        'pagado',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método para calcular valor según tipo de vehículo y tiempo
    public function calcularValor()
    {
        if (!$this->hora_salida) {
            return 0;
        }

        $horas = $this->hora_entrada->floatDiffInHours($this->hora_salida);
        $horas = abs($horas);
        $horas = max(1, ceil($horas)); // Mínimo 1 hora

        $tarifas = config('tarifas');
        $tarifa = $tarifas[$this->vehiculo->tipo] ?? 3000;

        return $horas * $tarifa;
    }

    // Método para calcular valor estimado (sin salida)
    public function calcularEstimado()
    {
        $horas = $this->hora_entrada->floatDiffInHours(now());
        $horas = abs($horas);
        $horas = max(1, ceil($horas));

        $tarifas = config('tarifas');
        $tarifa = $tarifas[$this->vehiculo->tipo] ?? 3000;

        return $horas * $tarifa;
    }
}
