<?php
include 'env.php';
include 'db.php';


    //
    $url_data = [];
    $url_data["command"] = "pay";
    //$url_data["txn_id"] = $transaction_id['id'];
    $url_data["txn_id"] = '123456789';
    $url_data["account"] = '1674627753';
    $url_data["amount"] = '5';
    $url_data['payment_method'] = 'prometeo'; 
    //


    $bc_param = [];
	$bc_param["host"]="https://payments1.betconstruct.com/";
	$bc_param["resource"]="TerminalCallbackPG";
	$bc_param["secretkey"]='wwaw4TbqSrO24gH22';
	$bc_param["sid"]="18751709";
	$bc_param["currency"]="USD";
    //payphone  - 366 -- 15134 //FUNCIONA  - 51 // 15134 prometeo  //NewPrometeo (14207) //NewPayphone (14177) // 3803
    /*
    if($url_data['payment_method'] == 'payphone'){
        $bs_param["paymentID"]="14177";
    } else if ($url_data['payment_method'] == 'prometeo'){
        $bs_param["paymentID"]="14207";
    } else {
        $bs_param["paymentID"]="3803";
    }
    */
    if($url_data['payment_method'] == 'payphone'){
        $bs_param["paymentID"]="3624";
    } else if ($url_data['payment_method'] == 'prometeo'){
        $bs_param["paymentID"]="3944";
    } else {
        $bs_param["paymentID"]="3803";
    }
    unset($url_data["payment_method"]);
    
    $url_data["currency"]=$bc_param["currency"];
	$url_data["sid"]=$bc_param["sid"];
	$url_data["hashcode"]=md5(implode($url_data).$bc_param["secretkey"]);
	$url_data["paymentID"]=$bs_param["paymentID"];

    $bc_url = $bc_param["host"] . "Bets/PaymentsCallback/" . $bc_param["resource"] . "/?" . http_build_query($url_data);

    //consolelogdata($bc_url);

    $curl = curl_init($bc_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_TIMEOUT,6);
        
    $response = curl_exec($curl);
    echo "Respuesta JSON recibida:\n";
    print_r($response);

        
    if ($response) {
        $response_arr = json_decode($response, true);
        if (is_array($response_arr)) {
            // Verificar si "txn_id" existe en $url_data y asignarlo a $response_arr si es así
            if (array_key_exists("txn_id", $url_data)) {
                $response_arr["response"]["txn_id"] = $url_data["txn_id"];

                echo "Respuesta JSON recibida:\n";
                print_r($response_arr);
                
            }
            return $response_arr;
        } else {
            return false;
        }
    } else {
        return false;
    }



?>