<?php
    
function save_transaction_activity($data_activiy){
    global $mysqli_kushki;
    
    $db = 'at_kushki';
    $table = 'activity_transactions';
    $connection = 'kushki_db';
    $rq = [];

    $transaction_id = $data_activiy['transaction_id'];
    $user_id = $data_activiy['result']['account'];
    $ip = $data_activiy['ip_address'];
    $url = url_insert();
    $method = $data_activiy['method'];
    $input_data = json_encode($data_activiy);
    $http_code = $data_activiy['http_code'];
    $result = json_encode($data_activiy['result']);
    $status = $data_activiy['status'];
    $token = $data_activiy['token'] ?? 0;
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    // Consulta SQL para insertar los datos en la tabla activity_transactions
    $sql_activity = "INSERT INTO $table (transaction_id, user_id, ip, url, method, request, http_code, result, status, token, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_activity = $mysqli_kushki->prepare($sql_activity);
    $stmt_activity->bind_param("ssssssssssss", $transaction_id, $user_id, $ip, $url, $method, $input_data, $http_code, $result, $status, $token, $created_at, $updated_at);

    if ($stmt_activity->execute() === TRUE) {

        $id = $mysqli_kushki->insert_id;

        $rq['id'] = $id;
        $rq['transaction_id'] = $transaction_id;
        $rq['user_id'] = $user_id;
        $rq['ip'] = $ip;
        $rq['url'] = $url;
        $rq['method'] = $method;
        $rq['request'] = $input_data;
        $rq['http_code'] = $http_code;
        $rq['result'] = $result;
        $rq['status'] = $status;
        $rq['token'] = $token;
        $rq['created_at'] = $created_at;
        $rq['updated_at'] = $updated_at;

        consolelogdata($rq); 

        return $rq; // Éxito en la inserción

    } else {
        return false; // Error en la inserción
    }
}
function url_insert() {

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = strtok($_SERVER['REQUEST_URI'], '?'); // Obtiene la parte de la URL antes del signo de interrogación
    $url = $protocol . '://' . $host . $uri;

    return $url;
}
