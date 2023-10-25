<?php
include '/Transaction.php';
include '/TransactionActivity.php';
include '/kushki.php';

function deposit_prometeo($request){

    // Realiza una comprobación de las direcciones IP permitidas aquí
    //$ipAddress = $_SERVER['REMOTE_ADDR'];
    // $d=[];
    // $d['account']=$trans['client_id'];
    // $d['amount']=$trans['amount'];
    // $d['order_id']=$trans['order_id'];

    $request['ip_address'] = $_SERVER['REMOTE_ADDR'];
    $validator = validateRequest($request);

    if ($validator !== true){
        //INSERT TRANSACCTION
        $transaction = save_transaction($request, $txt_id=0, $type=3, $status=0);

        //INSERT ACTIVITY TRANSACTION
        $data_activiy = [
            'transaction_id' => $transaction['id'],
            'http_code' 	 => '422',
            'result'		 => $validator,
            'status' 		 => '0',
            'user_id' 		 => $request['account'],
            'REMOTE_ADDR' 	 => $request['ip_address'],
            'method ' 		 => 'No method'
        ];
         //INSERT TRANSACCTION ACTIVITY
        save_transaction_activity($data_activiy);

        return ['http_code' => 422, 'status' => 'Error', 'result' => $validator];

    }

    $response = paymente_kushki($request);
    //auth()->user()->addActivity($response);
	return $response; 

}

function validateRequest($request) {
    $errors = "";

    // Validación del campo 'ip_address, // Validación del campo 'account', // Validación del campo 'amount'
    // comando en terminal de ubuntu para saber tu ip curl ifconfig.me
    $validIPs = ["45.169.92.244", "200.107.154.26","190.223.60.40"];
    if (!isset($request['ip_address']) || !in_array($request['ip_address'], $validIPs)) {
        $errors = "El campo 'ip_address' es inválido: ".$request['ip_address'];
    }elseif(!isset($request['account']) || !is_numeric($request['account']) || $request['account'] <= 0){
        $errors = "El campo 'account' no es numerico: ".$request['account'];
    }elseif(!isset($request['amount'])){
        if(!is_numeric($request['amount'])){
            $errors = "El campo 'amount' no es numerico: ".$request['account'];
        }elseif($request['amount'] < 1 || $request['amount'] > 500){
            $errors = "El campo 'amount' no esta entra la cantidad correcta ".$request['account'];
        }
    }else{
        $errors = true;
    }

    return $errors;
}


?>