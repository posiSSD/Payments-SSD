<?php
function conectarBaseDeDatos($config) {
    $mysqli = new mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
    if ($mysqli->connect_errno) {
        die("Error en la conexión a la base de datos " . $config['database'] . ": " . $mysqli->connect_error);
    }
    $mysqli->query("SET CHARACTER SET utf8");
    return $mysqli;
}

// Configuración de la base de datos de at_payments_prueba
$databaseConfig = [
    'host' => env('DB_HOST'),
    'user' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'database' => env('DB_DATABASE'),
    'port' => env('DB_PORT'),
];
$mysqli = conectarBaseDeDatos($databaseConfig);

// Configuración de la base de datos de at_kushki
$kushkiConfig = [
    'host' => env('DB_HOST'),
    'user' => env('DB_USERNAME_KUSHKI'),
    'password' => env('DB_PASSWORD_KUSHKI'),
    'database' => env('DB_DATABASE_KUSHKI'),
    'port' => env('DB_PORT'),
];
$mysqli_kushki = conectarBaseDeDatos($kushkiConfig);

// Configuración de la base de datos de bc_kushkipayment
$kushkiPaymentConfig = [
    'host' => env('DB_HOST'),
    'user' => env('DB_USERNAME_KUSHKIPAYMENT'),
    'password' => env('DB_PASSWORD_KUSHKIPAYMENT'),
    'database' => env('DB_DATABASE_KUSHKIPAYMENT'),
    'port' => env('DB_PORT'),
];
$mysqli_kushkipayment = conectarBaseDeDatos($kushkiPaymentConfig);

$date = date("Y-m-d H:i:s");

?>
