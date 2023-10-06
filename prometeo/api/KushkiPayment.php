<?php
include '../env.php';
include '../db.php';
include '../sys/helpers.php';

function payment_deposit($request){

    // $d=[];
    // $d['account']=$trans['client_id'];
    // $d['amount']=$trans['amount'];
    // $d['order_id']=$trans['order_id'];

    $insert_db = [];
    $insert_db['bd'] = 'bc_kushkipayment;';
    $insert_db['table'] = 'tbl_transactions';
    $insert_db['eject'] = 'insert';
    $insert_db['account'] = $request['client_id'];
    $insert_db['amount'] = $request['amount'];
    $insert_db['status'] = 0;   
    
    $transaction_id = insert_or_update_tbl_transactions($insert_db);


    // $rq['id'] = $id;
    // $rq['client_id'] = $client_id;
    // $rq['amount'] = $amount;
    // $rq['status'] = $status;
    // $rq['created_at '] = $created_at;
    // $rq['updated_at'] = $updated_at;

    $url_data = [];
    $url_data["command"] = "pay";
    $url_data["txn_id"] = $transaction_id['id'];
    $url_data["account"] = $transaction_id['client_id'];
    $url_data["amount"] = $transaction_id['amount']; 

    $payment_curl = payment_curl($url_data);

    if($payment_curl){
        if($payment_curl["response"]["code"]){
            return ['http_code' => 400, 'status' => 'Error', 'result' =>  $payment_curl["response"]];
        }
        else{
            $transaction_id['status'] = 1;
            insert_or_update_tbl_transactions($transaction_id);

            $payment_curl["response"]["account"] = $request['account'];
            $payment_curl["response"]["Amount"] = $request['amount'];
            
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
    // $url_data["txn_id"] = 1;
    // $url_data["account"] = 1674627753;
    // $url_data["amount"] = 1;
    
    
	$bc_param = [];
	$bc_param["host"]="https://payments1.betconstruct.com/";
	$bc_param["resource"]="TerminalCallbackPG";
	$bc_param["secretkey"]=env('BC_KUSHKI_SECRET_KEY');
	$bc_param["sid"]="18751709";
	$bc_param["currency"]="USD";
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

	insert_tbl_api_activities($url_data, $bc_url, $response);


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


function insert_or_update_tbl_transactions($insert_db){

    // $insert_db = [];
    // $insert_db['bd'] = 'bc_kushkipayment;';
    // $insert_db['table'] = 'tbl_transactions';
    // $insert_db['account'] = $request['client_id'];
    // $insert_db['amount'] = $request['amount'];
    // $insert_db['status'] 

    $bd = $insert_db['bd'];
    $table = $insert_db['table'];

    if($insert_db['eject'] == "insert"){

        $client_id  = $insert_db['account']??0;
        $amount = $insert_db['amount']??0;
        $status  = $insert_db['status']??0;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Consulta SQL para insertar los datos en la tabla transactions
        $sql_details = "INSERT INTO $table (client_id, amount, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?)";
        $stmt_details = $mysqli_kushki->prepare($sql_details);
        $stmt_details->bind_param("sssss", $client_id , $amount, $status, $created_at, $updated_at);
        // Ejecutar la consulta
        if ( $stmt_details->execute() === TRUE) {

            // Obtener el ID de la transacción insertada
            $id = $mysqli_kushki->insert_id;

            $rq['id'] = $id;
            $rq['client_id'] = $client_id;
            $rq['amount'] = $amount;
            $rq['status'] = $status;
            $rq['created_at '] = $created_at;
            $rq['updated_at'] = $updated_at;
        
            return $rq;  
        } 
    }elseif($insert_db['eject'] == "update"){

    }else{

    }

    
    

}




?>