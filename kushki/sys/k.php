<?php
include '/var/www/payments.apuestatotal.app/kushki/env.php';
include '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/payments.apuestatotal.app/kushki/sys/helpers.php';
https://api.apuestatotal.com/v2/kushki/deposit$ret = [];
$ret["status"] = 500;
$ret["return"] = "Error";
$unique_id = md5(microtime().rand(0,1000));
if(isset($_POST['kushki_create_payment_button'])){
	// print_r($_POST); exit();
	kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
	$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);
	if(array_key_exists("webcheckoutUrl", $kushki_create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["webcheckoutUrl"];
		// print_r($kushki_create_payment_button); exit();
		kushki_create_or_update_transaction(['status'=>2,'order_id'=>$kushki_create_payment_button['webcheckoutId']]);
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['kushki_create_payment_button'];
	}
	// print_r($kushki_create_payment_button); exit();
}




echo json_encode($ret);
?>