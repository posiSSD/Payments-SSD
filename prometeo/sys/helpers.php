<?php
function data_to_db($d){
	global $mysqli;
	$tmp=[];
	$nulls=["null","",false,"false"];
	foreach ($d as $k => $v) {
		if(array_search($v, $nulls)){
			$tmp[$k]="NULL";
		}else{
			if(is_float($v)){
				$tmp[$k]="'".$v."'";
			}elseif(is_int($v)){
				$tmp[$k]=$v;
			}elseif(in_array($v, ["NOW()"])){
				$tmp[$k]=$v;
			}else{
				if(is_string($v)){
					$tmp[$k]="'".trim($mysqli->real_escape_string($v))."'";
				} else {
					print_r($k);
					echo "\n\n";
					print_r($v);
					exit();
				}
			}
		}
	}
	return $tmp;
}
/*function bc_deposit($d=false){
	$ret = false;
	$rq = [];
		$rq['url']='https://api.apuestatotal.com/v2/kushki/deposit';
		
		$rq['rq']=[];
			$rq['rq']['account']=$d['account'];
			$rq['rq']['amount']=$d['amount'];
			$rq['rq']['order_id']=$d['order_id'];
		// $rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
		$rq['h']=[];
			// $rq['h'][] = "Content-Type: application/json";
			$rq['h'][] = "Authorization: Bearer " . env('API_V2_TOKEN');
			// print_r($rq); 
	$kushki_curl = kushki_curl($rq);
	// print_r($rq); exit();

	if(array_key_exists("http_code", $kushki_curl)){
		$ret = $kushki_curl;
	}else{
		$ret['curl']=$kushki_curl;
		$ret['rq']=$rq;
		print_r($rq['rq']); exit();
	}
	return $ret;
}*/
function kushki_get_transaction($trans=false){
	$ret = false;
	global $mysqli;

	$db = 'at_payments_prueba';
	$table = 'transactions';
	$where = ' id > 0 ';
	
	if(array_key_exists('client_id', $trans)){
		$where.= " AND client_id = '".$trans['client_id']."'";
	}
	if(array_key_exists('unique_id', $trans)){
		$where.= " AND unique_id = '".$trans['unique_id']."'";
	}
	if(array_key_exists('order_id', $trans)){
		$where.= " AND order_id = '".$trans['order_id']."'";
	}
	if(array_key_exists('payment_id', $trans)){
		$where.= " AND payment_id = '".$trans['payment_id']."'";
	}

	$get_command = "SELECT * FROM {$db}.{$table} WHERE {$where}";
	$query = $mysqli->query($get_command);

	$mysqli->query($get_command);
	if($mysqli->error){
		echo $mysqli->error;
		echo "\n";
		echo $get_command;
		echo "\n";
		exit();
	}
	$ret = $query->fetch_assoc();

	return $ret;
}
function create_or_update_transaction($trans=false){
	global $unique_id;
	global $mysqli;

	$db = 'at_payments_prueba';
	$table = 'transactions';

	$insert_arr = [];
		$insert_arr['payment_method_id']=2; //1=kushki
		$insert_arr['type_id']=3; //2=web_deposit

		if(isset($trans['unique_id'])){
			$insert_arr['unique_id']=$trans['unique_id'];
		}
		//$insert_arr['unique_id']=(isset($trans['unique_id'])?$trans['unique_id']:$unique_id);
		if(isset($trans['client_id'])){
			$insert_arr['client_id']=$trans['client_id'];
		}
		if(isset($trans['order_id'])){
			$insert_arr['order_id']=$trans['order_id'];
		}
		if(isset($trans['payment_id'])){
			$insert_arr['payment_id']=$trans['payment_id'];
		}
		if(isset($trans['payment_method_id'])){
			$insert_arr['payment_method_id']=$trans['payment_method_id'];
		}
		if(isset($trans['kushki_value'])){
			$insert_arr['amount']=$trans['kushki_value'];
		}
		//$insert_arr['status']=6;
		if(isset($trans['status'])){
			$insert_arr['status']=$trans['status'];
		}
		if(isset($trans['wallet_id'])){
			$insert_arr['wallet_id']=$trans['wallet_id'];
		}


	$data_to_db = data_to_db($insert_arr);
	$insert_command = "INSERT INTO {$db}.{$table} (";
	$insert_command.= implode(", \n", array_keys($insert_arr));
	$insert_command.= ") VALUES ";
	$insert_command.= "(";
	$insert_command.= implode(", \n", $data_to_db);
	$insert_command.= ")";
	$insert_command.= " ON DUPLICATE KEY UPDATE ";

	$uqn=0;
	foreach ($data_to_db as $k => $v) {
		if($uqn>0) { $insert_command.=", \n"; }
		$insert_command.= "".$k." = VALUES(".$k.")";
		$uqn++;
	}
	$mysqli->query($insert_command);
	if($mysqli->error){
		echo $mysqli->error;
		echo "\n";
		echo $insert_command;
		echo "\n";
		exit();
	}
	return $trans;
}
function kushki_create_payment_button($client=false){
	
	$expires_at = generateexpires_at();
	$ret = false;
	$rq = [];
	$rq['url']='https://payment.prometeoapi.net/api/v1/payment-link/';
	$rq['method']="POST";
	// Define los datos de la solicitud para Prometeo
    $rq['rq'] = [
        "product_id" => env('WIDGET_PROMTEO'),
        "external_id" => $client['unique_id'],
        "concept" => "Recarga Prometeo",
        "currency" => "USD",
        "amount" => $client['kushki_value'], // Utiliza el balance de usr_active
        "expires_at" => $expires_at,
        "email" => $client['email'], // Utiliza el email de usr_active
        "reusable" => false
    ];	
	// Define el header de la solicitud para Prometeo	
	$rq['h']=[
		"Content-Type: application/json",
		"X-API-Key: " . env('API_KEY_PROMETEO') // Ajusta la clave de API correcta
	];
	// Imprimir el contenido de $RQ en la consola
	$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
	$kushki_curl = kushki_curl($rq);
	
	//imprimir y guardar en la bd
	//bd_save_prometeo($kushki_curl);
	
	//obtener detalles de pago
	//$update_kushi = details_payment_link($kushki_curl);

	//update en bd
	//bd_update_prometeo($update_kushi);

	if (array_key_exists("curl_error", $kushki_curl)) {
        $ret['curl_error'] = $kushki_curl;
    } elseif (array_key_exists("code", $kushki_curl)) {
        $ret['curl'] = $kushki_curl;
        $ret['rq'] = $rq;
        //print_r($rq['rq']);
        exit();
    } else {
        $ret = $kushki_curl;
    }	
    return $ret;
}
function kushki_curl($rq = false) {
	
    $curl = curl_init();
    $curl_options = [
        CURLOPT_URL => $rq['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => (array_key_exists('timeout', $rq) ? $rq['timeout'] : 30),
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => $rq['h'],
    ];

    // Inicio Verificar si contiene un body o si es una peticion POST O GET
    if (!empty($rq['rq'])) {
        $curl_options[CURLOPT_POSTFIELDS] = $rq['rq'];
    }
	if ($rq['method']  == "POST") {  //linea 2010
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
    }else{
		$curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
	}
	// Fin Verificar si contiene un body o si es una peticion POST O GET
    curl_setopt_array($curl, $curl_options);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        $response_arr = ['curl_error' => curl_error($curl)];
    } else {
        $response_arr = json_decode($result, true);
    }
    curl_close($curl);
    return $response_arr;
}


