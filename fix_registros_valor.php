<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RegistroParqueadero;

$registros = RegistroParqueadero::whereNotNull('hora_salida')->where('valor_total', 0)->get();
foreach ($registros as $registro) {
    $registro->valor_total = $registro->calcularValor();
    $registro->save();
    echo "Actualizado registro {$registro->id} a valor_total={$registro->valor_total}\n";
}
if ($registros->isEmpty()) {
    echo "No hay registros con valor_total 0\n";
}
