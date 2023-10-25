<?php
include '../env.php';
include '../db.php';

$variable = md5(microtime().rand(0,1000));

// Configura los datos de la solicitud
$data = [
    "amount" => "100",
    "amountWithoutTax" => "100",
    "currency" => "USD",
    "clientTransactionId" => $variable,
    "responseUrl" => "https://payments.totalbet.com/Payments-SSD/payphonenots.php",
];

// Convierte los datos a JSON
$json_data = json_encode($data);

// Configura los encabezados de la solicitud
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer '.env('TOKEN_PAYPHONE'), 
];

// Configura las opciones de cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://pay.payphonetodoesposible.com/api/button/Prepare');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// Realiza la solicitud
$response = curl_exec($curl);

// Verifica si hay errores
if (curl_errno($curl)) {
    $error = ['curl_error' => curl_error($curl)];
    echo json_encode($error);
} else {
    $response_arr = json_decode($response, true);
    echo json_encode($response_arr);
}

// Cierra la conexión cURL
curl_close($curl);
?>
