<?php

function payment_deposit($request){

    $insert_db = [];
    $insert_db['account'] = $request['request']['account'];
    $insert_db['amount'] = $request['request']['amount'];
    $insert_db['status'] = 0;
    $insert_db['payment_method'] = $request['payment_method'];
    //////
    $insert_db['order_id'] = $request['order_id'];
    //////

     ///prueba
    //consolelogdata($insert_db);
    $transaction_id = insert_tbl_transactions($insert_db);

    $url_data = [];
    $url_data["command"] = "pay";
    //$url_data["txn_id"] = $transaction_id['id'];
    $url_data["txn_id"] = $transaction_id['order_id'];
    $url_data["account"] = $request['request']['account'];
    $url_data["amount"] = $request['request']['amount']; 
    $url_data['payment_method'] = $request['payment_method']; 

    $payment_curl = payment_curl($url_data);
    //consolelogdata($payment_curl); 

    if ($payment_curl) {

        $payment_curl['response']['account'] = $request['request']['account'];
        $payment_curl['response']['amount'] = $request['request']['amount'];

        if ($payment_curl["response"]["code"] == 0) {
            $insert_db_new = [];
            $insert_db_new['id'] = $transaction_id['id'];
            $insert_db_new['status'] = 1; 
            //consolelogdata($insert_db_new);

            update_tbl_transactions($insert_db_new);

            return ['http_code' => 200, 'status' => 'Ok', 'result' => $payment_curl["response"]];
        } else {
            return ['http_code' => 400, 'status' => 'Error', 'result' => $payment_curl["response"]];
        }
    } else {
        return ['http_code' => 408, 'status' => 'Error', 'result' => $transaction_id];
    }   
}   
function payment_curl($url_data){

	$bc_param = [];
	$bc_param["host"]="https://payments1.betconstruct.com/";
	$bc_param["resource"]="TerminalCallbackPG";
	$bc_param["secretkey"]=env('BC_PAYPHONE_SECRET_KEY');
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
        $bs_param["paymentID"]="3803"; ///antiguo 3624  ///new 3803
    } else if ($url_data['payment_method'] == 'prometeo'){
        $bs_param["paymentID"]="4016"; ///antiguo 3944  ///new 4016
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
    //consolelogdata($response);

    insert_tbl_api_activities($url_data, $bc_url, $response);
        
    if ($response) {
        $response_arr = json_decode($response, true);
        if (is_array($response_arr)) {
            // Verificar si "txn_id" existe en $url_data y asignarlo a $response_arr si es así
            if (array_key_exists("txn_id", $url_data)) {
                $response_arr["response"]["txn_id"] = $url_data["txn_id"];
            }
            return $response_arr;
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}

function insert_tbl_transactions($insert_db) {

    global $mysqli_kushkipayment;
    $bd = 'tb_payment_transfer';
	$table = 'tbl_transactions';
    
    $rq = [];
    $client_id = $insert_db['account'] ?? 0;
    $amount = $insert_db['amount'] ?? 0;
    $status = $insert_db['status'] ?? 0;
    $created_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
    $updated_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
    $payment_method = $insert_db['payment_method'];
    ////////////////////////////////////////////////////
    $order_id = $insert_db['order_id'];
    //////////////////////////////////////////////////////
    //consolelogdata($insert_db);
   

    $sql_insert = "INSERT INTO $table (client_id, amount, status, created_at, updated_at, payment_method, order_id)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $sql_insert = $mysqli_kushkipayment->prepare($sql_insert);
    $sql_insert->bind_param("sssssss", $client_id, $amount, $status, $created_at, $updated_at, $payment_method, $order_id);
        
    // Ejecutar la consulta
    if ($sql_insert->execute() === TRUE) {
        $id = $mysqli_kushkipayment->insert_id;
        $rq['id'] = $id;
        $rq['client_id'] = $client_id;
        $rq['amount'] = $amount;
        $rq['status'] = $status;
        $rq['created_at'] = $created_at;
        $rq['updated_at'] = $updated_at;
        $rq['payment_method'] = $payment_method;
        $rq['order_id'] = $order_id;
        //consolelogdata($rq); 
        return $rq;  
    } else {
        $errordb = $sql_insert->error;
        //consolelogdata($errordb);
        return false; 
    }        
}
function update_tbl_transactions($insert_db) {

    global $mysqli_kushkipayment;
    $bd = 'tb_payment_transfer';
	$table = 'tbl_transactions';
    
    $rq = [];

    $transaction_id = $insert_db['id'];
    $status = $insert_db['status'];
    $updated_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s'); 

    $sql_update = "UPDATE $table SET status = ?, updated_at = ? WHERE id = ?";
    $stmt_update = $mysqli_kushkipayment->prepare($sql_update);
    $stmt_update->bind_param("ssi", $status, $updated_at, $transaction_id); 
    if ($stmt_update->execute() === TRUE) {
        $rq['id'] = $transaction_id;
        $rq['status'] = $status;
        $rq['updated_at'] = $updated_at;
        //consolelogdata($rq); 
        return $rq;
    } else {
        $errordb = $stmt_update->error;
        //consolelogdata($errordb);
        return false; 
    }
}
function insert_tbl_api_activities($url_data, $bc_url, $response){
    global $mysqli_kushkipayment;
	$bd = 'tb_payment_transfer';
	$table = 'tbl_api_activities';

	$rq = [];

    $command = $url_data["command"];
    $account = (array_key_exists("account",$url_data)?$url_data["account"]:null);
    $txn_id  = (array_key_exists("txn_id",$url_data)?$url_data["txn_id"]:null);
    $url = $bc_url;
    //$response = $response ? $response : null;
    $decoded_response = json_decode($response);
    if ($decoded_response === null && json_last_error() !== JSON_ERROR_NONE) {
        // Si $response no es un JSON válido, lo convertimos a JSON
        $response = json_encode($response);
    } else {
        $response = $response ? $response : null;
    }
    $created_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
    $updated_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');

    $sql_details = "INSERT INTO $table (command, account, txn_id, url, response, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_details = $mysqli_kushkipayment->prepare($sql_details);
    $stmt_details->bind_param("sssssss", $command, $account, $txn_id, $url, $response, $created_at, $updated_at);
    
    if ( $stmt_details->execute() === TRUE) {

        $id = $mysqli_kushkipayment->insert_id;
        $rq['id'] = $id;
        $rq['command'] = $command;
        $rq['account'] = $account;
        $rq['txn_id'] = $txn_id;
        $rq['url '] = $url;
        $rq['response'] = $response;
        $rq['created_at '] = $created_at;
        $rq['updated_at '] = $updated_at;   
        //consolelogdata($rq); 
        return $rq;  
    }
    else {
        $errordb = $stmt_details->error;
        //consolelogdata($errordb);
        return false; 
    } 
}
?>