function details_payment_link($kushki_curl) {

	$ret = false;
	$rk = [];
	$rk['url']='https://payment.prometeoapi.net/api/v1/payment-link/'.$kushki_curl['id'];
	$rk['h']=[
		"Content-Type: application/json",
		'Accept: application/json',
		"X-API-Key: " . env('API_KEY_PROMETEO') // Ajusta la clave de API correcta
	];
	// Imprimir el contenido de $RQ en la consola
	$data_curl = kushki_curl($rk);
	return $data_curl; 
  }

function bd_save_prometeo($data){
	global $mysqli;
	//$data = json_decode($json_data, true);
    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        $expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $id = isset($data['id']) ? $data['id'] : null;
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : null;
        $link = isset($data['url']) ? $data['url'] : null;

        // Utiliza sentencias preparadas para evitar inyección SQL
        $sql = "INSERT INTO prometeo_details (expires_at, id, qr_code, url)
                VALUES ('$expires_at', '$id', '$qr_code', '$link')";

        // Ejecutar la consulta
        if ($mysqli->query($sql) === TRUE) {
           
        } else {
			
        }

    }
}
function bd_update_prometeo($data){
	global $mysqli;
	if ($data !== null) {
       
        $expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : null;
        $link = isset($data['url']) ? $data['url'] : null;

        $callback_url = isset($data['callback_url']) ? $data['callback_url'] : null;
        $created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        $amount = isset($data['payment_data']['amount']) ? $data['payment_data']['amount'] : null;
        $concept = isset($data['payment_data']['concept']) ? $data['payment_data']['concept'] : null;
        $currency = isset($data['payment_data']['currency']) ? $data['payment_data']['currency'] : null;
        $external_id = isset($data['payment_data']['external_id']) ? $data['payment_data']['external_id'] : null;
        $intent_id = isset($data['payment_data']['intent_id']) ? $data['payment_data']['intent_id'] : null;

        $payment_link_type = isset($data['payment_link_type']) ? $data['payment_link_type'] : null;
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $return_url = isset($data['return_url']) ? $data['return_url'] : null;
        $reusable = isset($data['reusable']) ? $data['reusable'] : null;
        $status = isset($data['status']) ? $data['status'] : null;
        $id = isset($data['id']) ? $data['id'] : null;

        // Utiliza sentencias preparadas para evitar inyección SQL
        $sql = "UPDATE prometeo_details SET
                expires_at = '$expires_at',
                qr_code = '$qr_code',
                url = '$link',

                callback_url = '$callback_url',
                created_at = '$created_at',
                email = '$email',

                amount = '$amount',
                concept = '$concept',
                currency = '$currency',
                external_id = '$external_id',
                intent_id = '$intent_id',

                payment_link_type = '$payment_link_type',
                product_id = '$product_id',
                return_url = '$return_url',
                reusable = '$reusable',
                status = '$status'
                WHERE id = '$id'";

        // Ejecutar la consulta de actualización
        if ($mysqli->query($sql) === TRUE) {
        } else {
        }
    }  
}

