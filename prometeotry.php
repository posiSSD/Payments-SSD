<?php
include 'env.php';
include 'db.php';

    $url = 'https://banking.prometeoapi.net/transfer/logs/'.'b17f45f5f1134164b683551991d5e9e4';
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
	}
	curl_close($curl);

    var_dump($response_arr);


    /*
    b17f45f5f1134164b683551991d5e9e4
    b17f45f5f1134164b683551991d5e9e4
    */

?>