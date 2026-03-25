<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RegistroParqueadero;

$r = RegistroParqueadero::with('vehiculo')->whereNotNull('hora_salida')->orderBy('id','desc')->first();
if (!$r) {
    echo "No hay registros con salida\n";
    exit;
}

echo "registro: {$r->id}\n";
echo "valor_total guardado: {$r->valor_total}\n";
echo "tipo: {$r->vehiculo->tipo}\n";
echo "hora_entrada: {$r->hora_entrada}\n";
echo "hora_salida: {$r->hora_salida}\n";
echo "calcValor(): {$r->calcularValor()}\n";
