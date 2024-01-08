<?php
include 'env.php';
include 'db.php';

// Datos de la cuenta
$url_data = [];
$url_data["command"] = "pay";
$url_data["txn_id"] = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
$url_data["account"] = 1674627753;
$url_data["amount"] = 10;

// Configuración de BetConstruct
$bc_param = [];
$bc_param["host"] = "https://payments1.betconstruct.com/";
$bc_param["resource"] = "TerminalCallbackPG";
$bc_param["secretkey"] = '3e_lfs3syayUBEpyx1FD09A4K66scfjmDLvBBuirB0iGsNvndfcaAxbX3O0bSfoXl86aH87G6hKQ2nMJhB9dP7k1tqAnA5LDymAmBmE0fgQr8dwr7DNXa_vVN6LJH1US4i7yxia08TA_wUPYSPwn3mecajkX5abz6w-k9-Yo5SAnBlP6AInSOSo_maCuv88q_G68JjLhEJKhBrp_7aeVdgwLalLbGfY81NbIepdTEMOkP_iNjHaJNT2bQABfktMzZ007Orin5CqaD3CVJcJpe9SAucxQswwrTGIEenH11mKHDX15jWe5tH_GEl0M4yga6X9JAQ';
$bc_param["sid"] = "279";
$bc_param["currency"] = "USD";
$bs_param["paymentID"] = 366;

$url_data["currency"] = $bc_param["currency"];
$url_data["sid"] = $bc_param["sid"];
$url_data["hashcode"] = md5(implode($url_data) . $bc_param["secretkey"]); // Comprobación del hashcode
$url_data["paymentID"] = $bs_param["paymentID"];

$bc_url = $bc_param["host"] . "Bets/PaymentsCallback/" . $bc_param["resource"] . "/?" . http_build_query($url_data);
//  https://payments1.betconstruct.com/Bets/PaymentsCallback/
try {
    $curl = curl_init($bc_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 6);

    // Intentar realizar la solicitud cURL
    $response = curl_exec($curl);

    if ($response === false) {
        echo "True :" . $response;
        error_log("True :" . print_r($response, true));
    } else {
        error_log("False :" . print_r($response, true));
        echo "False :" . $response;
    }
    

} catch (Exception $e) {
    // Manejar la excepción
    echo "Excepción capturada: " . $e->getMessage();

} finally {
    // Cerrar la sesión cURL en el bloque finally
    if (isset($curl)) {
        curl_close($curl);
    }
}
?>
