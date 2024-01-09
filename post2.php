<?php
include 'env.php';
include 'db.php';

// Datos de la cuenta
$url_data = [];
$url_data["command"] = "pay";
$url_data["txn_id"] = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);;
$url_data["account"] = 1674627753;
$url_data["amount"] = 10;

// Configuración de BetConstruct
$bc_param = [];
$bc_param["host"] = "https://payments1.betconstruct.com/";
$bc_param["resource"] = "TerminalCallbackPG";
$bc_param["secretkey"] ="wwaw4TbqSrO24gH22";
$bc_param["sid"] = "18751709";
//18751709
$bc_param["currency"] = "USD";
//2064
//$bs_param["paymentID"] = 366;
$bs_param["paymentID"] = 366;
//

$url_data["currency"] = $bc_param["currency"];
$url_data["sid"] = $bc_param["sid"];
$url_data["hashcode"] = md5(implode($url_data) . $bc_param["secretkey"]); // Comprobación del hashcode
$url_data["paymentID"] = $bs_param["paymentID"];

$bc_url = $bc_param["host"] . "Bets/PaymentsCallback/" . $bc_param["resource"] . "/?" . http_build_query($url_data);

$curl = curl_init($bc_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 6);
$response = curl_exec($curl);

echo "Response es: " . $response;
echo "curl error: " . curl_error($curl);

curl_close($curl);
?>
