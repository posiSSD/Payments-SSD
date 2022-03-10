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
function bc_deposit($d=false){
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
			$rq['h'][] = "Authorization: Bearer " . env('MLLAGUNO_TOKEN');
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
}
function kushki_get_transaction($trans=false){
	$ret = false;
	global $mysqli;

	$db = 'at_payments_app';
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

	$db = 'at_payments_app';
	$table = 'transactions';

	$insert_arr = [];
		$insert_arr['payment_method_id']=1; //1=kushki
		$insert_arr['type_id']=1; //1=web_deposit
		$insert_arr['unique_id']=(isset($trans['unique_id'])?$trans['unique_id']:$unique_id);
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
	$ret = false;
	$rq = [];
		$rq['url']='https://api-uat.kushkipagos.com/smartlink/v1/webcheckout';
		$rq['rq']=[];
			$rq['rq']['kind']='webcheckout';
			$rq['rq']['contactDetail']['name']=$client['name'];
			$rq['rq']['contactDetail']['email']=$client['email'];
			// $rq['rq']['redirectURL']=$client['this_url'];
			$rq['rq']['redirectURL']='https://www.apuestatotal.com';
			$rq['rq']['products'][0]['description']='Recarga Web Apuesta Total';
			$rq['rq']['products'][0]['name']='Recarga Web Apuesta Total';
			$rq['rq']['products'][0]['quantity']=1;
			$rq['rq']['products'][0]['unitPrice']=$client['kushki_value'];
			$rq['rq']['paymentConfig']['amount']['subtotalIva']=0;
			$rq['rq']['paymentConfig']['amount']['subtotalIva0']=$client['kushki_value'];
			$rq['rq']['paymentConfig']['amount']['iva']=0;
			$rq['rq']['paymentConfig']['amount']['currency']='PEN';
			$rq['rq']['paymentConfig']['paymentMethod']='credit-card';
		$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
		$rq['h']=[];
			$rq['h'][] = "Content-Type: application/json";
			$rq['h'][] = "Private-Merchant-Id: " . env('KUSHKI_MERCHANT_ID');
	$kushki_curl = kushki_curl($rq);

	// Array
	// (
	// 	[webcheckoutId] => -wYjZC1e9
	// 	[webcheckoutUrl] => https://uat-webcheckout.kushkipagos.com/webcheckout/-wYjZC1e9
	// )

	if(array_key_exists("curl_error", $kushki_curl)){
		$ret['curl_error']=$kushki_curl;
	}elseif(array_key_exists("code", $kushki_curl)){
		$ret['curl']=$kushki_curl;
		$ret['rq']=$rq;
		print_r($rq['rq']); exit();
	}else{
		$ret = $kushki_curl;
	}
	return $ret;

	// $rq_json = '{
	// 	  "kind": "webcheckout",
	// 	  "contactDetail": {
	// 	    "name": "John Doe"
	// 	  },
	// 	  "redirectURL": "https://www.kushki.com",
	// 	  "products": [
	// 	    {
	// 	      "description": "Tenis",
	// 	      "name": "runners",
	// 	      "quantity": 2,
	// 	      "unitPrice": 100000
	// 	    }
	// 	  ],
	// 	  "paymentConfig": {
	// 	    "amount": {
	// 	      "subtotalIva": 238000,
	// 	      "subtotalIva0": 0,
	// 	      "iva": 19000,
	// 	      "currency": "COP"
	// 	    },
	// 	    "paymentMethod": "credit-card"
	// 	  }
	// 	}';
}
function kushki_curl($rq=false){
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => $rq['url'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => (array_key_exists('timeout', $rq)?$rq['timeout']:30),
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $rq['rq'],
		CURLOPT_HTTPHEADER => $rq['h'],
	]);
	$result = curl_exec($curl);
	if (curl_errno($curl)) {
		$response_arr = ['curl_error'=>curl_error($curl)];
		// echo 'Error:' . curl_error($curl);
		// exit();
	}else{
		$response_arr = json_decode($result, true);
	}
	curl_close($curl);
	return $response_arr;
}
?>