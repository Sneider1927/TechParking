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

echo "hora_entrada class: " . get_class($hora_entrada) . "\n";
echo "hora_salida class: " . get_class($hora_salida) . "\n";

// direct diffs
echo "diffInHours: " . $hora_salida->diffInHours($hora_entrada) . "\n";
// if negative? carbon diffInHours always abs? Actually if not absolute.
echo "diffInMinutes: " . $hora_salida->diffInMinutes($hora_entrada) . "\n";

echo "calcularValor() method: " . $r->calcularValor() . "\n";
