<?php

include ROOT_PATH.'/Payments-SSD/api/Transaction.php';
include ROOT_PATH.'/Payments-SSD/api/TransactionActivity.php';
include ROOT_PATH.'/Payments-SSD/api/payphonePayment.php';

function paymente_bc($request){
    // $request=[];
    // $request['account'];
    // $request['amount'];
    // $request['order_id'];
    // $request['payment_method']
    // $request['ip_address'];
    // 

    $myRequest = [];
    $myRequest['setMethod'] = 'POST';
    $myRequest['request'] = [
        "account" => $request['account'],
        "amount" => $request['amount']
    ];
    $myRequest['payment_method'] = $request['payment_method'];

    

    //codigo de pago         
    $response = payment_deposit($myRequest);

    
    

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


        return ['http_code' => 200, 'status' => 'Ok', 'result' =>  $response];
        
    } else if ($response['http_code'] == 400){

        $transaction = save_transaction($request,$response['result']['txn_id'], $type=3,$status=4);

        $data_activiy = [
            'transaction_id' => $transaction->id,
            'http_code' 	 => '400',
            'result'		 =>  $response['result'],
            'status' 		 => '4'
        ];
        save_transaction_activity($data_activiy);

        return ['http_code' => 400, 'status' => 'Error', 'result' => 'recharge denied'];

    } else if ($response['http_code'] == 408){

        $transaction = save_transaction($request,$response['result'],$type=3,$status=5);

        $data_activiy = [
            'transaction_id' => $transaction->id,
            'http_code' 	 => '408',
            'result'		 =>  $response['result'],
            'status' 		 => '5'
        ];

        save_transaction_activity($data_activiy);

        return ['http_code' => 408, 'status' => 'Error', 'result' => 'API timeout'];

    }
}


?>