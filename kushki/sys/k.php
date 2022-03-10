<?php
include '/var/www/payments.apuestatotal.app/kushki/env.php';
include '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/payments.apuestatotal.app/kushki/sys/helpers.php';

$ret["status"] = 500;
$ret["return"] = "Error";
$unique_id = md5(microtime().rand(0,1000));

if(isset($_POST['kushki_create_payment_button'])){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));

	if(
		$_POST['kushki_create_payment_button']['kushki_value']<$payment_limits[0]
		||
		$_POST['kushki_create_payment_button']['kushki_value']>$payment_limits[1]
	){
		$ret['status']=406;
		$ret['error']='Ocurrio un error, el monto está fuera de los limites, refresca la pagina y vuelve a intentar.';
	}else{
		kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
		$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);
		if(array_key_exists("webcheckoutUrl", $kushki_create_payment_button)){
			$ret["status"] = 201;
			$ret["return"] = "Ok";
			$ret["url"]=$kushki_create_payment_button["webcheckoutUrl"];
			kushki_create_or_update_transaction(['status'=>2,'order_id'=>$kushki_create_payment_button['webcheckoutId']]);
		}elseif(array_key_exists("curl_error", $kushki_create_payment_button)){
			$ret['status']=408;
			$ret['error']='Ocurrio un error, refresca la pagina y vuelve a intentar.';
		}else{
			$ret["status"] = 500;
			$ret["result"] = $kushki_create_payment_button;
			$ret["_POST"]=$_POST['kushki_create_payment_button'];
		}
	}
}
echo json_encode($ret);
?>