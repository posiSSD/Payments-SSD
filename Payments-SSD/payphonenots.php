<?php
include '../env.php';
include ROOT_PATH.'/db.php';
include ROOT_PATH.'/sys/helpers.php';
include ROOT_PATH.'/payphone/sys/helpers.php';
include ROOT_PATH.'/Payments-SSD/api/Controller.php';

$id = $_GET["id"];
$transaccion = $_GET["clientTransactionId"];

$data_array = array(
    "id" => (int)$id,
    "clientTxId" => $transaccion
);

$payment_limits=explode(',', env('DEPOSIT_LIMITS'));
$log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
$log_file = date("Y-m-d").".log";
log_init($log_dir, $log_file);
log_write('-----------------------------------------------------------------------------------------');
log_write('_POST');
log_write($_POST);
log_write('_GET');
log_write($_GET);
log_write('_SERVER');
log_write($_SERVER);

$a=[];
$ret=[];
$http_code = 500;
$status = 'Error';
$response = [];
$limit_try = 0;

//comprobacion si la tranx existe:
$status_payphone_transactions = payphone_status_transaction($data_array);

// declarar el request para la actividad
$a['request']=$status_payphone_transactions;

if (!$status_payphone_transactions){

    $payphone_array_response = payphone_api_confirm ($data_array);// obtener detalles de la tx en la api de payphone
    //consolelogdata($payphone_array_response);

    if($payphone_array_response){

        payphone_api_transactions($payphone_array_response);
        $payphone_array_response['Response'] = "True";
        log_write('json');
        log_write($payphone_array_response);

        // obtener client_id, amount,  
        $data_array_response_details = payphone_bd_details($payphone_array_response);
        //consolelogdata($data_array_response_details); 

        switch ($payphone_array_response['transactionStatus']){

            case "Approved":
                
                $new_trans=[];
                $new_trans['unique_id']=$data_array_response_details['unique_id'];
                $new_trans['client_id']=$data_array_response_details['client_id'];
                $new_trans['status']=9; // 3=pending deposit
                $new_trans['order_id']=$payphone_array_response['transactionId'];
                $new_trans['payment_id']=$payphone_array_response['transactionId'];
                create_or_update_transaction($new_trans);
                sleep(10);
                // llamar BC
                $d=[];
                $d['account']=$data_array_response_details['client_id'];
                $d['amount']=$data_array_response_details['amount'];
                $d['order_id']=$payphone_array_response['transactionId'];
                $d['payment_method']='payphone'; // 4 = payphone
                //consolelogdata($d);

                do{
                    $bc_deposit = bc_deposit($d);
                    //consolelogdata($bc_deposit);
                    if(array_key_exists('http_code', $bc_deposit)){
                        if ($bc_deposit['http_code']==200){
                            $new_trans=[];
                            $new_trans['unique_id']=$data_array_response_details['unique_id'];
                            $new_trans['client_id']=$data_array_response_details['client_id'];
                            $new_trans['status']=7; // 3=paid
                            $new_trans['wallet_id']=$bc_deposit['result']['trx_id'];
                            $new_trans['payment_id']=$payphone_array_response['transactionId'];
                            create_or_update_transaction($new_trans);
                            $ret['http_code']=200;
                            $ret['status']='Ok';
                            $ret['response']='Order '.$transaccion.' paid';
                            api_ret($ret);
                        } else {
                            $new_trans=[];
                            $new_trans['unique_id']=$data_array_response_details['unique_id'];
                            $new_trans['client_id']=$data_array_response_details['client_id'];
                            $new_trans['status']=11; // 11 = 5 = failed deposit
                            $new_trans['payment_id']=$payphone_array_response['transactionId'];
                            create_or_update_transaction($new_trans);
                            $ret['http_code']=$bc_deposit['http_code'];
                            $ret['status']='Error';
                            $ret['response']='Order '.$transaccion.'wrong, check logs';
                            api_ret($ret);
                        }
                    }
                    $limit_try++;
                    sleep(5);
                } while ($bc_deposit['http_code'] !== 200 || ($limit_try <= 3));
                exit();   
            break;

            case "Canceled": 

                $new_trans=[];
                $new_trans['unique_id']=$data_array_response_details['unique_id'];
                $new_trans['client_id']=$data_array_response_details['client_id'];
                $new_trans['status']=10; // 10 = 4=declined by payment
                create_or_update_transaction($new_trans);
                $ret['http_code']=400;
                $ret['status']='Canceled';
                $ret['response']='Order '.$transaccion.' Canceled';
                api_ret($ret);
                exit();   
            break;    
        }

    }else{
        
        $data_array['Response'] = "False";
        log_write('json');
        log_write($data_array);
    }
   
} else {
    exit();
}

function api_ret($r){
	api_activities($r);
	log_write($r);
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

function consolelogdata($data) {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $pFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'Unknown Function';

    echo '<script>';
    echo 'console.log("'. $pFunction . '");';
    echo 'console.log(": ", ' . json_encode($data) . ');';
    echo '</script>';
}

?>