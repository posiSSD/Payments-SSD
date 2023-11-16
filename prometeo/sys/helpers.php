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
function kushki_create_or_update_transaction($trans=false){
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

function generateexpires_at() {
    $currentTimestamp = time();
    $expiresAtTimestamp = $currentTimestamp + 300;
    $expiresAtISO8601 = date('Y-m-d\TH:i:s.v\Z', $expiresAtTimestamp);
    return $expiresAtISO8601;
}
?>