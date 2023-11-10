<?php
include '../../env.php';
include '../../db.php';
include 'helpers.php';

//include '/var/www/payments.apuestatotal.app/prometeo/env.php';
//include '/var/www/payments.apuestatotal.app/prometeo/db.php';
//include '/var/www/payments.apuestatotal.app/prometeo/sys/helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";

if(isset($_POST['create_payment_button'])){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));

	// clientTransactionId
	$_POST['create_payment_button']['unique_id'] = md5(microtime().rand(0,1000));
	$_POST['create_payment_button']['status'] = 6;

	create_or_update_transaction($_POST['create_payment_button']);
	$create_payment_button = create_payment_button($_POST['create_payment_button']);
	
	$_POST['create_payment_button']['paymentId'] = $create_payment_button['paymentId'];
	$_POST['create_payment_button']['payWithPayPhone'] = $create_payment_button['payWithPayPhone'];
	$_POST['create_payment_button']['payWithCard'] = $create_payment_button['payWithCard'];

	//Guardando datos en table prometeo_details y prometeo_transactions

	create_or_update_bd_api_details($_POST['create_payment_button']);

	if(array_key_exists("payWithCard", $create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$create_payment_button["payWithCard"];
		$ret["id"]=$create_payment_button["paymentId"];
		//
		$_POST['create_payment_button']['status'] = 8;
		$_POST['create_payment_button']['order_id'] = $create_payment_button['paymentId'];
		//var_dump($_POST['kushki_create_payment_button']);
		create_or_update_transaction($_POST['create_payment_button']);
		//
	}elseif(array_key_exists("curl_error", $create_payment_button)){
		$ret['status']=408;
		$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
		//echo "esta ingresando a 408";
	}else{
		$ret["status"] = 500;
		$ret["result"] = $create_payment_button;
		$ret["_POST"]=$_POST['create_payment_button'];
		//echo "esta ingresando a 500";
	}	
	echo json_encode($ret);
}


if(isset($_POST['status_payment_button'])){
	$ret_res = [];
	$status_payment_button = status_transaction($_POST['status_payment_button']);
	$ret_res = $status_payment_button;

	if(array_key_exists('status', $status_payment_button)){

		// new = waiting order_id
		// paid = money in client wallet
		// pending payment = waiting confirmation from payment method
		// pending deposit = waiting confirmation from wallet
		// declined payment = order declined by payment method
		// failed deposit = deposit failed by wallet

		if($status_payment_button['status'] == 7){ 		// paid = money in client wallet
			$ret_res['status_response'] = true;
		} else if ($status_payment_button['status'] == 10){ // declined payment = order declined by payment method
			$ret_res['status_response'] = true;
		} else if ($status_payment_button['status'] == 11){ // failed deposit = deposit failed by wallet
			$ret_res['status_response'] = true;
		} else if ($status_payment_button['status'] == 6){ // new = waiting order_id
			$ret_res['status_response'] = false;
		} else if ($status_payment_button['status'] == 8){ // pending payment = waiting confirmation from payment method
			$ret_res['status_response'] = false;
		} else if ($status_payment_button['status'] == 9){ // pending deposit = waiting confirmation from wallet
			$ret_res['status_response'] = false;
		} else {
			$ret_res['status_response'] = false;
		}
			
	} else {
		$ret_res['status_response'] = null;
	}

	//error_log("status_payment_button - \$ret_res: " . print_r($ret_res, true));

	echo json_encode($ret_res);
}

?> 