<?php
include '../../env.php';
include '../../db.php';
include 'helpers.php';

//include '/var/www/payments.apuestatotal.app/prometeo/env.php';
//include '/var/www/payments.apuestatotal.app/prometeo/db.php';
//include '/var/www/payments.apuestatotal.app/prometeo/sys/helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";
/*
auth_token: "4AFE2CF984A12EF5318255352A86B617"
balance: 0
client_id: 1674627753
email: "sinpossio85@gmail.com"
kushki_value: 1
name: "posi "
order_id: undefined
this_url: "http://localhost:8081/payphone/"
*/
if(isset($_POST['create_payment_button'])){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));

	// clientTransactionId
	$_POST['create_payment_button']['unique_id'] = md5(microtime().rand(0,1000));
	$_POST['create_payment_button']['status'] = 6;

	create_or_update_transaction($_POST['create_payment_button']);
	$create_payment_button = create_payment_button($_POST['create_payment_button']);
	//Exitoso
	//{
	// "paymentId":"8rvzanUyzkO2XgdfGwlSA",
	// "payWithPayPhone":"https:\/\/pay.payphonetodoesposible.com\/PayPhone\/Index?paymentId=8rvzanUyzkO2XgdfGwlSA",
	// "payWithCard":"https:\/\/pay.payphonetodoesposible.com\/Anonymous\/Index?paymentId=8rvzanUyzkO2XgdfGwlSA"
	//}
	$_POST['create_payment_button']['paymentId'] = $create_payment_button['paymentId'];
	$_POST['create_payment_button']['payWithPayPhone'] = $create_payment_button['payWithPayPhone'];
	$_POST['create_payment_button']['payWithCard'] = $create_payment_button['payWithCard'];
	//Guardando datos en table prometeo_details y prometeo_transactions
	create_or_update_bd_api_details($_POST['create_payment_button']);
	
	//$update_kushi = details_payment_link($create_payment_button);
	//bd_update_prometeo($update_kushi);
	//fin datos en table prometeo_details y prometeo_transactions

	if(array_key_exists("payWithCard", $create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$create_payment_button["payWithCard"];
		$ret["id"]=$create_payment_button["paymentId"];
		//
		$_POST['create_payment_button']['status'] = 6;
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

?> 