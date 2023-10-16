<?php
include '../prometeo/env.php';
include '../prometeo/db.php';
include '../prometeo/sys/helpers.php';
include '../sys/helpers.php';
include '../prometeo/api/KushkiController.php';
//include '/api/KushkiController.php';

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud como JSON
    $payload = file_get_contents("php://input");
    // Verificar si el cuerpo de la solicitud es JSON válido
    $data = json_decode($payload, true);
    

    //$verifyToken = $data['verify_token'];
    if (json_last_error() === JSON_ERROR_NONE) {
        if(isset($data)) {

            
            $data_array = [];
            $data_array['verify_token'] = isset($data['verify_token']) ? $data['verify_token'] : null;

            $events = $data['events'][0];
            $data_array['event_type'] = isset($events['event_type']) ? $events['event_type'] : null;
            $data_array['event_id'] = isset($events['event_id']) ? $events['event_id'] : null;
            $data_array['timestamp'] = isset($events['timestamp']) ? $events['timestamp'] : null; 

            $payload = isset($events['payload']) ? $events['payload'] : null;
            $data_array['amount'] = isset($payload['amount']) ? $payload['amount'] : null;
            $data_array['concept'] = isset($payload['concept']) ? $payload['concept'] : null;
            $data_array['currency'] = isset($payload['currency']) ? $payload['currency'] : null;
            $data_array['origin_account'] = isset($payload['origin_account']) ? $payload['origin_account'] : null;
            $data_array['destination_account'] = isset($payload['destination_account']) ? $payload['destination_account'] : null;
            $data_array['destination_institution'] = isset($payload['destination_institution']) ? $payload['destination_institution'] : null;
            $data_array['branch'] = isset($payload['branch']) ? $payload['branch'] : null;
            $data_array['destination_owner_name'] = isset($payload['destination_owner_name']) ? $payload['destination_owner_name'] : null; 
            $data_array['destination_account_type'] = isset($payload['destination_account_type']) ? $payload['destination_account_type'] : null;
            $data_array['document_type'] = isset($payload['document_type']) ? $payload['document_type'] : null;
            $data_array['document_number'] = isset($payload['document_number']) ? $payload['document_number'] : null;
            $data_array['destination_bank_code'] = isset($payload['destination_bank_code']) ? $payload['destination_bank_code'] : null;
            $data_array['mobile_os'] = isset($payload['mobile_os']) ? $payload['mobile_os'] : null;
            $data_array['request_id'] = isset($payload['request_id']) ? $payload['request_id'] : null;
            $data_array['intent_id'] = isset($payload['intent_id']) ? $payload['intent_id'] : null;
            $data_array['external_id'] = isset($payload['external_id']) ? $payload['external_id'] : consultaintent($intent_id);

            // obtener el unique_id de la transaccion
	        $trans = kushki_get_transaction(['unique_id'=>$data_array['external_id']]);

            //$data_array['id_usuario'] = $trans['client_id'];
            $data_array['id_usuario'] = $trans['client_id'];


            insert_bd($mysqli, $data_array);

            

            
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
            log_write($data);
            // declarar actividad y retorno
            $a=[];
            $ret=[];

            // declarar respuestas en caso de error
            $http_code = 500;
            $status = 'Error';
            $response = [];  
            
            //verificacion de datos...
            //verificacion_datos($data_array);              
                                    
            

            // obtener el unique_id de la transaccion
	        //$trans = kushki_get_transaction(['unique_id'=>$data_array['external_id']]);
            if($data_array['event_type']==="payment.success"){
                // declarar el update
                $new_trans=[];
                $new_trans['unique_id']=$data_array['external_id'];
                $new_trans['status']=9; // 3=pending deposit
                $new_trans['payment_id']=$data_array['event_id'];

                // ejecutar el update
                kushki_create_or_update_transaction($new_trans);
            }

        
            
            /////////////////// FIN CODIGO //////////////////////////////
            
            http_response_code(200);
            //echo json_encode(["message" => "Registro exitoso"]);
        } else {
            // No se recibieron datos válidos en la solicitud
            http_response_code(400); // Código 400 para solicitud incorrecta
            //echo json_encode(["message" => "No se recibieron datos válidos en la solicitud"]);
        }
    } else {
        // La solicitud no contenía JSON válido
        http_response_code(400); // Código 400 para solicitud incorrecta
        //echo json_encode(["message" => "El cuerpo de la solicitud no es JSON válido"]);
    }

} else {
    // La solicitud no contenía JSON válido
    http_response_code(400); // Código 400 para solicitud incorrecta
    //echo json_encode(["message" => "El cuerpo REQUEST_METHOD no es POST"]);
}
/*
else{
	// retornar error al no json
	$ret['http_code']=400;
	$ret['status']='Error';
	$ret['response']='no json';

	api_ret($ret);
}
*/

