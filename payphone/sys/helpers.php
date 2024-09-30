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
function kushki_get_transaction($trans=false){
	$ret = false;
	global $mysqli;

	$db = 'tb_payment';
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
	$db = 'tb_payment';
	$table = 'transactions';

	$insert_arr = [];
		$insert_arr['payment_method_id']=4; //1=kushki / 2=prometeo / 4=payphone
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
function create_payment_button($client=false){
	$ret = false;
	$rq = [];
	$rq['url']='https://pay.payphonetodoesposible.com/api/button/Prepare';
	$rq['method']="POST";

	$dolar_Value_Payphone = $client['kushki_value']*100;  //en la doc de payphone 1 dolar = 100//

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

function payphone_api_confirm ($data_array){
	
	$ret = false;
	$rq = [];
	$rq['url']='https://pay.payphonetodoesposible.com/api/button/V2/Confirm';
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

	consolelogdata($rq);	

	$peticion_curl = kushki_curl($rq);

	consolelogdata($peticion_curl);

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

	consolelogdata($ret);	

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

	consolelogdata($result);	

    if (curl_errno($curl)) {
        $response_arr = ['curl_error' => curl_error($curl)];
    } else {
        $response_arr = json_decode($result, true);
    }
    curl_close($curl);
    return $response_arr;
}
function create_or_update_bd_api_details($data=false){
	global $mysqli;
	//$data = json_decode($json_data, true);
    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        $db = 'tb_payment';
		$table = 'payphone_details';

		$insert_arr = [];
		$insert_arr['amount'] = isset($data['kushki_value']) ? $data['kushki_value'] : null;
		$insert_arr['amountWithoutTax'] = isset($data['kushki_value']) ? $data['kushki_value']: null;
		$insert_arr['amountWithTax'] = isset($data['kushki_value']) ? $data['kushki_value'] : null;
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
		$insert_arr['unique_id'] = isset($data['unique_id']) ? $data['unique_id'] : null;

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
function payphone_api_transactions($data=false){
	global $mysqli;

	if ($data !== null) {
		$db = 'tb_payment';
		$table = 'payphone_transactions';

		$insert_arr = [];
        $insert_arr['statusCode'] = isset($data['statusCode']) ? $data['statusCode'] : null;   ////////////////////////////////////
        $insert_arr['transactionStatus'] = isset($data['transactionStatus']) ? $data['transactionStatus'] : null;   ////////////////////////////////////
        $insert_arr['clientTransactionId'] = isset($data['clientTransactionId']) ? $data['clientTransactionId'] : null;  ////////////////////////////////////
        $insert_arr['authorizationCode'] = isset($data['authorizationCode']) ? $data['authorizationCode'] : null;    ////////////////////////////////////
        $insert_arr['transactionId'] = isset($data['transactionId']) ? $data['transactionId'] : null;   ////////////////////////////////////
        $insert_arr['email'] = isset($data['email']) ? $data['email'] : null; ////////////////////////////////////
        $insert_arr['currency'] = isset($data['currency']) ? $data['currency'] : null;   ////////////////////////////////////
        $insert_arr['phoneNumber'] = isset($data['phoneNumber']) ? $data['phoneNumber'] : null;  ////////////////////////////////////
        $insert_arr['document'] = isset($data['document']) ? $data['document'] : null;   ////////////////////////////////////
        $insert_arr['amount'] = isset($data['amount']) ? (floatval($data['amount'])/100) : null;
        $insert_arr['cardType'] = isset($data['cardType']) ? $data['cardType'] : null; ////////////////////////////////////
        $insert_arr['cardBrandCode'] = isset($data['cardBrandCode']) ? $data['cardBrandCode'] : null;   ////////////////////////////////////
        $insert_arr['cardBrand'] = isset($data['cardBrand']) ? $data['cardBrand'] : null;
        $insert_arr['bin'] = isset($data['bin']) ? $data['bin'] : null;  ////////////////////////////////////
        $insert_arr['lastDigits'] = isset($data['lastDigits']) ? $data['lastDigits'] : null;   ////////////////////////////////////
        $insert_arr['deferredCode'] = isset($data['deferredCode']) ? $data['deferredCode'] : null;   ////////////////////////////////////
        $insert_arr['deferredMessage'] = isset($data['deferredMessage']) ? $data['deferredMessage'] : "No Response Api";
		$insert_arr['deferred'] = isset($data['deferred']) ? $data['deferred'] : "No Response Api";  ////////////////////////////////////
		$insert_arr['message'] = isset($data['message']) ? $data['message'] : "No Response Api";
		$insert_arr['messageCode'] = isset($data['messageCode']) ? $data['messageCode'] : "No Response Api";   ////////////////////////////////////
		$insert_arr['optionalParameter1'] = isset($data['optionalParameter1']) ? $data['optionalParameter1'] : null;
		
		$fecha_hora_actual = new DateTime('now', new DateTimeZone('America/Lima'));
		$insert_arr['created_at'] = $fecha_hora_actual->format('Y-m-d H:i:s');

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
		return $insert_arr;
		//return $data; 
	}
}
function payphone_bd_details($trans=false){
	// $data=false
	$ret = false;
	global $mysqli;

	$db = 'tb_payment';
	$table = 'transactions';
	$where = ' id > 0 ';
	if(array_key_exists('clientTransactionId', $trans)){
		$where.= " AND unique_id = '".$trans['clientTransactionId']."'";
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
	$trans_ret['client_id'] = $ret['client_id'];
	$trans_ret['unique_id'] = $ret['unique_id'];
	$trans_ret['payment_method_id'] = $ret['payment_method_id'];
	$trans_ret['amount'] = $ret['amount'];
	
	return $trans_ret;
}
function status_transaction($trans=false){
	// $data=false
	$ret = false;
	global $mysqli;
	
	//error_log("status_transaction - \$trans: " . print_r($trans, true));

	$db = 'tb_payment';
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
function payphone_status_transaction($trans = false) {
    global $mysqli;

    $trans_ret = false;
    $db = 'tb_payment';
    $table = 'payphone_transactions';
    $where = ' 1=1 '; // Cambiado para que siempre sea verdadero
	
    if (array_key_exists('clientTxId', $trans)) {
        $where .= " AND clientTransactionId = '" . $trans['clientTxId'] . "'";
    }
    if (array_key_exists('id', $trans)) {
        $where .= " AND transactionId = '" . $trans['id'] . "'";
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

?>