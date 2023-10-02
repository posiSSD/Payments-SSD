<?php
function conectarBaseDeDatos($host, $usuario, $contrasena, $nombreBase, $puerto) {
    $mysqli = new mysqli($host, $usuario, $contrasena, $nombreBase, $puerto);
    if ($mysqli->connect_errno) {
        die("Error en la conexión a la base de datos: " . $mysqli->connect_error);
    }
    $mysqli->query("SET CHARACTER SET utf8");
    return $mysqli;
}

$con_host = env('DB_HOST');
$con_port = env('DB_PORT');

$con_db_name = env('DB_DATABASE');
$con_user = env('DB_USERNAME');
$con_pass = env('DB_PASSWORD');
$mysqli = conectarBaseDeDatos($con_host, $con_user, $con_pass, $con_db_name, $con_port);

$con_db_name_kushki = env('DB_DATABASE_KUSHKI');
$con_user_kushki = env('DB_USERNAME_KUSHKI');
$con_pass_kushki = env('DB_PASSWORD_KUSHKI');
$mysqli_kushki = conectarBaseDeDatos($con_host, $con_user_kushki, $con_pass_kushki, $con_db_name_kushki, $con_port);

$con_db_name_kushkipayment = env('DB_DATABASE_KUSHKIPAYMENT');
$con_user_kushkipayment = env('DB_USERNAME_KUSHKIPAYMENT');
$con_pass_kushkipayment = env('DB_PASSWORD_KUSHKIPAYMENT');
$mysqli_kushkipayment = conectarBaseDeDatos($con_host, $con_user_kushkipayment, $con_pass_kushkipayment, $con_db_name_kushkipayment, $con_port);

$date = date("Y-m-d H:i:s");
?>
