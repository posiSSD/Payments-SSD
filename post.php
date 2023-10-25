<?php
include 'prometeo/env.php';
include 'prometeo/db.php';

$url_data = [];
$url_data["command"] = "pay";
$url_data["txn_id"] = 1;
$url_data["account"] = 1674627753;
$url_data["amount"] = 1;

$bc_param = [];
$bc_param["host"] = "https://payments1.betconstruct.com/";
$bc_param["resource"] = "TerminalCallbackPG";
$bc_param["secretkey"] = env('BC_KUSHKI_SECRET_KEY');
$bc_param["sid"] = "18751709";
$bc_param["currency"] = "USD";
$bs_param["paymentID"]=1819;

$url_data["currency"] = $bc_param["currency"];
$url_data["sid"] = $bc_param["sid"];
$url_data["hashcode"] = md5(implode($url_data) . $bc_param["secretkey"]); // hashcode check

$bc_url = $bc_param["host"];
$bc_url .= "Bets/PaymentsCallback/";
$bc_url .= $bc_param["resource"] . "/";
$bc_url .= "?" . http_build_query($url_data);

$request_headers = array();
// ... (código previo)

$curl = curl_init($bc_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 6);
$response = curl_exec($curl);

if ($response) {
    $response_arr = json_decode($response, true);
    if (is_array($response_arr)) {
        if (array_key_exists("txn_id", $url_data)) {
            $response_arr["response"]["txn_id"] = $url_data["txn_id"];
        }
        echo "Success: Response Content: " . print_r($response_arr, true);
    } else {
        echo "Failed to decode JSON response.";
    }
} else {
    echo ($bc_url);
    //echo "Request to $bc_url failed. cURL error: " . curl_error($curl)." Request : ".$request;

    

}

curl_close($curl);





?>
