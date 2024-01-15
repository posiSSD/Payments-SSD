<?php
require 'env.php'; //desde la raiz a otro lado
require 'db.php';   








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
    $sql_details = "INSERT INTO $table (command, account, txn_id, url, response, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_details = $mysqli_kushkipayment->prepare($sql_details);
    $stmt_details->bind_param("sssssss", $command, $account, $txn_id, $url, $response, $created_at, $updated_at);

// Ejecutar la consulta
    if ( $stmt_details->execute() === TRUE) {

        // Obtener el ID de la transacción insertada
        $id = $mysqli_kushki->insert_id;

        $rq['id'] = $id;
        $rq['command'] = $command;
        $rq['account'] = $account;
        $rq['txn_id'] = $txn_id;
        $rq['url '] = $url;
        $rq['response'] = $response;
        $rq['created_at '] = $created_at;
        $rq['updated_at '] = $updated_at;
            
        consolelogdata($rq); 
        return $rq;  

    } 

}


function consolelogdata($data) {
    echo '<script>';
    echo 'console.log("Data:", ' . json_encode($data) . ');';
    echo '</script>';
}
?>