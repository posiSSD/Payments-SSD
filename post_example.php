<?php
$bc_param = [];
$bc_param["host"]="https://payments1.betconstruct.com/";
$bc_param["resource"]="TerminalCallbackPG";
$bc_param["secretkey"]=env('BC_KUSHKI_SECRET_KEY');
$bc_param["sid"]="279";
$bc_param["currency"]="PEN";
$bs_param["paymentID"]=2064; //Kushki

$url_data["currency"]=$bc_param["currency"];
$url_data["sid"]=$bc_param["sid"];
$url_data["hashcode"]=md5(implode($url_data).$bc_param["secretkey"]);
$url_data["paymentID"]=$bs_param["paymentID"];
$bc_url="";
$bc_url =$bc_param["host"];
$bc_url.="Bets/PaymentsCallback/";
$bc_url.=$bc_param["resource"];
$bc_url.="/";
$bc_url.="?";
$bc_url.=http_build_query($url_data);

$request_headers = array();
$curl = curl_init($bc_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, false);
curl_setopt($curl, CURLOPT_TIMEOUT,6); //Timeout Seconds	
$response = curl_exec($curl);

DB::connection('mysql_bc_kushkipayment')
    ->table('tbl_api_activities')
    ->insert([
        "command" => $url_data["command"],
        "account" => (array_key_exists("account",$url_data)?$url_data["account"]:null),
        "txn_id" => (array_key_exists("txn_id",$url_data)?$url_data["txn_id"]:null),
        "url" => $bc_url,
        "response" => ($response?$response:null),
    ]);
if($response){
    $response_arr = json_decode($response,true);
    if(is_array($response_arr)){
        if(array_key_exists("txn_id",$url_data)){
            $response_arr["response"]["txn_id"]=$url_data["txn_id"];
        }
        return $response_arr;
    }else{
        return false;
    }
}else{
    return false;
}
?>