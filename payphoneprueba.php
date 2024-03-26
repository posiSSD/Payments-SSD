<?php

$data_array = array(
    "id" => 28758578,
    "clientTxId" => '4858cf05684e530370e784ab15ec7a66'
);
$payphone_array_response = payphone_api_confirm ($data_array);

echo "Response payphone_array_response :\n";
print_r($payphone_array_response);

function payphone_api_confirm ($data_array){
$ret = false;
$rq = [];
$rq['url']='https://pay.payphonetodoesposible.com/api/button/V2/Confirm';
$rq['method']="POST";

$rq['rq'] = [
    "id" => $data_array['id'],
    "clientTxId" => $data_array['clientTxId']
];

// Define el header de la solicitud para Prometeo	
$rq['h']=[
    "Content-Type: application/json",
    'Authorization: Bearer '. '3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ'
];
// Imprimir el contenido de $RQ en la consola
$rq['rq']=json_encode($rq['rq'],JSON_NUMERIC_CHECK);
$peticion_curl = kushki_curl($rq);

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
return $ret;
}


?>