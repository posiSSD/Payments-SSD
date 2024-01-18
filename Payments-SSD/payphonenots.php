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

consolelogdata($data_array);

$status_payphone_transactions = payphone_status_transaction($data_array);

consolelogdata($status_payphone_transactions);

if (!$status_payphone_transactions){
    //https://payments.totalbet.com/Payments-SSD/payphonenots.php?id=25680440&clientTransactionId=c82fe876b8dfba0db07b11b1f86e1a20
    //c82fe876b8dfba0db07b11b1f86e1a20
    //c82fe876b8dfba0db07b11b1f86e1a20
    //unique_id c82fe876b8dfba0db07b11b1f86e1a20
    $payphone_array_response = payphone_api_confirm ($data_array);
    payphone_api_transactions($payphone_array_response);
    $data_array_response_details = payphone_api_bd_details($payphone_array_response);
    consolelogdata($data_array_response_details);

    $payment_limits=explode(',', env('DEPOSIT_LIMITS'));
    $log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
    $log_file = date("Y-m-d").".log";
    log_init($log_dir,$log_file);
    log_write('-----------------------------------------------------------------------------------------');
    log_write('_POST');
    log_write($_POST);
    log_write('_GET');
    log_write($_GET);
    log_write('_SERVER');
    log_write($_SERVER);
    log_write('json');
    log_write($data_array_response_details);

    $a=[];
    $ret=[];
    $http_code = 500;
    $status = 'Error';
    $response = [];  

    if($data_array_response_details){
        switch ($data_array_response_details['transactionStatus']){
            case "Approved":
                
                $new_trans=[];
                $new_trans['unique_id']=$data_array_response_details['unique_id'];
                $new_trans['client_id']=$data_array_response_details['client_id'];
                $new_trans['status']=9; // 3=pending deposit
                $new_trans['payment_id']=$transaccion;
                
                create_or_update_transaction($new_trans);
                
                $d=[];
                $d['account']=$data_array_response_details['client_id'];
                $d['amount']=$data_array_response_details['amount'];
                $d['order_id']=$data_array_response_details['paymentId'];
                $d['payment_method']='payphone'; // 4 = payphone

                $bc_deposit = bc_deposit($d);
                //return ['http_code' => 200, 'status' => 'Ok', 'result' =>  $response];
                consolelogdata($bc_deposit); 
                            
                if(array_key_exists('http_code', $bc_deposit)){
                    if ($bc_deposit['http_code']==200){
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$data_array_response_details['unique_id'];
                        $new_trans['status']=7; // 3=paid
                        $new_trans['wallet_id']=$bc_deposit['result']['trx_id'];
                        
                        create_or_update_transaction($new_trans);
                        
                        $ret['http_code']=200;
                        $ret['status']='Ok';
                        $ret['response']='Order '.$transaccion.' paid';
                        api_ret($ret);

                    } elseif ($bc_deposit['http_code']==400){
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$data_array_response_details['unique_id'];
                        $new_trans['status']=10; // 11 failed deposit
                        
                        create_or_update_transaction($new_trans);
                    
                        $ret['http_code']=400;
                        $ret['status']='denied';
                        $ret['response']='Order '.$transaccion.' denied';
                        api_ret($ret);

                    } elseif ($bc_deposit['http_code']==408){
                    
                        $new_trans=[];
                        $new_trans['unique_id']=$data_array_response_details['unique_id'];
                        $new_trans['status']=10; // 11 failed deposit
                        
                        create_or_update_transaction($new_trans);
                        
                        $ret['http_code']=408;
                        $ret['status']='timeout';
                        $ret['response']='Order '.$transaccion.' timeout';
                        api_ret($ret);

                    } elseif ($bc_deposit['http_code']==402){
                    
                        $new_trans=[];
                        $new_trans['unique_id']=$data_array_response_details['unique_id'];
                        $new_trans['status']=10; // 11 failed deposit
                        
                        create_or_update_transaction($new_trans);
                        
                        $ret['http_code']=402;
                        $ret['status']='Validator fail';
                        $ret['response']='Order '.$transaccion.' Validator fail';
                        api_ret($ret);

                    } else {
                    
                        $new_trans=[];
                        $new_trans['unique_id']=$data_array_response_details['unique_id'];
                        $new_trans['status']=11; // 
                        
                        create_or_update_transaction($new_trans);
                        
                        $ret['http_code']=500;
                        $ret['status']='Error';
                        $ret['response']='Something went wrong, check logs';
                        api_ret($ret);

                    }
                }         
            break;
            case "Canceled":   
            break;    
        }
    }

} else {
    exit();
}









function api_ret($r){
	api_activities($r);
	log_write($r);
	exit();
}

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

    //consolelogdata($insert_command); 
	$mysqli->close();
}


//function personalizada para emitir salidas en la consola del navegador
function consolelogdata($data) {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $pFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'Unknown Function';

    echo '<script>';
    echo 'console.log("'. $pFunction . '");';
    echo 'console.log("Data:", ' . json_encode($data) . ');';
    echo '</script>';
}
//function personalizada para emitir salidas en la consola del navegador

?>