<?php
require '/var/www/payments.apuestatotal.app/kushki/env.php';
require '/var/www/payments.apuestatotal.app/kushki/db.php';

$a=[];

$response = [];
$http_code = 200;
$status = 'Ok';


$a['response']=$response;
$a['http_code']=$http_code;
$a['status']=$status;
api_activities($a);

function api_activities($a){
	global $mysqli;

	$insert_command = '';
	$insert_command.= 'INSERT INTO api_activities';
	$insert_command.= ' (ip,method,request,response,http_code,status)';
	$insert_command.= ' VALUES';
	$insert_command.= '(';
	$insert_command.= "'".$_SERVER['REMOTE_ADDR']."'";
	$insert_command.= ',';
	$insert_command.= "'".$_SERVER['REQUEST_METHOD']."'";
	$insert_command.= ',';
	$insert_command.= "'".json_encode($_REQUEST)."'";
	$insert_command.= ',';
	$insert_command.= (array_key_exists('response', $a)?"'".json_encode($a['response'])."'":'NULL');
	$insert_command.= ',';
	$insert_command.= (array_key_exists('http_code', $a)?"'".$a['http_code']."'":'NULL');
	// $insert_command.= $a['http_code'];
	$insert_command.= ',';
	$insert_command.= (array_key_exists('status', $a)?"'".$a['status']."'":'NULL');
	// $insert_command.= $a['status'];
	$insert_command.= '';
	$insert_command.= '';
	$insert_command.= ')';
	$insert_command.= '';

	$mysqli->query($insert_command);
	if($mysqli->error){
		echo $mysqli->error; 
		print_r($insert_command); exit();
	}
	$mysqli->close();
}


?>