/*
function prometeo_select_bd($trans=false){
	global $mysqli;
	if(isset($trans)){

		$sql = "SELECT external_id from prometeo_details
				WHERE client_id = '$trans['client_id']' && order_id = '$trans['order_id']'"

        
        $sql = "SELECT event_type FROM prometeo_transactions
                WHERE external_id='$external_id'
                ORDER BY id DESC
                LIMIT 1";

        $result = $mysqli->query($sql); // Ejecutar la consulta

        if ($result->num_rows > 0) {
            // Si se encontraron resultados, obtén el valor resultante y devuélvelo
            $row = $result->fetch_assoc();
			$trans["event_type"] = $row["event_type"];
            return $trans;
        } else {
            return "No se encontraron resultados.";
        }
    }
}
*/

function prometeo_select_bd($trans = false) {
	global $mysqli;

	if (!isset($trans) || !isset($trans['client_id']) || !isset($trans['order_id'])) {
		return ["success" => false, "error" => "Faltan claves necesarias en el arreglo."];
	}

	$client_id = $trans['client_id'];
	$order_id = $trans['order_id'];

	// Consulta la tabla prometeo_details para obtener el external_id
	$sql_details = "SELECT unique_id FROM transactions
				   WHERE client_id = ? AND order_id = ?
				   order by id DESC LIMIT 1";
	$stmt_details = $mysqli->prepare($sql_details);
	$stmt_details->bind_param("ss", $client_id, $order_id);
	$stmt_details->execute();
	$result_details = $stmt_details->get_result();

	if ($result_details->num_rows > 0) {
		$row_details = $result_details->fetch_assoc();
		$external_id = $row_details["unique_id"];
		$trans["unique_id"] = $external_id;
		// Consulta la tabla prometeo_transactions usando el external_id
		$sql_transactions = "SELECT event_type FROM prometeo_transactions
							 WHERE external_id = ?
							 ORDER BY id DESC LIMIT 1";
		$stmt_transactions = $mysqli->prepare($sql_transactions);
		$stmt_transactions->bind_param("s", $external_id);
		$stmt_transactions->execute();
		$result_transactions = $stmt_transactions->get_result();

		if ($result_transactions->num_rows > 0) {
			$row_transactions = $result_transactions->fetch_assoc();
			$trans["event_type"] = $row_transactions["event_type"];
			return $trans;
		} else {
			$trans["event_type"] = "payment.falla";
			return $trans;
		}
	} else {
		return "No se encontraron resultados en prometeo_details.";
	}
}

