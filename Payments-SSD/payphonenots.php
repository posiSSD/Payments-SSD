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
log_write('json');

try {
    
    //$payphone_array_response = dataconstruccion ($data_array);
    //comprobacion si la tranx existe para evitar duplicados 
    $status_payphone_transactions = payphone_status_transaction($data_array);
    consolelogdata($status_payphone_transactions); 
    
    if (!$status_payphone_transactions){

        // obtener detalles de la tx en la api de payphone
        $payphone_array_response = payphone_api_confirm ($data_array);
        consolelogdata($payphone_array_response);

        if($payphone_array_response){

            // guardar en la BD
            payphone_api_transactions($payphone_array_response);

            // obtener client_id, unique_id, amount,  
            $data_array_response_details = payphone_bd_details($payphone_array_response);
           

            $payphone_array_response['client_id'] = $data_array_response_details['client_id'];
            $payphone_array_response['Response'] = "True";
            $payphone_array_response['Time'] = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
            log_write($payphone_array_response);

            switch ($payphone_array_response['transactionStatus']){

                case "Approved":
                    
                    $new_trans=[];
                    $new_trans['unique_id']=$data_array_response_details['unique_id'];
                    $new_trans['client_id']=$data_array_response_details['client_id'];
                    $new_trans['status']=9; // 3=pending deposit
                    $new_trans['order_id']=$payphone_array_response['transactionId'];
                    $new_trans['payment_id']=$payphone_array_response['transactionId'];
                    create_or_update_transaction($new_trans);
                    sleep(5);
                    // llamar BC
                    $d=[];
                    $d['account']=$data_array_response_details['client_id'];
                    $d['amount']=$data_array_response_details['amount'];
                    $d['order_id']=$payphone_array_response['transactionId'];
                    $d['payment_method']='payphone'; // 4 = payphone

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
                            $ret['http_code'] = 200;
                            $ret['status'] = 'Ok';
                            $ret['response'] = 'Order '.$id.' paid';
                            // $id = order_id
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
                            $ret['response']='Order '.$id.' wrong / check logs';
                            // $id = order_id
                            //$ret['try'] = 'Try :'.$limit_try;
                            api_ret($ret);
                        }
                    }
    

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
                    $ret['response']='Order '.$id.' Canceled';
                    // $id = order_id
                    api_ret($ret);
                    exit();   
                break;    
            }
        } else {
            
            $data_array['Response'] = "Datos Nulos";
            $data_array['Time'] = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
            log_write('json');
            log_write($data_array);
            exit();

        }  
    } else {
        $data_array['Response'] = "Duplicity";
        $data_array['Time'] = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
        log_write('json');
        log_write($data_array);
        exit();

    }
    
} catch (Exception $e) {
    // Captura cualquier excepción y muestra el mensaje de error en la consola del navegador
    $error_message = $e->getMessage();
    echo "<script>console.error('Error: $error_message');</script>";
}

function api_ret($r){
    log_write($r);
	api_activities($r);
}

// registrar la actividad 
function api_activities($a){
    global $mysqli;
    $bd =  'tb_payment';
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
    $result = $sql_insert->execute();
    // Cerrar la conexión
    $mysqli->close();

    if ($result === TRUE) {
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

?>
