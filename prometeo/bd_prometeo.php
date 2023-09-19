<?php
include '/var/www/payments.apuestatotal.app/prometeo/env.php';
include '/var/www/payments.apuestatotal.app/prometeo/db.php';


if(isset($_POST["save_prometeo"])){
    $json_data = $_POST["save_prometeo"];
    $data = json_decode($json_data, true);

    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        $expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $id = isset($data['id']) ? $data['id'] : null;
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : null;
        $link = isset($data['url']) ? $data['url'] : null;

        // Utiliza sentencias preparadas para evitar inyección SQL
        $sql = "INSERT INTO prometeo_details (expires_at, id, qr_code, url)
                VALUES ('$expires_at', '$id', '$qr_code', '$link')";

        // Ejecutar la consulta
        if ($mysqli->query($sql) === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Error al insertar el registro: " . $mysqli->error));
        }

     
    }  
}

if(isset($_POST["edit_prometeo"])){
    $json_data = $_POST["edit_prometeo"];
    $data = json_decode($json_data, true);

    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        /*$id = isset($data['id']) ? $data['id'] : null;
        $expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : null;
        $link = isset($data['url']) ? $data['url'] : null;

        $callback_url = isset($data['callback_url']) ? $data['callback_url'] : null;
        $created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        $amount = isset($data['amount']) ? $data['amount'] : null;
        $concept = isset($data['concept']) ? $data['concept'] : null;
        $currency = isset($data['currency']) ? $data['currency'] : null;
        $external_id = isset($data['external_id']) ? $data['external_id'] : null;
        $intent_id = isset($data['intent_id']) ? $data['intent_id'] : null;
    
        $payment_link_type = isset($data['payment_link_type']) ? $data['payment_link_type'] : null;
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $return_url = isset($data['return_url']) ? $data['return_url'] : null;
        $reusable = isset($data['reusable']) ? $data['reusable'] : null;
        $status = isset($data['status']) ? $data['status'] : null;*/

        // Asignar valores a los parámetros
        $expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : null;
        $link = isset($data['url']) ? $data['url'] : null;

        $callback_url = isset($data['callback_url']) ? $data['callback_url'] : null;
        $created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        $amount = isset($data['payment_data']['amount']) ? $data['payment_data']['amount'] : null;
        $concept = isset($data['payment_data']['concept']) ? $data['payment_data']['concept'] : null;
        $currency = isset($data['payment_data']['currency']) ? $data['payment_data']['currency'] : null;
        $external_id = isset($data['payment_data']['external_id']) ? $data['payment_data']['external_id'] : null;
        $intent_id = isset($data['payment_data']['intent_id']) ? $data['payment_data']['intent_id'] : null;

        $payment_link_type = isset($data['payment_link_type']) ? $data['payment_link_type'] : null;
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $return_url = isset($data['return_url']) ? $data['return_url'] : null;
        $reusable = isset($data['reusable']) ? $data['reusable'] : null;
        $status = isset($data['status']) ? $data['status'] : null;
        $id = isset($data['id']) ? $data['id'] : null;

        // Utiliza sentencias preparadas para evitar inyección SQL
        $sql = "UPDATE prometeo_details SET
                expires_at = '$expires_at',
                qr_code = '$qr_code',
                url = '$link',

                callback_url = '$callback_url',
                created_at = '$created_at',
                email = '$email',

                amount = '$amount',
                concept = '$concept',
                currency = '$currency',
                external_id = '$external_id',
                intent_id = '$intent_id',

                payment_link_type = '$payment_link_type',
                product_id = '$product_id',
                return_url = '$return_url',
                reusable = '$reusable',
                status = '$status'
                WHERE id = '$id'";

        // Ejecutar la consulta de actualización
        if ($mysqli->query($sql) === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Error al insertar el registro: " . $mysqli->error));
        }
    }  
}


if(isset($_POST["select_prometeo"])){
    $json_data = $_POST["select_prometeo"];
    $data = json_decode($json_data, true);
    $payment = "payment.error";

    // Verificar que los datos se decodificaron correctamente
    if ($data !== null) {
        $intent_id = isset($data['intent_id']) ? $data['intent_id'] : null;
        $external_id = isset($data['external_id']) ? $data['external_id'] : null;

        // Utiliza sentencias preparadas para evitar inyección SQL
        $sql = "SELECT * FROM prometeo_transactions
                WHERE external_id='$external_id' && event_type != '$payment'";

        // Ejecutar la consulta
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // Si se encontraron resultados, significa que existe una fila con los valores proporcionados
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "No se encontraron resultados."));
        }
    }  
}

?>
