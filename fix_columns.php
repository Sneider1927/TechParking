<?php

// Conexión directa a MySQL
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'panel_db';

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

try {
    // Agregar columna custom_name si no existe
    $result = $mysqli->query("SHOW COLUMNS FROM roles WHERE Field = 'custom_name'");
    if ($result->num_rows === 0) {
        $mysqli->query('ALTER TABLE roles ADD COLUMN custom_name VARCHAR(255) NULL AFTER name');
        echo "✓ Columna custom_name agregada\n";
    } else {
        echo "✓ Columna custom_name ya existe\n";
    }

    // Agregar columna description si no existe
    $result = $mysqli->query("SHOW COLUMNS FROM roles WHERE Field = 'description'");
    if ($result->num_rows === 0) {
        $mysqli->query('ALTER TABLE roles ADD COLUMN description LONGTEXT NULL AFTER custom_name');
        echo "✓ Columna description agregada\n";
    } else {
        echo "✓ Columna description ya existe\n";
    }

    // Agregar columna active si no existe
    $result = $mysqli->query("SHOW COLUMNS FROM roles WHERE Field = 'active'");
    if ($result->num_rows === 0) {
        $mysqli->query('ALTER TABLE roles ADD COLUMN active BOOLEAN DEFAULT 1 AFTER description');
        echo "✓ Columna active agregada\n";
    } else {
        echo "✓ Columna active ya existe\n";
    }

    echo "\n✓✓✓ Todas las columnas se han verificado/agregado correctamente ✓✓✓\n";
    $mysqli->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    $mysqli->close();
    exit(1);
}
