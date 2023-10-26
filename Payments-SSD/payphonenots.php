<?php
include '../env.php';
include ROOT_PATH.'/db.php';
include ROOT_PATH.'/payphone/sys/helpers.php';
include ROOT_PATH.'/sys/helpers.php';



$transaccion = $_GET["id"];
$client = $_GET["clientTransactionId"];

$data_array = array(
    "id" => (int)$transaccion,
    "clientTxId" => $client
);

//echo "dataarray : ".$data_array;

$rq = [];
$rq['id']=(int)$transaccion;
$rq['clientTxId']=$client;

//echo "rq : ".$rq;


//Preparar JSON de llamada
/*
$data_array = array(
    "id"=> (int)$transaccion,
    "clientTxId"=>$client );
    
    $data = json_encode($data_array);
    
    //Iniciar Llamada
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://pay.payphonetodoesposible.com/api/button/V2/Confirm");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt_array($curl, array(
    CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer TU TOKEN DE AUTENTICACIÓN", "Content-Type:application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
*/













$create_response_apibuttonV2confirm = api_button_V2_Confirm ($data_array);
create_or_update_bd_api_transactions($create_response_apibuttonV2confirm );


//En la variable result obtienes todos los parámetros de respuesta
echo $create_response_apibuttonV2confirm;
?>