<?php

include ROOT_PATH.'/Payments-SSD/api/Transaction.php';
include ROOT_PATH.'/Payments-SSD/api/TransactionActivity.php';
include ROOT_PATH.'/Payments-SSD/api/payphonePayment.php'; //cambiarlo

function paymente_bc($request){

    //consolelogdata($request);
    $myRequest = [];
    $myRequest['setMethod'] = 'POST';
    $myRequest['request'] = [
        "account" => $request['account'],
        "amount" => $request['amount']
    ];
    $myRequest['payment_method'] = $request['payment_method'];
    //////
    $myRequest['order_id'] = $request['order_id'];
    //////

    ///prueba
    //consolelogdata($request);

    $response = payment_deposit($myRequest);

    //'0', 'Pendiente'
    //'1', 'Enviado BC'
    //'2', 'BC Fallo (API no Responde)''
    //'3', 'BC Ok', 
    //'4', 'BC Error', 
    //'5', 'Pendiente Anular', 
    //'6', 'Anulado', 

    if($response['http_code'] == 200){
            
        $transaction = save_transaction($request,$response['result']['txn_id'],$type=3,$status=3);      
        $data_activiy = [
            'transaction_id' => $transaction['id'],
            'http_code' 	 => '200',
            'result'		 =>  $response['result'],
            'status' 		 => '3',
            'ip_address'      => $request['ip_address'],
            'method'         => $request['payment_method']
        ]; 
        
        $webTransaction = save_transaction_activity($data_activiy);
        $response = [
            'created'    => date("Y-m-d H:i:s", strtotime($webTransaction['created_at'])),
            'trx_id' 	 => $response['result']['txn_id'],
            'account'    => $response['result']['account'],
            'amount' 	 => $response['result']['amount']
        ];

        //consolelogdata($response);
        return ['http_code' => 200, 'status' => 'Ok', 'result' =>  $response];
        
    } else if ($response['http_code'] == 400){

        $transaction = save_transaction($request,$response['result']['txn_id'],$type=3,$status=4);
        $data_activiy = [
            'transaction_id' => $transaction['id'],
            'http_code' 	 => '400',
            'result'		 =>  $response['result'],
            'status' 		 => '4',
            'ip_address'      => $request['ip_address'],
            'method'         => $request['payment_method'],
            'account'         => $request['request']['account']
        ];

        save_transaction_activity($data_activiy);
        return ['http_code' => 400, 'status' => 'Error', 'result' => 'Recharge Denied'];

    } else if ($response['http_code'] == 408){

        $transaction = save_transaction($request,$myRequest['order_id'],$type=3,$status=2);
        $data_activiy = [
            'transaction_id' => $transaction['id'],
            'http_code' 	 => '408',
            'result'		 =>  $response['result'],
            'status' 		 => '2',
            'ip_address'      => $request['ip_address'],
            'method'         => $request['payment_method'],
            'account'         => $request['request']['account']
        ];

        save_transaction_activity($data_activiy);
        return ['http_code' => 408, 'status' => 'Error', 'result' => 'API Timeout'];
    }
    /*
    else {

        $transaction = save_transaction($request,$response['result'],$type=3,$status=0);
        $data_activiy = [
            'transaction_id' => $transaction['id'],
            'http_code' 	 => '408',
            'result'		 =>  $response['result'],
            'status' 		 => '0',
            'ip_address'      => $request['ip_address'],
            'method'         => $request['payment_method']
        ];

        save_transaction_activity($data_activiy);
        return ['http_code' => $response['result']['code'], 'status' => 'pendiente', 'result' => $response['result']['message']];
    }
    */
}
?>