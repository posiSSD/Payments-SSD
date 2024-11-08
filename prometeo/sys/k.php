<?php
include '../../env.php';
include '../../db.php';
include 'helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";

if(isset($_POST['create_payment_button'])){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));
	// clientTransactionId
	$_POST['create_payment_button']['unique_id'] = md5(microtime().rand(0,1000));
	$_POST['create_payment_button']['status'] = 6;

	create_or_update_transaction($_POST['create_payment_button']);
	$kushki_create_payment_button = kushki_create_payment_button($_POST['create_payment_button']);

	//Guardando datos en table prometeo_details y prometeo_transactions
	bd_save_prometeo($kushki_create_payment_button);
	$update_kushi = details_payment_link($kushki_create_payment_button);
	bd_update_prometeo($update_kushi);
	
	if(array_key_exists("url", $kushki_create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["url"];
		$ret["id"]=$kushki_create_payment_button["id"];
		$ret["unique_id"]=$_POST['create_payment_button']['unique_id'];
		$_POST['create_payment_button']['status'] = 8;
		$_POST['create_payment_button']['order_id'] = $kushki_create_payment_button['id'];
		create_or_update_transaction($_POST['create_payment_button']);
	}elseif(array_key_exists("curl_error", $kushki_create_payment_button)){
		$ret['status']=408;
		$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['create_payment_button'];
	}	
	echo json_encode($ret);
}

if(isset($_POST['status_payment_button'])){
	$data = $_POST['status_payment_button'];

	$status_payment = status_transaction($data);
	$ret_res = [];
	

	if(array_key_exists('status', $status_payment)){

		// new = waiting order_id 6
		// paid = money in client wallet 7
		// pending payment = waiting confirmation from payment method 8
		// pending deposit = waiting confirmation from wallet 9
		// declined payment = order declined by payment method 10
		// failed deposit = deposit failed by wallet 11
		$ret_res = $status_payment;
		

	} else {
		$ret_res['status'] = null;
	}

	echo json_encode($ret_res);
}

/*
if(isset($_POST['status_payment_button'])){
	
	echo json_encode($ret);
}
*/