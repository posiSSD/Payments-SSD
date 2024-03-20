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
            // revisa si existe el external_id en la BD
            $prometeo_array_response = dataconstruccion($data);

            //comprobacion si la tranx existe para evitar duplicados            
            $status_prometeo_transactions = prometeo_status_transaction($prometeo_array_response);
            if(!$status_prometeo_transactions){

                prometeo_api_transactions($prometeo_array_response);

                $prometeo_array_response['response'] = "True";
                $prometeo_array_response['Time'] = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
                log_write($prometeo_array_response);
                                        
              
                //switch aprobacion transaccion
                switch ($prometeo_array_response['event_type']) {

                    case "payment.success":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$prometeo_array_response['external_id'];
                        $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                        $new_trans['status']=9; // 3=pending deposit
                        $new_trans['payment_id']=$prometeo_array_response['intent_id'];
                        create_or_update_transaction($new_trans);
                        sleep(5);
                        $d=[];
                        $d['account']=$prometeo_array_response['id_usuario'];
                        $d['amount']=$prometeo_array_response['amount'];
                        //$d['order_id']=$prometeo_array_response['order_id'];
                        $d['order_id']=$prometeo_array_response['operation_id'];
                        $d['payment_method']='prometeo'; // 3 = prometeo
                        //consolelogdata($d);
                        $bc_deposit = bc_deposit($d);
                        if(array_key_exists('http_code', $bc_deposit)){
                            if ($bc_deposit['http_code']==200){
                                $new_trans=[];
                                $new_trans['unique_id']=$prometeo_array_response['external_id'];
                                $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                                $new_trans['status']=7; // 3=paid
                                $new_trans['wallet_id']=$bc_deposit['result']['trx_id'];
                                $new_trans['payment_id']=$prometeo_array_response['intent_id'];
                                create_or_update_transaction($new_trans);
                                $ret['http_code']=200;
                                $ret['status']='Ok';
                                $ret['response']='Order '.$prometeo_array_response['external_id'].' paid';
                                api_ret($ret);
                            }  else {  
                                $new_trans=[];
                                $new_trans['unique_id']=$prometeo_array_response['external_id'];
                                $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                                $new_trans['status']=11; // 11 failed deposit
                                $new_trans['payment_id']=$prometeo_array_response['intent_id'];
                                create_or_update_transaction($new_trans);
                                $ret['http_code']=$bc_deposit['http_code'];
                                $ret['status']='Error';
                                $ret['response']='Order '.$prometeo_array_response['external_id'].' wrong / check logs';
                                api_ret($ret);                           
                            }
                        }
                                   
                    break;

                    case "payment.error":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$prometeo_array_response['external_id'];
                        $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                        $new_trans['status']=10; // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=400;
                        $ret['status']='Error';
                        $ret['response']='Order '.$prometeo_array_response['external_id'].' error';
                        api_ret($ret);

                    break;

                    case "payment.rejected":

                        $new_trans=[];
                        $new_trans['unique_id']=$prometeo_array_response['external_id'];
                        $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                        $new_trans['status']=10; // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=400;
                        $ret['status']='rejected';
                        $ret['response']='Order '.$prometeo_array_response['external_id'].' rejected';
                        api_ret($ret);

                    break;

                    case "payment.cancelled":
                        
                        $new_trans=[];
                        $new_trans['unique_id']=$prometeo_array_response['external_id'];
                        $new_trans['client_id']=$prometeo_array_response['id_usuario'];
                        $new_trans['status']=10;  // 10 = 4=declined by payment
                        create_or_update_transaction($new_trans);
                        $ret['http_code']=500;
                        $ret['status']='cancelled';
                        $ret['response']='Order '.$prometeo_array_response['external_id'].' cancelled';
                        api_ret($ret);

                    break;

                }
                exit();

            } else {

                $data['response'] = "Duplicity";
                $data['Time'] = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
                log_write('json');
                log_write($data);
                exit();               
            }
                                            
        } 
    } 
}

function api_ret($r){
    log_write($r);
    api_activities($r);
	
}

// registrar la actividad 
function api_activities($a){
	global $mysqli;

	global $mysqli;
    $bd = 'at_payments_prueba';
    $table = 'api_activities';
    $rq = []; 
    $ip = ( array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : NULL );
    $method = ( array_key_exists('REQUEST_METHOD',$_SERVER) ? $_SERVER['REQUEST_METHOD'] : NULL );
    $request = ( array_key_exists('request', $a) ? json_encode($a['request']) : NULL );
    $response = ( array_key_exists('response', $a) ? json_encode($a['response']) : NULL );
    $http_code = ( array_key_exists('http_code', $a) ? $a['http_code'] : NULL );
    $status = ( array_key_exists('status', $a) ? $a['status'] : NULL );
    $created_at = ( new DateTime('now', new DateTimeZone('America/Lima')) )->format('Y-m-d H:i:s');
    $updated_at = ( new DateTime('now', new DateTimeZone('America/Lima')) )->format('Y-m-d H:i:s');


    $sql_insert = "INSERT INTO $table (ip,method,request,response,http_code,status,created_at,updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $sql_insert = $mysqli->prepare($sql_insert);
    $sql_insert->bind_param("ssssssss", $ip, $method, $request, $response, $http_code, $status, $created_at, $updated_at);
    // Ejecutar la consulta
    if ($sql_insert->execute() === TRUE) {
        $id = $mysqli->insert_id;
        $rq['id'] = $id;
        $rq['ip'] = $ip;
        $rq['method'] = $method;
        $rq['request'] = $request;
        $rq['response'] = $response;
        $rq['http_code'] = $http_code;
        $rq['status'] = $status;
        $rq['created_at'] = $created_at;
        $rq['updated_at'] = $updated_at;
        return $rq;  
    } else {
        $errordb = $sql_insert->error;
        //consolelogdata($errordb);
        return false; 
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