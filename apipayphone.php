<?php


$ret = false;

	$rq = [];
    //https://pay.payphonetodoesposible.com/api/Links 
	//$rq['url']='https://pay.payphonetodoesposible.com/api/sale';
    $rq['url']='https://pay.payphonetodoesposible.com/api/Links';
	$rq['method']="POST";

    $value = 12;
    $unique_id = md5(microtime().rand(0,1000));
    $tokenapypayphone = 'av5a2_p1Yp8ftbESUdCt6-mJrxjjnCBqwIPNPKkzzgIh1YBV3mbvNRbpzW-AAvb65v9-CwjLgWE-FEdtPvKc7tJOCdjOJmOw4si-OI7OceY5FgTnWIqugcl-RLu7X69AMp5IRBGIxvbU-BDbKBaZASb5ITmIi7GMZ9FmS4qU7AXIimEDKOhUEKZr9qwEJogoC51HWrZaFFG2IdObKDiDJjBH7ctvWVx9fLUmahVrP6ok4nhnyr4XJrqo9GREJZNLZVRKuu0X0tWdsUnLI_2XQG4l8Fx6kCnaK3ar52ybTe3PUmDX-vSNSU0K2f8N1GQrb1SYDw';
	$dolar_Value_Payphone = $value*100;  //en la doc de payphone 1 dolar = 100//

	$rq['rq'] = [
        
        "amount" => $dolar_Value_Payphone,
		"amountWithoutTax" => $dolar_Value_Payphone,
        "currency" => "USD", 
        "clientTransactionId" => 'idlink001' ,
        "email" => 'sinpossio85@gmail.com',
        "responseUrl" => 'https://payments.totalbet.com/Payments-SSD/payphonenots.php',    
    ];

    //"PhoneNumber" => "942616310",
    //"CountryCode" => "51", 
	// Define el header de la solicitud para Prometeo	
	$rq['h']=[
		"Content-Type: application/json",
		'Authorization: Bearer '. $tokenapypayphone 
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
    echo $ret;

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


function consolelogdata($data) {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $pFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'Unknown Function';

    echo '<script>';
    echo 'console.log("'. $pFunction . '");';
    echo 'console.log(": ", ' . json_encode($data) . ');';
    echo '</script>';
}
















?>