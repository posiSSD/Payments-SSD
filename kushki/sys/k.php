<?php
include '/var/www/payments.apuestatotal.app/kushki/env.php';
$ret = [];
$ret["status"] = 500;
$ret["return"] = "Error";
if(isset($_POST['kushki_create_payment_button'])){
	// print_r($_POST); exit();
	$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);
	if(array_key_exists("webcheckoutUrl", $kushki_create_payment_button)){
		$ret["status"] = 200;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["webcheckoutUrl"];
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['kushki_create_payment_button'];
	}
	// print_r($kushki_create_payment_button); exit();
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




	// $tst_curl = [];
	// 	$tst_curl['url']='https://api.apuestatotal.com/v2/sms_otp';
	// 	$tst_curl['rq']=[];
	// 		$tst_curl['rq']['mobile'] = '998877814';
	// 		$tst_curl['rq']['message'] = 'tst';
	// 	$tst_curl['h']=[];
	// 		$tst_curl['h'][] = "accept: application/json";
	// 		$tst_curl['h'][] = "authorization: Bearer " . env('MLLAGUNO_TOKEN');

	// kushki_curl($tst_curl);
}
// kushki_create_payment_button();

function kushki_curl($rq=false){
	// print_r('kushki_curl');
	// echo $rq['rq'];
	// echo "\n\n";
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
	// print_r($result);
	// echo "\n\n";
	// exit();
	if (curl_errno($curl)) {
		echo 'Error:' . curl_error($curl);
	} 
	$response_arr = json_decode($result, true);
	curl_close($curl);
	return $response_arr;
	// print_r($response_arr);
	// exit();
}
function custom_json_encode($s){
	// return $s;
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