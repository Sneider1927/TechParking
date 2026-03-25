<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RegistroParqueadero;

$r = RegistroParqueadero::with('vehiculo')->whereNotNull('hora_salida')->orderBy('id','desc')->first();
if (!$r) { echo "No hay registros con salida\n"; exit; }

$hora_entrada = $r->hora_entrada;
$hora_salida = $r->hora_salida;

echo "hora_entrada: {$hora_entrada->format('Y-m-d H:i:s.u')}\n";
echo "hora_salida: {$hora_salida->format('Y-m-d H:i:s.u')}\n";

echo "diffInSeconds: " . $hora_salida->diffInSeconds($hora_entrada) . "\n";

echo "diffInHours: " . $hora_salida->diffInHours($hora_entrada) . "\n";

echo "diffInRealHours: " . $hora_salida->floatDiffInHours($hora_entrada, false) . "\n";

echo "calcularValor() method: " . $r->calcularValor() . "\n";
