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
function create_or_update_transaction($trans=false){
	global $unique_id;
	global $mysqli;

	$db = 'at_payments_prueba';
	$table = 'transactions';

	$insert_arr = [];
		$insert_arr['payment_method_id']=4; //1=kushki / 2=prometeo / 3=payphone
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
	//var_dump($trans);

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
function create_payment_button($client=false){
	
	
	$ret = false;
	$rq = [];
	$rq['url']='https://pay.payphonetodoesposible.com/api/button/Prepare';
	$rq['method']="POST";

	$dolar_Value_Payphone = $client['kushki_value']*100;

	$rq['rq'] = [
        "amount" => $dolar_Value_Payphone,
		"amountWithoutTax" => $dolar_Value_Payphone,
        "currency" => "USD", 
        "clientTransactionId" => $client['unique_id'],
        "email" => $client['email'],
		"responseUrl" => env('RESPONSEURL_PAYPHONE'),
    ];

	// Define el header de la solicitud para Prometeo	
	$rq['h']=[
		"Content-Type: application/json",
		'Authorization: Bearer '. env('TOKEN_PAYPHONE') 
	];
	// Imprimir el contenido de $RQ en la consola
	$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
	$peticion_curl = kushki_curl($rq);

	if (array_key_exists("curl_error", $peticion_curl)) {
        $ret['curl_error'] = $peticion_curl;
    } elseif (array_key_exists("code", $peticion_curl)) {
        $ret['curl'] = $peticion_curl;
        $ret['rq'] = $rq;
        //print_r($rq['rq']);
        exit();
    } else {
        $ret = $peticion_curl;
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
        //CURLOPT_CUSTOMREQUEST => "POST",
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

/*
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
*/
function create_or_update_bd_api_details($data=false){
	global $mysqli;
	//$data = json_decode($json_data, true);
    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        $db = 'at_payments_prueba';
		$table = 'payphone_details';

		$insert_arr = [];
		$insert_arr['amount'] = isset($data['kushki_value']) ? $data['kushki_value'] : null;
		$insert_arr['amountWithoutTax'] = isset($data['amountWithoutTax']) ? $data['amountWithoutTax'] : null;
		$insert_arr['amountWithTax'] = isset($data['amountWithTax']) ? $data['amountWithTax'] : null;
		$insert_arr['tax'] = isset($data['tax']) ? $data['tax'] : null;
		$insert_arr['service'] = isset($data['service']) ? $data['service'] : null;
		$insert_arr['tip'] = isset($data['tip']) ? $data['tip'] : null;
		$insert_arr['currency'] = isset($data['currency']) ? $data['currency'] : null;
		$insert_arr['clientTransactionId'] = isset($data['unique_id']) ? $data['unique_id'] : null;
		$insert_arr['storeId'] = isset($data['storeId']) ? $data['storeId'] : null;
		$insert_arr['reference'] = isset($data['reference']) ? $data['reference'] : null;
		$insert_arr['phoneNumber'] = isset($data['phoneNumber']) ? $data['phoneNumber'] : null;
		$insert_arr['email'] = isset($data['email']) ? $data['email'] : null;
		$insert_arr['documentId'] = isset($data['documentId']) ? $data['documentId'] : null;
		$insert_arr['paymentId'] = isset($data['paymentId']) ? $data['paymentId'] : null;
		$insert_arr['payWithPayPhone'] = isset($data['payWithPayPhone']) ? $data['payWithPayPhone'] : null;
		$insert_arr['payWithCard'] = isset($data['payWithCard']) ? $data['payWithCard'] : null;
		$insert_arr['client_id'] = isset($data['client_id']) ? $data['client_id'] : null;

		$data_to_db = data_to_db($insert_arr);
		$insert_command = "INSERT INTO {$db}.{$table} (";
		$insert_command.= implode(", \n", array_keys($insert_arr));
		$insert_command.= ") VALUES ";
		$insert_command.= "(";
		$insert_command.= implode(", \n", $data_to_db);
		$insert_command.= ")";
		$insert_command.= " ON DUPLICATE KEY UPDATE ";

		$uqn = 0;
        foreach ($data_to_db as $k => $v) {
            if ($uqn > 0) {
                $insert_command .= ", \n";
            }
            $insert_command .= "" . $k . " = VALUES(" . $k . ")";
            $uqn++;
        }
        $mysqli->query($insert_command);
        if ($mysqli->error) {
            echo $mysqli->error;
            echo "\n";
            echo $insert_command;
            echo "\n";
            exit();
        }
        return $data;
    }
}

function api_button_V2_Confirm ($data_array){
	
	$ret = false;
	$rq = [];
	$rq['url']='https://pay.payphonetodoesposible.com/api/button/V2/Confirm ';
	$rq['method']="POST";

	$rq['rq'] = [
        "id" => $data_array['id'],
		"clientTxId" => $data_array['clientTxId']
    ];

	// Define el header de la solicitud para Prometeo	
	$rq['h']=[
		"Content-Type: application/json",
		'Authorization: Bearer '. env('TOKEN_PAYPHONE') 
	];
	// Imprimir el contenido de $RQ en la consola
	$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
	$peticion_curl = kushki_curl($rq);

	if (array_key_exists("curl_error", $peticion_curl)) {
        $ret['curl_error'] = $peticion_curl;
    } elseif (array_key_exists("code", $peticion_curl)) {
        $ret['curl'] = $peticion_curl;
        $ret['rq'] = $rq;
        //print_r($rq['rq']);
        exit();
    } else {
        $ret = $peticion_curl;
    }	
    return $ret;
}

function create_or_update_bd_api_transactions($data=false){
	global $mysqli;

	if ($data !== null) {
		$db = 'at_payments_prueba';
		$table = 'payphone_transactions';

		$insert_arr = [];
        $insert_arr['amount'] = isset($data['kushki_value']) ? $data['kushki_value'] : null;
        $insert_arr['amountWithoutTax'] = isset($data['amountWithoutTax']) ? $data['amountWithoutTax'] : null;
        $insert_arr['amountWithTax'] = isset($data['amountWithTax']) ? $data['amountWithTax'] : null;
        $insert_arr['tax'] = isset($data['tax']) ? $data['tax'] : null;
        $insert_arr['service'] = isset($data['service']) ? $data['service'] : null;
        $insert_arr['tip'] = isset($data['tip']) ? $data['tip'] : null;
        $insert_arr['currency'] = isset($data['currency']) ? $data['currency'] : null;
        $insert_arr['clientTransactionId'] = isset($data['clientTxId']) ? $data['clientTxId'] : null;
        $insert_arr['storeId'] = isset($data['storeId']) ? $data['storeId'] : null;
        $insert_arr['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $insert_arr['phoneNumber'] = isset($data['phoneNumber']) ? $data['phoneNumber'] : null;
        $insert_arr['email'] = isset($data['email']) ? $data['email'] : null;
        $insert_arr['document'] = isset($data['documentId']) ? $data['documentId'] : null;
        $insert_arr['paymentId'] = isset($data['paymentId']) ? $data['paymentId'] : null;
        $insert_arr['payWithPayPhone'] = isset($data['payWithPayPhone']) ? $data['payWithPayPhone'] : null;
        $insert_arr['payWithCard'] = isset($data['payWithCard']) ? $data['payWithCard'] : null;
        $insert_arr['client_id'] = isset($data['client_id']) ? $data['client_id'] : null;

		$data_to_db = data_to_db($insert_arr); // Asegúrate de que esta función esté definida.
        $insert_command = "INSERT INTO {$db}.{$table} (";
        $insert_command .= implode(", \n", array_keys($insert_arr));
        $insert_command .= ") VALUES ";
        $insert_command .= "(";
        $insert_command .= implode(", \n", $data_to_db);
        $insert_command .= ")";
        $insert_command .= " ON DUPLICATE KEY UPDATE ";

		$uqn = 0;
		foreach ($data_to_db as $k => $v) {
			if ($uqn > 0) { $insert_command .= ", \n"; }
			$insert_command .= "".$k." = VALUES(".$k.")";
			$uqn++;
		}
		$mysqli->query($insert_command);
		if ($mysqli->error) {
			echo $mysqli->error;
			echo "\n";
			echo $insert_command;
			echo "\n";
			exit();
		}
		return $data; // Debes devolver $data en lugar de $trans
	}
}

?>