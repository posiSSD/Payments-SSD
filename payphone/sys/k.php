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

	consolelogdata($_POST['create_payment_button']);

	$create_payment_button = create_or_update_transaction($_POST['create_payment_button']);
	
	consolelogdata($create_payment_button);

	if(array_key_exists("payWithCard", $create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$create_payment_button["payWithCard"];
		$ret["id"]=$create_payment_button["paymentId"];
		//
		$ret["unique_id"]=$_POST['create_payment_button']['unique_id'];
		//
		$_POST['create_payment_button']['status'] = 8;
		$_POST['create_payment_button']['order_id'] = $create_payment_button['paymentId'];
		create_or_update_transaction($_POST['create_payment_button']);
	}elseif(array_key_exists("curl_error", $create_payment_button)){
		$ret['status']=408;
		$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
	}else{
		$ret["status"] = 500;
		$ret["result"] = $create_payment_button;
		$ret["_POST"]=$_POST['create_payment_button'];
	}	
	echo json_encode($ret);
}


/*
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

	//Guardando datos en table payphone_details y payphone_transactions
	create_or_update_bd_api_details($_POST['create_payment_button']);

	if(array_key_exists("payWithCard", $create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$create_payment_button["payWithCard"];
		$ret["id"]=$create_payment_button["paymentId"];
		//
		$ret["unique_id"]=$_POST['create_payment_button']['unique_id'];
		//
		$_POST['create_payment_button']['status'] = 8;
		$_POST['create_payment_button']['order_id'] = $create_payment_button['paymentId'];
		create_or_update_transaction($_POST['create_payment_button']);
	}elseif(array_key_exists("curl_error", $create_payment_button)){
		$ret['status']=408;
		$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
	}else{
		$ret["status"] = 500;
		$ret["result"] = $create_payment_button;
		$ret["_POST"]=$_POST['create_payment_button'];
	}	
	echo json_encode($ret);
}
*/
if(isset($_POST['status_payment_button'])){
	$ret_res = [];
	$status_payment_button = status_transaction($_POST['status_payment_button']);
	$ret_res = $status_payment_button;

	if(array_key_exists('status', $status_payment_button)){

		// new = waiting order_id 6
		// paid = money in client wallet 7
		// pending payment = waiting confirmation from payment method 8
		// pending deposit = waiting confirmation from wallet 9
		// declined payment = order declined by payment method 10
		// failed deposit = deposit failed by wallet 11
		$ret_res['status_response'] = $status_payment_button['status'];

	} else {
		$ret_res['status_response'] = null;
	}

	echo json_encode($ret_res);
}

function consolelogdata($data) {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $pFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'Unknown Function';

    echo '<script>';
    echo 'console.log("'. $pFunction . '");';
    echo 'console.log(": ", ' . json_encode($data) . ');';
    echo '</script>';
}  


?> 