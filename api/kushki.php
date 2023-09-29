<?php

function paymente_kushki($request)
    {
        //CALL FUNCTION KUSHKI PAYMENT
       
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add([
            'account' => $request->account,
            'amount'  => $request->amount,
        ]);

        $response = KushkiPayment::payment_deposit($myRequest);
        
        Log::info('WEB FUNCTION KUSHKI');
        Log::info($response);

        if($response['http_code'] == 200)
        {
            
            $transaction = Transaction::save_transaction($request,$response['result']['txn_id'],$type=3,$status=3);

            $data_activiy = [
                'transaction_id' => $transaction->id,
                'http_code' 	 => '200',
                'result'		 =>  $response['result'],
                'status' 		 => '3'
            ];
            $webTransaction = TransactionActivity::save_transaction_activity($data_activiy);


            $response = [
                'created'    => date("Y-m-d H:i:s", strtotime($webTransaction->created_at)),
                'trx_id' 	 => $response['result']['txn_id'],
                'account'    => $request->account,
                'amount' 	 => $request->amount
            ];

            return ['http_code' => 200, 'status' => 'Ok', 'result' =>  $response];
        }
        
        else if($response['http_code'] == 400)
        {
            $transaction = Transaction::save_transaction($request,$response['result']['txn_id'], $type=3,$status=4);

            $data_activiy = [
                'transaction_id' => $transaction->id,
                'http_code' 	 => '400',
                'result'		 =>  $response['result'],
                'status' 		 => '4'
            ];
            TransactionActivity::save_transaction_activity($data_activiy);

            return ['http_code' => 400, 'status' => 'Error', 'result' => 'recharge denied'];
        }

        else if($response['http_code'] == 408)
        {
            $transaction = Transaction::save_transaction($request,$response['result'],$type=3,$status=5);

            $data_activiy = [
                'transaction_id' => $transaction->id,
                'http_code' 	 => '408',
                'result'		 =>  $response['result'],
                'status' 		 => '5'
            ];

            TransactionActivity::save_transaction_activity($data_activiy);

            return ['http_code' => 408, 'status' => 'Error', 'result' => 'API timeout'];
        }
    }

?>