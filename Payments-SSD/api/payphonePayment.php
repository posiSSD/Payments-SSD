<?php

function payment_deposit($request){

    // $myRequest = [];
    // $myRequest['setMethod'] = 'POST';
    // $myRequest['request'] = [
    //    "account" => $request['account'],
    //    "amount" => $request['amount']
    // ];
    //$myRequest['payment_method'] = $request['payment_method'];

    $insert_db = [];
    $insert_db['eject'] = 'insert';
    $insert_db['account'] = $request['request']['account'];
    $insert_db['amount'] = $request['request']['amount'];
    $insert_db['status'] = 0;   
    $insert_db['payment_method'] = $request['payment_method'];

    $transaction_id = insert_or_update_tbl_transactions($insert_db);

    
    $url_data = [];
    $url_data["command"] = "pay";
    $url_data["txn_id"] = $transaction_id['id'];
    $url_data["account"] = $request['request']['account'];
    $url_data["amount"] = $request['request']['amount']; 
    //$url_data["payment_method"] = $request['payment_method'];

    $payment_curl = payment_curl($url_data);
    
    //simulacion
    /*
    $txn_id = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
    $payment_curl = [
        'http_code' => 200,
        'created' => '2023-10-26 14:30:00',
        'response' => [
            'txn_id' => $txn_id,
            'account' => $request['request']['account'],
            'amount' => $request['request']['amount'],
        ]
    ];
    */
    
    if($payment_curl){
        if($payment_curl["response"]["code"]){
            return ['http_code' => 400, 'status' => 'Error', 'result' =>  $payment_curl["response"]];
        }
        else{
            $transaction_id['status'] = 1;
            $transaction_id['eject'] = 'update';

            insert_or_update_tbl_transactions($transaction_id);
            
            return ['http_code' => 200, 'status' => 'Ok', 'result' =>  $payment_curl["response"]];
        }
    }else{
        return ['http_code' => 408, 'status' => 'Error', 'result' =>  $transaction_id];
    }
    return ['http_code' => 200, 'status' => 'Ok', 'result' => $transaction_id];
        // Resto del código...

}   


function payment_curl($url_data){
    
    // $url_data = [];
    // $url_data["command"] = "pay";
    // $url_data["txn_id"] = 15;
    // $url_data["account"] = 1674627753;
    // $url_data["amount"] = 10;
    


    //Payment ID - 366

	$bc_param = [];
	$bc_param["host"]="https://payments1.betconstruct.com/";
	$bc_param["resource"]="TerminalCallbackPG";
	$bc_param["secretkey"]=env('TOKEN_PAYPHONE');
	$bc_param["sid"]="279";
	$bc_param["currency"]="USD";
	$bs_param["paymentID"]=366; //payphone //Payment ID - 366

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

	//$request_headers = array();
	$curl = curl_init($bc_url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_TIMEOUT,6); //Timeout Seconds	
		
	$response = curl_exec($curl);

	//insert_tbl_api_activities($url_data, $bc_url, $response);


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
}

function insert_tbl_api_activities($url_data, $bc_url, $response){
    

	$bd = 'bc_kushkipayment';
	$table = 'tbl_api_activities';

	$rq = [];

    $command = $url_data["command"];
    $account = (array_key_exists("account",$url_data)?$url_data["account"]:null);
    $txn_id  = (array_key_exists("txn_id",$url_data)?$url_data["txn_id"]:null);
    $url = $bc_url;
    $response  = $response?$response:null;
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    // Consulta SQL para insertar los datos en la tabla transactions
    $sql_details = "INSERT INTO $table (command, account, txn_id, url, response, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_details = $mysqli_kushki->prepare($sql_details);
    $stmt_details->bind_param("sssssss", $command, $account, $txn_id, $url, $response, $created_at, $updated_at);
    // Ejecutar la consulta
    if ( $stmt_details->execute() === TRUE) {

        // Obtener el ID de la transacción insertada
        $id = $mysqli_kushki->insert_id;

        $rq['id'] = $id;
        $rq['command'] = $type_transaction_id;
        $rq['account'] = $txt_id;
        $rq['txn_id'] = $amount;
        $rq['url '] = $shop_id;
        $rq['response'] = $cashdesk_id;
        $rq['created_at '] = $user_id;
        $rq['updated_at '] = $status;
            
        return $rq;  
    } 

}

function insert_or_update_tbl_transactions($insert_db) {
    global $mysqli_kushkipayment;

    $bd = 'bc_kushkipayment';
	$table = 'tbl_transactions';
    
    $rq = [];

    if ($insert_db['eject'] == "insert") {
        $client_id = $insert_db['account'] ?? 0;
        $amount = $insert_db['amount'] ?? 0;
        $status = $insert_db['status'] ?? 0;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $payment_method = $insert_db['payment_method'];

        // Consulta SQL para insertar los datos en la tabla transactions
        $sql_insert = "INSERT INTO $table (client_id, amount, status, created_at, updated_at, payment_method)
        VALUES (?, ?, ?, ?, ?, ?)";
        $sql_insert = $mysqli_kushkipayment->prepare($sql_insert);
        $sql_insert->bind_param("ssssss", $client_id, $amount, $status, $created_at, $updated_at, $payment_method);
        
        // Ejecutar la consulta
        if ($sql_insert->execute() === TRUE) {
            // Obtener el ID de la transacción insertada
            $id = $mysqli_kushkipayment->insert_id;

            $rq['id'] = $id;
            $rq['client_id'] = $client_id;
            $rq['amount'] = $amount;
            $rq['status'] = $status;
            $rq['created_at'] = $created_at;
            $rq['updated_at'] = $updated_at;

            return $rq;  
        } 
    } elseif ($insert_db['eject'] == "update") {
        $transaction_id = $insert_db['id'];
        $status = $insert_db['status'];
        $updated_at = date('Y-m-d H:i:s'); // Agregar fecha y hora actual

        // Consulta SQL para actualizar el estado de la transacción
        $sql_update = "UPDATE $table SET status = ?, updated_at = ? WHERE id = ?";
        $stmt_update = $mysqli_kushkipayment->prepare($sql_update);
        $stmt_update->bind_param("ssi", $status, $updated_at, $transaction_id); // Cambiar "ii" a "ssi"
        if ($stmt_update->execute() === TRUE) {
            
            return $rq;
        }
    }
    
    return ['http_code' => 500, 'status' => 'Error', 'result' => 'Error en la base de datos'];
}



?>