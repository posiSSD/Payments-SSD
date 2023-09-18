<?php
include 'prometeo/env.php';
include 'prometeo/db.php';

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud como JSON
    $payload = file_get_contents("php://input");

    // Verificar si el cuerpo de la solicitud es JSON válido
    $data = json_decode($payload, true);
    // grabar el token
    //$verifyToken = $data['verify_token'];


    if (json_last_error() === JSON_ERROR_NONE) {
        // Verificar si existe el campo "events" en los datos
        if (isset($data['events'][0])) {
            // Acceder a los valores del JSON
            $verifyToken = $data['verify_token'];
            $events = $data['events'][0]; // Tomar el primer elemento del array

            // Acceder a los campos dentro de "payload" y realizar una inserción
            $event_type = isset($events['event_type']) ? $events['event_type'] : null;
            $event_id = isset($events['event_id']) ? $events['event_id'] : null;
            $timestamp = isset($events['timestamp']) ? $events['timestamp'] : null;
            $payload = isset($events['payload']) ? $events['payload'] : null;
            $amount = isset($payload['amount']) ? $payload['amount'] : null;
            $concept = isset($payload['concept']) ? $payload['concept'] : null;
            $currency = isset($payload['currency']) ? $payload['currency'] : null;
            $origin_account = isset($payload['origin_account']) ? $payload['origin_account'] : null;
            $destination_account = isset($payload['destination_account']) ? $payload['destination_account'] : null;
            $destination_institution = isset($payload['destination_institution']) ? $payload['destination_institution'] : null;
            $branch = isset($payload['branch']) ? $payload['branch'] : null;
            $destination_owner_name = isset($payload['destination_owner_name']) ? $payload['destination_owner_name'] : null;
            $destination_account_type = isset($payload['destination_account_type']) ? $payload['destination_account_type'] : null;
            $document_type = isset($payload['document_type']) ? $payload['document_type'] : null;
            $document_number = isset($payload['document_number']) ? $payload['document_number'] : null;
            $destination_bank_code = isset($payload['destination_bank_code']) ? $payload['destination_bank_code'] : null;
            $mobile_os = isset($payload['mobile_os']) ? $payload['mobile_os'] : null;
            $request_id = isset($payload['request_id']) ? $payload['request_id'] : null;
            $intent_id = isset($payload['intent_id']) ? $payload['intent_id'] : null;

            // Crear una consulta SQL para insertar los datos en la tabla de la base de datos
            $sql = "INSERT INTO prometeo_transactions ( id_usuario,
                                                        event_type,
                                                        event_id,
                                                        timestamp,
                                                        amount,
                                                        concept,
                                                        currency,
                                                        origin_account,
                                                        destination_account,
                                                        destination_institution,
                                                        branch,
                                                        destination_owner_name,
                                                        destination_account_type,
                                                        document_type,
                                                        document_number,
                                                        destination_bank_code,
                                                        mobile_os,
                                                        request_id,
                                                        intent_id)
                                                VALUES ('1',
                                                        '$verifyToken',
                                                        '$event_type',
                                                        '$event_id',
                                                        '$timestamp',
                                                        '$amount',
                                                        '$concept',
                                                        '$currency',
                                                        '$origin_account',
                                                        '$destination_account',
                                                        '$destination_institution',
                                                        '$branch',
                                                        '$destination_owner_name',
                                                        '$destination_account_type',
                                                        '$document_type',
                                                        '$document_number',
                                                        '$destination_bank_code',
                                                        '$mobile_os',
                                                        '$request_id',
                                                        '$intent_id')";

            // Ejecutar la consulta
            if ($mysqli->query($sql) === TRUE) {

                http_response_code(200);
            } else {
                echo "Error al insertar el registro: " . $mysqli->error;
            }

            // Llamamos a la función consultaintent para obtener el external_id
            $external_id = consultaintent($intent_id);

            if ($external_id !== false) {
                // Utiliza sentencias preparadas para evitar inyección SQL
                $update_sql = "UPDATE prometeo_transactions SET external_id = '$external_id' 
                                WHERE intent_id = '$intent_id'";
        
                // Ejecutar la consulta de actualización
                if ($mysqli->query($update_sql) === TRUE) {
                    // Retorna true si la consulta se ejecuta con éxito
                    return true;
                } else {
                    return false;
                }
            }

            exit;

        } else {
            // Si no se encuentra el campo "events", responde con un error
            http_response_code(400);
            exit;
        }
    } else {
        // Si el cuerpo de la solicitud no es JSON válido, responde con un error
        http_response_code(400);
        exit;
    }
} else {
    // Si la solicitud no es POST, responde con un error
    http_response_code(405);
    exit;
}


function consultaintent($intent_id) {
    $apiUrl = 'https://payment.prometeoapi.net/api/v1/payment-intent/' . $intent_id;
    $apiKey = 'SKEyYnMt1OGIoMX0gpAy0xPJLrgh2e5p8jp3vGrZyjqO1wbuIJDKPuSHKxpIFynA';

    // Configuración de la solicitud cURL
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'X-API-Key: ' . $apiKey,
        'accept: application/json'
    ]);

    // Realizar la solicitud GET
    $response = curl_exec($curl);

    // Verificar si hubo errores
    if (curl_errno($curl)) {
        echo 'Error en la solicitud cURL: ' . curl_error($curl);
        return false; // Retorna false en caso de error
    } else {
        // Procesar la respuesta JSON
        $responseData = json_decode($response, true);
        $external_id = isset($responseData['external_id']) ? $responseData['external_id'] : null;

        return($external_id);
    }

    // Cerrar la sesión cURL
    curl_close($curl);
}

?>