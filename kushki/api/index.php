<?php
header('Content-Type: application/json; charset=utf-8');

include '/var/www/payments.apuestatotal.app/kushki/env.php';
include '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/payments.apuestatotal.app/kushki/sys/helpers.php';
include '/var/www/html/sys/helpers.php';

// definir parametros de log
$log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
$log_file = date("Y-m-d").".log";
log_init($log_dir,$log_file);

// declarar actividad y retorno
$a=[];
$ret=[];

// declarar respuestas en caso de error
$http_code = 500;
$status = 'Error';
$response = [];




// /** 
// * $webhook_signature: Backoffice > Profile > Services > Identifiers > Webhook Signature
// * $x_kushki_id: Request Header
// * $x_kushki_simple_signature: Request header
// */
// /**
// * 'WEBHOOK_SIGNATURE: environment variable must be set
// */
// $webhook_signature = env('KUSHKI_WEBHOOK_SIGNATURE');
// $x_kushki_id = (array_key_exists('HTTP_X_KUSHKI_ID',$_SERVER)?$_SERVER['HTTP_X_KUSHKI_ID']:false);
// $x_kushki_simple_signature = (array_key_exists('HTTP_X_KUSHKI_SIMPLESIGNATURE',$_SERVER)?$_SERVER['HTTP_X_KUSHKI_SIMPLESIGNATURE']:false);
// $signature_generated = hash_hmac("sha256", $x_kushki_id, $webhook_signature);
// if ($signature_generated === $x_kushki_simple_signature) {
// 	header("Status: 200 OK");
// } else {
// 	header("Status: 401 Not authenticated");
// }


// obtener JSON enviado
$json = json_decode(file_get_contents('php://input'),true);
// declarar objeto de json parseado
$json_data=[];
// $json = '{"id": "77167302-7e9b-4a94-bc6e-53bcbfa5c5e0", "token": "8aa4ea2fc4c54bbdb0ab344457e14ad2", "amount": {"iva": 0, "currency": "PEN", "subtotalIva": 0, "subtotalIva0": 1.28}, "status": "approvedTransaction", "created": 1646865170526, "product": [{"name": "Recarga Web Apuesta Total", "quantity": 1, "unitPrice": 1.28, "description": "Recarga Web Apuesta Total"}], "metadata": [], "smartLink": "55E0jmS_H", "merchantId": "20000000103794123000", "syncMetadata": "false", "ticketNumber": "771814626490155686", "paymentMethod": "creditCard", "contactDetails": [], "transactionType": "SALE", "publicMerchantId": "439837f90ccf48f6bf07207d315c7fc1", "transactionReference": "c419a054-e4f6-41a7-8b67-a94a8c7f7848"}';

// iniciar log
log_write('-----------------------------------------------------------------------------------------');
log_write('_POST');
log_write($_POST);
log_write('_GET');
log_write($_GET);
log_write('_SERVER');
log_write($_SERVER);
log_write('json');
log_write($json);

// validar que json exista
if($json){
	// $http_code=200;
	// $status='Ok';

	// si es array pasarlo directo, sino convertirlo en array
	if(is_array($json)){
		$json_data = $json;
	}else{
		$json_data = (json_decode($json,true));
	}

	// declarar el request para la actividad
	$a['request']=$json_data;

	// validar si el campo existe
	if(!array_key_exists('smartLink', $json_data)){
		$ret['http_code']=400;
		$ret['status']='Error';
		$ret['response']='Missing smartLink';
		api_ret($ret);
	}
	// validar si el campo existe
	if(!array_key_exists('status', $json_data)){
		$ret['http_code']=400;
		$ret['status']='Error';
		$ret['response']='Missing status';
		api_ret($ret);
	}

	// obtener el unique_id de la transaccion
	$trans = kushki_get_transaction(['order_id'=>$json_data['smartLink']]);

	if(!$trans){
		$ret['http_code']=404;
		$ret['status']='Error';
		$ret['response']='Order '.$json_data['smartLink'].' not found';
		api_ret($ret);
	}

	// solo permitir estado 2=pending payment
	if($trans['status']!=2){
		$ret['http_code']=428;
		$ret['status']='Error';
		$ret['response']='Order '.$json_data['smartLink'].' is not pending';
		api_ret($ret);		
	}


	switch ($json_data['status']) {
		// en caso de aprobado
		case 'approvedTransaction':
			// validar si el campo existe
			if(!array_key_exists('ticketNumber', $json_data)){
				$ret['http_code']=400;
				$ret['status']='Error';
				$ret['response']='Missing ticketNumber';
				api_ret($ret);
			}
			// declarar el update
			$new_trans=[];
				$new_trans['unique_id']=$trans['unique_id'];
				$new_trans['status']=3; // 3=pending deposit
				$new_trans['payment_id']=$json_data['ticketNumber'];
			// ejecutar el update
			kushki_create_or_update_transaction($new_trans);
		break;
		// en caso de declinado
		case 'declinedTransaction':
			// validar si el campo existe
			if(!array_key_exists('id', $json_data)){
				$ret['http_code']=400;
				$ret['status']='Error';
				$ret['response']='Missing id';
				api_ret($ret);
			}
			// declarar el update
			$new_trans=[];
				$new_trans['unique_id']=$trans['unique_id'];
				$new_trans['status']=4; // 4=declined by payment
				$new_trans['payment_id']=$json_data['id'];
			// ejecutar el update
			kushki_create_or_update_transaction($new_trans);
		break;
		
		default:
			// responder a un status no reconocido
			$ret['http_code']=400;
			$ret['status']='Error';
			$ret['response']='Unknown status '.$json_data['status'];
			api_ret($ret);
		break;
	}
	$ret['http_code']=200;
	$ret['status']='Ok';
	$ret['response']='Order '.$json_data['smartLink'].' updated';
	api_ret($ret);
}else{
	// retornar error al no json
	$ret['http_code']=400;
	$ret['status']='Error';
	$ret['response']='no json';

	api_ret($ret);
}

log_write('----------------------------------------------------------------------------------------- And Now His Watch Is Ended');

// imprimir el retorno y detener la ejecucion del php
function api_ret($r){
	global $a;
	api_activities(array_merge($r,$a));
	echo json_encode($r);
	exit();
}

// registrar la actividad 
function api_activities($a){
	global $mysqli;

	$insert_command = '';
	$insert_command.= 'INSERT INTO api_activities';
	$insert_command.= ' (ip,method,request,response,http_code,status)';
	$insert_command.= ' VALUES';
	$insert_command.= '(';
	$insert_command.= "'".(array_key_exists('REMOTE_ADDR',$_SERVER)?$_SERVER['REMOTE_ADDR']:'NULL')."'";
	$insert_command.= ',';
	$insert_command.= "'".(array_key_exists('REQUEST_METHOD',$_SERVER)?$_SERVER['REQUEST_METHOD']:'NULL')."'";
	$insert_command.= ',';
	// $insert_command.= "'".$a['json']."'";
	$insert_command.= (array_key_exists('request', $a)?"'".json_encode($a['request'])."'":'NULL');
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
		log_write('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> mysqli->error <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<-----------------------------');
		log_write($mysqli->error);
		log_write($insert_command);
		// echo $mysqli->error; 
		// print_r($insert_command); exit();
	}
	$mysqli->close();
}


?>