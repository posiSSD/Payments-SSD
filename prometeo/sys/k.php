<?php
include '../env.php';
include '../db.php';
include 'helpers.php';

//include '/var/www/payments.apuestatotal.app/prometeo/env.php';
//include '/var/www/payments.apuestatotal.app/prometeo/db.php';
//include '/var/www/payments.apuestatotal.app/prometeo/sys/helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";



if(isset($_POST['kushki_create_payment_button'])){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));

	
	$_POST['kushki_create_payment_button']['unique_id'] = md5(microtime().rand(0,1000));
	$_POST['kushki_create_payment_button']['status'] = 6;

	kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
	$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);

	//Guardando datos en table prometeo_details y prometeo_transactions
	bd_save_prometeo($kushki_create_payment_button);
	$update_kushi = details_payment_link($kushki_create_payment_button);
	bd_update_prometeo($update_kushi);
	//fin datos en table prometeo_details y prometeo_transactions
	
	if(array_key_exists("url", $kushki_create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["url"];
		$ret["id"]=$kushki_create_payment_button["id"];
		//
		$_POST['kushki_create_payment_button']['status'] = 6;
		$_POST['kushki_create_payment_button']['order_id'] = $kushki_create_payment_button['id'];
		//var_dump($_POST['kushki_create_payment_button']);
		kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
		//
	}elseif(array_key_exists("curl_error", $kushki_create_payment_button)){
		$ret['status']=408;
		$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
		//echo "esta ingresando a 408";
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['kushki_create_payment_button'];
		//echo "esta ingresando a 500";
	}	
	echo json_encode($ret);
}
////////////////////////////////////////////////////////////////////////////
/*

if(isset($_POST['prometeo_select_transactions'])){

	$result = prometeo_select_bd($_POST['prometeo_select_transactions']);
	$_POST['prometeo_select_transactions']['unique_id'] = $result['unique_id'];

	if($result["event_type"] == "payment.error"){
		$_POST['prometeo_select_transactions']['status'] = 8;
		kushki_create_or_update_transaction($_POST['prometeo_select_transactions']);
		$_POST['prometeo_select_transactions']['success'] = false;
	}else if($result["event_type"] == "payment.success"){
		$_POST['prometeo_select_transactions']['status'] = 9;
		kushki_create_or_update_transaction($_POST['prometeo_select_transactions']);
		$_POST['prometeo_select_transactions']['success'] = true;
	}else if($result["event_type"] == "payment.rejected"){
		$_POST['prometeo_select_transactions']['status'] = 10;
		kushki_create_or_update_transaction($_POST['prometeo_select_transactions']);
		$_POST['prometeo_select_transactions']['success'] = true;	
	}else if($result["event_type"] == "payment.cancelled"){
		$_POST['prometeo_select_transactions']['status'] = 11;
		kushki_create_or_update_transaction($_POST['prometeo_select_transactions']);
		$_POST['prometeo_select_transactions']['success'] = true;	
	}
	else{
		$_POST['prometeo_select_transactions']['success'] = false;
	}

	echo json_encode($_POST['prometeo_select_transactions']);
}
*/
?> 