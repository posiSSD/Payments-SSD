<?php
include '../env.php';
include ROOT_PATH.'/db.php';
include ROOT_PATH.'/sys/helpers.php';
include ROOT_PATH.'/payphone/sys/helpers.php';
include ROOT_PATH.'/Payments-SSD/api/Controller.php';

/*
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "totalbet.ec") !== false) {

} else{
    Echo "Gracias por su visita";
}
*/
$transaccion = $_GET["id"];
$client = $_GET["clientTransactionId"];

$data_array = array(
    "id" => (int)$transaccion,
    "clientTxId" => $client
);

$data_array_response = api_button_V2_Confirm ($data_array);
create_or_update_bd_api_transactions($data_array_response);

$data_array_response_details = payphone_get_details($data_array_response);

 

// Imprimir $data_array_response_details


///////////////////NUEVO CODIGO //////////////////////////////
$payment_limits=explode(',', env('DEPOSIT_LIMITS'));
// definir parametros de log
$log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
$log_file = date("Y-m-d").".log";
log_init($log_dir,$log_file);
// iniciar log
log_write('-----------------------------------------------------------------------------------------');
log_write('_POST');
log_write($_POST);
log_write('_GET');
log_write($_GET);
log_write('_SERVER');
log_write($_SERVER);
log_write('json');
log_write($data_array_response_details);
// declarar actividad y retorno
$a=[];
$ret=[];

// declarar respuestas en caso de error
$http_code = 500;
$status = 'Error';
$response = [];  



switch ($data_array_response_details['transactionStatus']){
    case "Approved":
        // declarar el update
        $new_trans=[];
        $new_trans['unique_id']=$data_array_response_details['unique_id'];
        $new_trans['client_id']=$data_array_response_details['client_id'];
        $new_trans['status']=9; // 3=pending deposit
        $new_trans['payment_id']=$transaccion;
      
        

        create_or_update_transaction($new_trans);

        //desde de aqui
        
        $d=[];
            $d['account']=$data_array_response_details['client_id'];
            $d['amount']=$data_array_response_details['amount'];
            $d['order_id']=$data_array_response_details['paymentId'];
            $d['payment_method']="payphone";
        
        $bc_deposit = bc_deposit($d);

        //simular $bc_deposit['result']['trx_id']
        $bc_deposit['result']['trx_id'] = 1111111;
        //ver la respuesta
        //var_dump($bc_deposit);
        
        if(array_key_exists('http_code', $bc_deposit)){
            if($bc_deposit['http_code']==200){
                // declarar el update
                $new_trans=[];
                $new_trans['unique_id']=$data_array_response_details['unique_id'];
                $new_trans['status']=7; // 3=paid
                $new_trans['wallet_id']=$bc_deposit['result']['trx_id'];
                // ejecutar el update
                create_or_update_transaction($new_trans);
                // todo bien, transaccion pagada
                $ret['http_code']=200;
                $ret['status']='Ok';
                $ret['response']='Order '.$transaccion.' paid';
                //api_ret($ret);
            }
        }          
    break;
    case "Canceled":
        
    break;
    
}

function api_ret($r){
	
	
	api_activities($r);
	
	log_write($r);
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
	$insert_command.= (array_key_exists('request', $a)?"'".$a['request']."'":'NULL');
	$insert_command.= ',';
	$insert_command.= (array_key_exists('response', $a)?"'".$a['response']."'":'NULL');
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