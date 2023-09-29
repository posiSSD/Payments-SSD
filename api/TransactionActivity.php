<?php

    
    $db = 'kushki_db';
    $table = 'activity_transactions';
    $connection = 'kushki_db';


    function save_transaction_activity($request)
	{
		return TransactionActivity::create([

			'transaction_id'=> $request['transaction_id'],
			'user_id'		=> auth()->user()->id,
            'ip' 			=> request()->ip(),
            'url' 			=> request()->fullUrl(),
            'method' 		=> request()->method(),
            'request' 		=> json_encode(request()->input()),
            'http_code' 	=> $request['http_code'],
			'result'		=> json_encode($request['result']) ?? "",
            'status' 		=> $request['status'],
			'token'			=> auth()->user()->token() ? auth()->user()->token()->name : null
        ]);
	}



?>


