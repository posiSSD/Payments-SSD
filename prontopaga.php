<?php

include 'env.php';
include 'db.php';

$ret = false;
$rq = [];
$rq['url']='https://sandbox.insospa.com/api/payment/new';
$rq['method']="POST";
	// Define los datos de la solicitud para Prometeo
$rq['rq'] = [
	"currency" => "USD",
	"country" => "EC",
	"amount" => rand(5, 50),
	"clientName" => "Posi Vargas Cusacani",
	"clientEmail" => "sinpossio85@gmail.com",
	"clientPhone" => "999999999",
    "clientDocument" => "999999999",
	"paymentMethod" => "prometeo_payment",
	'urlConfirmation' => env('RESPONSEURL_PRONTOPAGA'),
    'urlFinal' => env('RESPONSEURL_PRONTOPAGA'),
    'urlRejected' => env('RESPONSEURL_PRONTOPAGA'),
    "order" => md5(microtime().rand(0,1000)),
    "isIframePay" => false
    ];

// Generar la firma
$secret_key = env('SECRETKEY_PRONTOPAGA'); // Ajusta con tu clave secreta
$rq['rq']['sign'] = generate_signature($rq['rq'], $secret_key);

echo $rq['rq'];

// Define el header de la solicitud para Prometeo	
$rq['h']=[
	"Content-Type: application/json",
	"X-API-Key: " . env('TOKEN_PRONTOPAGA') // Ajusta la clave de API correcta
	];

echo $rq;

$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
$prontopaga_curl = prontopaga_curl($rq);
	
if (array_key_exists("curl_error", $prontopaga_curl)) {
    $ret['curl_error'] = $prontopaga_curl;
} elseif (array_key_exists("code", $prontopaga_curl)) {
    $ret['curl'] = $prontopaga_curl;
    $ret['rq'] = $rq;
    exit();
} else {
    $ret = $prontopaga_curl;
}
echo $ret;	

function generate_signature($parameters, $secret_key) {

    $keys = array_keys($parameters);
    sort($keys);
        
    $toSign = '';
    foreach ($keys as $key) {
        $toSign .= $key . $parameters[$key];
    }
    
    $signature = hash_hmac('sha256', $toSign, $secret_key);
    
    return $signature;
    
}

function prontopaga_curl($rq = false) {
        
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






?>