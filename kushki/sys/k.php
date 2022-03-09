<?php
include '/var/www/payments.apuestatotal.app/kushki/env.php';
include '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/payments.apuestatotal.app/kushki/sys/helpers.php';
$ret = [];
$ret["status"] = 500;
$ret["return"] = "Error";
$unique_id = md5(microtime().rand(0,1000));
if(isset($_POST['kushki_create_payment_button'])){
	// print_r($_POST); exit();
	kushki_create_or_update_transaction($_POST['kushki_create_payment_button']);
	$kushki_create_payment_button = kushki_create_payment_button($_POST['kushki_create_payment_button']);
	if(array_key_exists("webcheckoutUrl", $kushki_create_payment_button)){
		$ret["status"] = 201;
		$ret["return"] = "Ok";
		$ret["url"]=$kushki_create_payment_button["webcheckoutUrl"];
		// print_r($kushki_create_payment_button); exit();
		kushki_create_or_update_transaction(['status'=>2,'order_id'=>$kushki_create_payment_button['webcheckoutId']]);
	}else{
		$ret["status"] = 500;
		$ret["result"] = $kushki_create_payment_button;
		$ret["_POST"]=$_POST['kushki_create_payment_button'];
	}
	// print_r($kushki_create_payment_button); exit();
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