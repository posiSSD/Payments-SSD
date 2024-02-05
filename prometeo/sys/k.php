<?php
include '../../env.php';
include '../../db.php';
include 'helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";

if(isset($_POST['kushki_create_payment_button'])){
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
	consolelogdata($data);

	// Registra los datos de la solicitud POST y cualquier otro mensaje de error específico
    $error_message = 'Mensaje de error específico: ' . json_encode($_POST);
    error_log($error_message, 0);
    consolelogdata($error_message);

	// unique_id / client_id / order_id
	$d=[];
	$d['client_id']=$_POST['status_payment_button']['client_id'];
    $d['order_id']=$_POST['status_payment_button']['order_id'];
    $d['unique_id']=$_POST['status_payment_button']['unique_id'];

	$ret_res = [];
	$status_payment_button = status_transaction($d);

	if(array_key_exists('status', $status_payment_button)){

		// new = waiting order_id 6
		// paid = money in client wallet 7
		// pending payment = waiting confirmation from payment method 8
		// pending deposit = waiting confirmation from wallet 9
		// declined payment = order declined by payment method 10
		// failed deposit = deposit failed by wallet 11
		echo json_encode($ret_res);

	} else {
		$ret_res['status'] = null;
	}

	
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