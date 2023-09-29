<?php
$con_host=env('DB_HOST');
$con_port=env('DB_PORT');
/////////////////////////////////////////////
$con_db_name=env('DB_DATABASE');
$con_user=env('DB_USERNAME');
$con_pass=env('DB_PASSWORD');
$mysqli = new mysqli($con_host, $con_user, $con_pass, $con_db_name,3306);
$mysqli->query("SET CHARACTER SET utf8");
/////////////////////////////////////////////
$con_db_name_kushki=env('DB_DATABASE_KUSHKI');
$con_user_kushki=env('DB_USERNAME_KUSHKI');
$con_pass_kushki=env('DB_PASSWORD_KUSHKI');
$mysqli_kushki = new mysqli($con_host, $con_user_kushki,
							$con_pass_kushki, $con_db_name_kushki, $con_port);
$mysqli_kushki->query("SET CHARACTER SET utf8");
/////////////////////////////////////////////
$con_db_name_kushkipayment=env('DB_DATABASE_KUSHKIPAYMENT');
$con_user_kushkipayment=env('DB_USERNAME_KUSHKIPAYMENT');
$con_pass_kushkipayment=env('DB_PASSWORD_KUSHKIPAYMENT');
$mysqli_kushkipayment = new mysqli($con_host, $con_user_kushkipayment,
								   $con_pass_kushkipayment, $con_db_name_kushkipayment, $con_port);
$mysqli_kushkipayment->query("SET CHARACTER SET utf8");
/////////////////////////////////////////////
if (mysqli_connect_errno()) {
	printf("Conexion fallida: %s\n", mysqli_connect_error());
	exit();
}
$date = date("Y-m-d H:i:s");