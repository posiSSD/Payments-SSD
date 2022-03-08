<?php
include '/var/www/payments.apuestatotal.app/kushki/env.php';
include '/var/www/payments.apuestatotal.app/kushki/db.php';
$ret = [];
$ret["status"] = 500;
$ret["return"] = "Error";
$unique_id = md5(microtime().rand(0,1000));
if(isset($_POST['kushki_create_payment_button'])){
	// print_r($_POST); exit();
	kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
	$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);
	if(array_key_exists("webcheckoutUrl", $kushki_create_payment_button)){
		$ret["status"] = 200;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["webcheckoutUrl"];
		// print_r($kushki_create_payment_button); exit();
		kushki_create_or_update_transaction(['status'=>2,'ext_id'=>$kushki_create_payment_button['webcheckoutId']]);
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['kushki_create_payment_button'];
	}
	// print_r($kushki_create_payment_button); exit();
}
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
function kushki_create_or_update_transaction($trans=false){
	global $unique_id;
	global $mysqli;

	$db = 'at_payments_app';
	$table = 'transactions';

	$insert_arr = [];
		$insert_arr['payment_id']=1; //1=kushki
		$insert_arr['type_id']=1; //1=web_deposit
		$insert_arr['unique_id']=(isset($trans['unique_id'])?$trans['unique_id']:$unique_id);
		if(isset($trans['client_id'])){
			$insert_arr['client_id']=$trans['client_id'];
		}
		if(isset($trans['ext_id'])){
			$insert_arr['ext_id']=$trans['ext_id'];
		}
		if(isset($trans['kushki_value'])){
			$insert_arr['amount']=$trans['kushki_value'];
		}
		if(isset($trans['status'])){
			$insert_arr['status']=$trans['status'];
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
			// $rq['rq']['products'][0]['unitPrice']=1.23;
			$rq['rq']['paymentConfig']['amount']['subtotalIva']=0;
			$rq['rq']['paymentConfig']['amount']['subtotalIva0']=$client['kushki_value'];
			// $rq['rq']['paymentConfig']['amount']['subtotalIva0']=1.23;
			$rq['rq']['paymentConfig']['amount']['iva']=0;
			$rq['rq']['paymentConfig']['amount']['currency']='PEN';
			$rq['rq']['paymentConfig']['paymentMethod']='credit-card';
		// print_r($rq); exit();
		// print_r(json_encode($rq['rq'], JSON_NUMERIC_CHECK)); exit();
		$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
		$rq['h']=[];
			$rq['h'][] = "Content-Type: application/json";
			$rq['h'][] = "Private-Merchant-Id: " . env('KUSHKI_MERCHANT_ID');
			// print_r($rq); 
	$kushki_curl = kushki_curl($rq);

	// Array
	// (
	// 	[webcheckoutId] => -wYjZC1e9
	// 	[webcheckoutUrl] => https://uat-webcheckout.kushkipagos.com/webcheckout/-wYjZC1e9
	// )

	if(array_key_exists("code", $kushki_curl)){
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
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $rq['rq'],
		CURLOPT_HTTPHEADER => $rq['h'],
	]);
	$result = curl_exec($curl);
	if (curl_errno($curl)) {
		echo 'Error:' . curl_error($curl);
	} 
	$response_arr = json_decode($result, true);
	curl_close($curl);
	return $response_arr;
}
function custom_json_encode($s){
	$j='{';
	foreach ($s as $k => $v) {
		$j.='"'.$k.'"';
		$j.=':';
		switch (gettype($v)) {
			case 'integer':
				$j.=$v;
			break;
			case 'string':
				$j.='"'.$v.'"';
			break;
			case 'double':
				$j.=$v;
				if(strstr($v, ".")){
					for ($i = 2; $i > (strlen($v) - strrpos($v, '.') - 1); $i--) {
						$j.='0';
					}
				}else{
					$j.='.00';
				}
			break;
			case 'array':
				$j.='[';
				$j.=custom_json_encode($v);
				// $j.=implode(",", $v);
				$j.=']';
			break;
			case 'boolean':
				if($v){
					$j.="true";
				}else{
					$j.="false";
				}
			break;
			default:
				print_r($k);
				echo "\n\n\n";
				print_r(gettype($v)); exit();
				echo "\n\n\n";
				// $j.=''.gettype($v).'';
			break;
		}
		$j.=',';
	}
	$j = rtrim($j,",");
	$j.='}';
	// print_r($j); exit();
	return $j;
}
echo json_encode($ret);
?>