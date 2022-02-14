<?php 
require '/var/www/payments.apuestatotal.app/kushki/env.php';
require '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/html/sys/helpers.php';


$log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
$log_file = date("Y-m-d").".log";
log_init($log_dir,$log_file);

$visit = [];
$visit["init"]=date("Y-m-d H:i:s");
$visit['ip']=$_SERVER['REMOTE_ADDR'];
$visit['device']=$_SERVER['HTTP_USER_AGENT'];
$visit['ref']=(array_key_exists('HTTP_REFERER', $_SERVER)?$_SERVER['HTTP_REFERER']:'direct');
$visit['url']=$_SERVER['REQUEST_URI'];

$visit_insert = "INSERT INTO 
					tbl_visits 
					(url,ip,server_info) 
					VALUES ('".$visit["url"]."','".$visit['ip']."','".print_r($_SERVER,true)."')";
$mysqli->query($visit_insert);
if($mysqli->error){
	echo $mysqli->error; die; 
}
$mysqli->close();
log_write($visit);

?>
:)