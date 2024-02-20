<?php
include '../env.php';
include ROOT_PATH.'/db.php';
include ROOT_PATH.'/sys/helpers.php';
include ROOT_PATH.'/prometeo/sys/helpers.php';
include ROOT_PATH.'/Payments-SSD/api/Controller.php';
//include '/api/KushkiController.php';

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud como JSON
    $payload = file_get_contents("php://input");
    // Verificar si el cuerpo de la solicitud es JSON válido
    $data = json_decode($payload, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {

        if(isset($data)) {

            ////////////////////TIME///////////////////////////////
            $fecha_hora_actual = new DateTime('now', new DateTimeZone('America/Lima'));

            /////////////////// LOGS //////////////////////////////
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
            //log_write($data); 

            $a=[];
            $ret=[];
            $http_code = 500;
            $status = 'Error';
            $response = [];
            $limit_try = 0;

            // construccion y ordenamiento de la data
            $payphone_array_response = dataconstruccion($data);
            // revisa si existe el external_id en la BD
            $status_prometeo_transactions = prometeo_status_transaction($payphone_array_response);
            if(!$status_prometeo_transactions){

                prometeo_api_transactions($payphone_array_response);

                $payphone_array_response['response'] = "True";
                $payphone_array_response['Time'] = $fecha_hora_actual->format('Y-m-d H:i:s');
                log_write($payphone_array_response);
                                        
              
                //switch aprobacion transaccion
                switch ($payphone_array_response['event_type']) {

                    case "payment.success":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$payphone_array_response['external_id'];
                        $new_trans['client_id']=$payphone_array_response['id_usuario'];
                        $new_trans['status']=9; // 3=pending deposit
                        $new_trans['payment_id']=$payphone_array_response['intent_id'];
                        create_or_update_transaction($new_trans);
                        sleep(10);
                        $d=[];
                        $d['account']=$payphone_array_response['id_usuario'];
                        $d['amount']=$payphone_array_response['amount'];
                        $d['order_id']=$payphone_array_response['order_id'];
                        $d['payment_method']='prometeo'; // 3 = prometeo
                        //consolelogdata($d);

                        do{
                            $bc_deposit = bc_deposit($d);
                            //consolelogdata($bc_deposit);
                            if(array_key_exists('http_code', $bc_deposit)){
                                if ($bc_deposit['http_code']==200){
                                    $new_trans=[];
                                    $new_trans['unique_id']=$payphone_array_response['external_id'];
                                    $new_trans['client_id']=$payphone_array_response['id_usuario'];
                                    $new_trans['status']=7; // 3=paid
                                    $new_trans['wallet_id']=$bc_deposit['result']['trx_id'];
                                    $new_trans['payment_id']=$payphone_array_response['intent_id'];
                                    create_or_update_transaction($new_trans);
                                    $ret['http_code']=200;
                                    $ret['status']='Ok';
                                    $ret['response']='Order '.$payphone_array_response['external_id'].' paid';
                                    api_ret($ret);
                                }  else {  
                                    if( $limit_try <= 5 ){
                                        $new_trans=[];
                                        $new_trans['unique_id']=$payphone_array_response['external_id'];
                                        $new_trans['client_id']=$payphone_array_response['id_usuario'];
                                        $new_trans['status']=11; // 11 failed deposit
                                        $new_trans['payment_id']=$payphone_array_response['intent_id'];
                                        create_or_update_transaction($new_trans);
                                        $ret['http_code']=$bc_deposit['http_code'];
                                        $ret['status']='Error';
                                        $ret['response']='Order '.$payphone_array_response['external_id'].' wrong / Try '.$limit_try.' / check logs';
                                        api_ret($ret);
                                    } else {
                                        $ret['http_code']=$bc_deposit['http_code'];
                                        $ret['status']='Error';
                                        $ret['response']='Order '.$payphone_array_response['external_id'].' wrong / Try '.$limit_try.' / check logs';
                                        api_ret($ret);
                                    }                                 
                                }
                            }
                            $limit_try++;
                            sleep(5);
                        } while ($bc_deposit['http_code'] !== 200 || $limit_try <= 5);
                                   
                    break;

                    case "payment.error":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$payphone_array_response['external_id'];
                        $new_trans['client_id']=$payphone_array_response['id_usuario'];
                        $new_trans['status']=10; // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=400;
                        $ret['status']='Error';
                        $ret['response']='Order '.$payphone_array_response['external_id'].' error';
                        api_ret($ret);

                    break;

                    case "payment.rejected":

                        $new_trans=[];
                        $new_trans['unique_id']=$payphone_array_response['external_id'];
                        $new_trans['client_id']=$payphone_array_response['id_usuario'];
                        $new_trans['status']=10; // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=400;
                        $ret['status']='rejected';
                        $ret['response']='Order '.$payphone_array_response['external_id'].' rejected';
                        api_ret($ret);

                    break;

                    case "payment.cancelled":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$payphone_array_response['external_id'];
                        $new_trans['client_id']=$payphone_array_response['id_usuario'];
                        $new_trans['status']=10;  // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=500;
                        $ret['status']='cancelled';
                        $ret['response']='Order '.$payphone_array_response['external_id'].' cancelled';
                        api_ret($ret);

                    break;

                }
                exit();

            } else {

                $data['response'] = "False";
                $data['Time'] = $fecha_hora_actual->format('Y-m-d H:i:s');
                log_write('json');
                log_write($data);
                exit();               
            }
                                            
        } 
    } 
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

function responsejson($data) {

    // Convertir la respuesta a formato JSON
    $json_response = json_encode($data);
    // Establecer los encabezados de respuesta
    header('Content-Type: application/json');
    // Imprimir la respuesta
    echo $json_response;
    // DAR RESPUSETA AQUI A LA PETICION POST //
}

?>