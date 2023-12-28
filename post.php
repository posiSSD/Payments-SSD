<?php
include 'env.php';
include 'db.php';



//datos de la cuenta///
$url_data = [];
$url_data["command"] = "pay";
$url_data["txn_id"] = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);;
$url_data["account"] = 1674627753;
$url_data["amount"] = 10;


//construccion
$bc_param = [];
$bc_param["host"] = "https://payments1.betconstruct.com/";
$bc_param["resource"] = "TerminalCallbackPG";
//token payphone 3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ
$bc_param["secretkey"] = '3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ';
//$bc_param["secretkey"] = env('TOKEN_PAYPHONE');
$bc_param["sid"] = "279";
$bc_param["currency"] = "USD";
$bs_param["paymentID"]=366;

$url_data["currency"] = $bc_param["currency"];
$url_data["sid"] = $bc_param["sid"];
$url_data["hashcode"] = md5(implode($url_data).$bc_param["secretkey"]); // hashcode check
$url_data["paymentID"]=$bs_param["paymentID"];

$bc_url="";
$bc_url = $bc_param["host"];
$bc_url .= "Bets/PaymentsCallback/";
$bc_url .= $bc_param["resource"];
$bc_url.="/";
$bc_url.="?";
$bc_url.=http_build_query($url_data);

//$request_headers = array();
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
    echo "Else: ".$bc_url;
    //echo "Request to $bc_url failed. cURL error: " . curl_error($curl)." Request : ".$request;
}

curl_close($curl);

?>


