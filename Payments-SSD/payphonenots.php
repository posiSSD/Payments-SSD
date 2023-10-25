<?php

include '../env.php';
include '../db.php';
include ROOT_PATH.'/sys/helpers.php';
include ROOT_PATH.'/payphone/sys/k.php';
include ROOT_PATH.'/payphone/sys/helpers.php';
include ROOT_PATH.'/payphone/api/payphonecontroller.php';


//Obtener parametros de la URL enviados por PayPhone
$transaccion = $_GET["id"];
$client = $_GET["clientTransactionId"];

$data_array = array(
    "id" => (int)$transaccion,
    "clientTxId" => $client
);

//$rq = [];
//$rq['id']=(int)$transaccion;
//$rq['clientTxId']=$client;


$create_response_apibuttonV2confirm = api_button_V2_Confirm ($data_array);
create_or_update_bd_api_transactions($create_response_apibuttonV2confirm );


//En la variable result obtienes todos los parámetros de respuesta
echo $create_response_apibuttonV2confirm;
?>