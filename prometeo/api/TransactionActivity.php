<?php
    include '../env.php';
    include '../db.php';
    
function save_transaction_activity($data_activiy){

    $db = 'at_kushki';
    $table = 'activity_transactions';
    $connection = 'kushki_db';
    
    global $mysqli_kushki; // Asegúrate de que $mysqli_kushki esté disponible en este contexto

    $transaction_id = $data_activiy['transaction_id'];
    $user_id = $data_activiy['user_id'];
    $ip = $data_activiy['ip_address'];
    $url = url_insert();
    $method = $data_activiy['method'];
    $input_data = json_encode($_REQUEST);
    $http_code = $$data_activiy['http_code'];
    $result = json_encode($data_activiy['result']);
    $status = $data_activiy['status'];
    $token = $data_activiy['token'] ?? null;

    // Consulta SQL para insertar los datos en la tabla activity_transactions
    $sql_activity = "INSERT INTO $table (transaction_id, user_id, ip, url, method, request, http_code, result, status, token)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_activity = $mysqli_kushki->prepare($sql_activity);
    $stmt_activity->bind_param("ssssssssss", $transaction_id, $user_id, $ip, $url, $method, $input_data, $http_code, $result, $status, $token);

    if ($stmt_activity->execute() === TRUE) {
        return true; // Éxito en la inserción
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