function api_ret($r){
	global $a;
	http_response_code($r['http_code']);
	api_activities(array_merge($r,$a));
	echo json_encode($r);
	log_write(array_merge($r,$a));
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


function consultId($externalId, $mysqli) {
     // Valor predeterminado en caso de que no se encuentre el registro
     $idSel = "";
     $sqlDetails = "SELECT client_id FROM transactions WHERE unique_id = ?";
     $stmtDetails = $mysqli->prepare($sqlDetails);
 
     if ($stmtDetails) {
         $stmtDetails->bind_param("s", $externalId);
         $stmtDetails->execute();
         $resultDetails = $stmtDetails->get_result();
 
         if ($resultDetails->num_rows > 0) {
             $rowTransactions = $resultDetails->fetch_assoc();
             $idSel = $rowTransactions["client_id"];
         }
         
         $stmtDetails->close();
     }
     
     return $idSel;
}

function insert_bd($mysqli, $data_array) {
    // Crear la sentencia SQL preparada

    $sqlDetails = " INSERT INTO prometeo_transactions (id_usuario, verify_token, event_type, event_id,
                    timestamp, amount, concept, currency, origin_account, destination_account,
                    destination_institution, branch, destination_owner_name, destination_account_type,
                    document_type, document_number, destination_bank_code, mobile_os, request_id,
                    intent_id, external_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtDetails = $mysqli->prepare($sqlDetails);
    if ($stmtDetails) {
        $stmtDetails->bind_param("sssssssssssssssssssss", $data_array['id_usuario'], $data_array['verify_token'],
                                                            $data_array['event_type'], $data_array['event_id'],
                                                            $data_array['timestamp'], $data_array['amount'],
                                                            $data_array['concept'], $data_array['currency'],
                                                            $data_array['origin_account'], $data_array['destination_account'],
                                                            $data_array['destination_institution'], $data_array['branch'],
                                                            $data_array['destination_owner_name'], $data_array['destination_account_type'],
                                                            $data_array['document_type'], $data_array['document_number'],
                                                            $data_array['destination_bank_code'], $data_array['mobile_os'],
                                                            $data_array['request_id'], $data_array['intent_id'],
                                                            $data_array['external_id']);
        $stmtDetails->execute();
        $stmtDetails->close();
    }
}


function consultaintent($intent_id) {

    $url = 'https://payment.prometeoapi.net/api/v1/payment-intent/'.$intent_id;
    $rq = [];
    $rq['url']=$url;
    $rq['method']="GET";
    $rq['h']=[
        "Content-Type: application/json",
        "X-API-Key: " . env('API_KEY_PROMETEO') // Ajusta la clave de API correcta
    ];

    $curl = curl_init();
    $curl_options = [
        CURLOPT_URL => $rq['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => (array_key_exists('timeout', $rq) ? $rq['timeout'] : 30),
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $rq['method'],
        CURLOPT_HTTPHEADER => $rq['h'],
    ];
    curl_setopt_array($curl, $curl_options);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
		$response_arr = ['curl_error'=>curl_error($curl)];
	}else{
		$response_arr = json_decode($result, true);
	}
	curl_close($curl);

    // Verificar si 'external_id' existe en la respuesta antes de acceder a él
    if (isset($response_arr['external_id'])) {
        $external_id = $response_arr['external_id'];
    }
    return $external_id;
  
}

function verificacion_datos($data_array){

    $required_keys = [
        'verify_token',
        'event_type',
        'timestamp',
        'payload',
        'amount',
        'concept',
        'currency',
        'origin_account',
        'destination_account',
        'destination_institution',
        'branch',
        'destination_owner_name',
        'destination_account_type',
        'document_type',
        'document_number',
        'destination_bank_code',
        'mobile_os',
        'request_id',
        'intent_id',
        'external_id'
    ];
    
    foreach ($required_keys as $key) {
        if (!array_key_exists($key, $data_array)) {
            $ret['http_code'] = 400;
            $ret['status'] = 'Error';
            $ret['response'] = "Missing $key";
            api_ret($ret);
            // Puedes decidir si quieres salir del bucle después del primer error o continuar verificando todas las claves.
        }
    }
    
}

?>