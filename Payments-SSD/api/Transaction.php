<?php

function save_transaction($request, $txt_id, $type, $status){
    global $mysqli_kushki;
    $db = 'at_kushki';
    $table = 'transactions';
    $connection = 'kushki_db';

    /*
        0. Pendiente
        1. Enviado: (Enviado a BC)
        2. BC Fallo: (Api Nunca Responde)
        3. BC Ok
        4. BC Error
    */
    $rq = [];
     
    $type_transaction_id = $type;
    $txt_id = $txt_id??0;
    $amount  = $request['amount']??0;
    $shop_id = "0";
    $cashdesk_id  = "0";
    $user_id  = $request['account']??0;
    $rstatus  = $status;
    $created_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
    $updated_at = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');

    // Consulta SQL para insertar los datos en la tabla transactions
    $sql_details = "INSERT INTO $table (type_transaction_id, txt_id, amount, 
    shop_id, cashdesk_id, user_id, status, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_details = $mysqli_kushki->prepare($sql_details);
    $stmt_details->bind_param("sssssssss", $type_transaction_id, $txt_id, $amount, $shop_id,
                                    $cashdesk_id, $user_id, $rstatus, $created_at, $updated_at);
    // Ejecutar la consulta
    if ( $stmt_details->execute() === TRUE) {

        // Obtener el ID de la transacción insertada
        $id = $mysqli_kushki->insert_id;

        $rq['id'] = $id;
        $rq['type_transaction_id'] = $type_transaction_id;
        $rq['txt_id'] = $txt_id;
        $rq['amount'] = $amount;
        $rq['shop_id '] = $shop_id;
        $rq['cashdesk_id'] = $cashdesk_id;
        $rq['user_id'] = $user_id;
        $rq['status'] = $rstatus;
        $rq['created_at'] = $created_at;
        $rq['updated_at'] = $updated_at;
        //consolelogdata($rq); 

        return $rq;  
    } else {

        $errordb = $stmt_details->error;
        //consolelogdata($errordb);
        return false; // Error en la inserción
    }
}
    
?>