function prometeo_bd_details($trans=false){
	// $data=false
	$ret = false;
	global $mysqli;

	$db = 'at_payments_prueba';
	$table = 'transactions';
	$where = ' id > 0 ';
	if(array_key_exists('external_id', $trans)){
		$where.= " AND unique_id = '".$trans['external_id']."'";
	}
	if(array_key_exists('order_id', $trans)){
		$where.= " AND order_id = '".$trans['order_id']."'";
	}
	if(array_key_exists('payment_id', $trans)){
		$where.= " AND payment_id = '".$trans['payment_id']."'";
	}

	$get_command = "SELECT * FROM {$db}.{$table} WHERE {$where}";
	$query = $mysqli->query($get_command);

	$mysqli->query($get_command);
	if($mysqli->error){
		echo $mysqli->error;
		echo "\n";
		echo $get_command;
		echo "\n";

		/*
		consolelogdata(array(
			'error' => $mysqli->error,
			'get_command' => $get_command,
		));
		*/

		exit();
	}
	$ret = $query->fetch_assoc();
	$trans_ret['client_id'] = $ret['client_id'];
	$trans_ret['unique_id'] = $ret['unique_id'];
	$trans_ret['payment_method_id'] = $ret['payment_method_id'];
	$trans_ret['amount'] = $ret['amount'];
	
	return $trans_ret;
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

function pprometeo_api_transactions($mysqli, $data_array) {
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

function dataconstruccion($data) {

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
    $data_array['external_id'] = isset($payload['external_id']) ? $payload['external_id'] : consultaintent($data_array['intent_id']);

    $trans = kushki_get_transaction(['unique_id'=>$payphone_array_response['external_id']]);
    $data_array['id_usuario'] = $trans['client_id'];
                    

    consolelogdata($data_array);

    return $data_array;
    
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
        consolelogdata($response_arr);
	}
	curl_close($curl);

    // Verificar si 'external_id' existe en la respuesta antes de acceder a él
    if (isset($response_arr['external_id'])) {
        $external_id = $response_arr['external_id'];
    }
    return $external_id;
  
}
function status_transaction($trans=false){
	// $data=false
	$ret = false;
	global $mysqli;
	
	//error_log("status_transaction - \$trans: " . print_r($trans, true));

	$db = 'at_payments_prueba';
	$table = 'transactions';
	$where = ' id > 0 ';
	if(array_key_exists('unique_id', $trans)){
		$where.= " AND unique_id = '".$trans['unique_id']."'";
	}
	if(array_key_exists('client_id', $trans)){
		$where.= " AND client_id = '".$trans['client_id']."'";
	}
	if(array_key_exists('order_id', $trans)){
		$where.= " AND order_id = '".$trans['order_id']."'";
	}
	/*
	if(array_key_exists('status', $trans)){
		$where.= " AND status = '".$trans['status']."'";
	}
	*/
	$get_command = "SELECT * FROM {$db}.{$table} WHERE {$where}";
	$query = $mysqli->query($get_command);

	$mysqli->query($get_command);
	if($mysqli->error){
		echo $mysqli->error;
		echo "\n";
		echo $get_command;
		echo "\n";
		exit();
	}
	$ret = $query->fetch_assoc();

	return $ret;
}

function prometeo_status_transaction($trans = false){
    global $mysqli;

    $trans_ret = false;
    $db = 'at_payments_prueba';
    $table = 'prometeo_transactions';
    $where = ' 1=1 '; // Cambiado para que siempre sea verdadero
	
    if (array_key_exists('external_id', $trans)) {
        $where .= " AND external_id = '" . $trans['external_id'] . "'";
    }
    if (array_key_exists('intent_id', $trans)) {
        $where .= " AND intent_id = '" . $trans['intent_id'] . "'";
    }
    if (array_key_exists('id_usuario', $trans)) {
        $where .= " AND id_usuario = '" . $trans['id_usuario'] . "'";
    }

    $get_command = "SELECT COUNT(*) as count FROM {$db}.{$table} WHERE {$where}";
    $result = $mysqli->query($get_command);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
		if($row['count'] > 0){
			$trans_ret = true;
		}
    }

    return $trans_ret;
}
function generateexpires_at() {
    $currentTimestamp = time();
    $expiresAtTimestamp = $currentTimestamp + 300;
    $expiresAtISO8601 = date('Y-m-d\TH:i:s.v\Z', $expiresAtTimestamp);
    return $expiresAtISO8601;
}
?>