<?php

use Illuminate\Support\Facades\DB;

try {
    // Agregar columna custom_name si no existe
    $columns = DB::select("SHOW COLUMNS FROM roles WHERE Field = 'custom_name'");
    if (empty($columns)) {
        DB::statement('ALTER TABLE roles ADD COLUMN custom_name VARCHAR(255) NULL AFTER name');
        echo "✓ Columna custom_name agregada\n";
    } else {
        echo "✓ Columna custom_name ya existe\n";
    }

    // Agregar columna description si no existe
    $columns = DB::select("SHOW COLUMNS FROM roles WHERE Field = 'description'");
    if (empty($columns)) {
        DB::statement('ALTER TABLE roles ADD COLUMN description LONGTEXT NULL AFTER custom_name');
        echo "✓ Columna description agregada\n";
    } else {
        echo "✓ Columna description ya existe\n";
    }

    // Agregar columna active si no existe
    $columns = DB::select("SHOW COLUMNS FROM roles WHERE Field = 'active'");
    if (empty($columns)) {
        DB::statement('ALTER TABLE roles ADD COLUMN active BOOLEAN DEFAULT 1 AFTER description');
        echo "✓ Columna active agregada\n";
    } else {
        echo "✓ Columna active ya existe\n";
    }

    echo "✓ Todas las columnas se han verificado/agregado correctamente\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
