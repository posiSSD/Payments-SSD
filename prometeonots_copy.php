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
        if(isset($data)) {
            $id_usuario = "1";
            $verifyToken = isset($data['verify_token']) ? $data['verify_token'] : null;

            $events = $data['events'][0];
            //$events = isset($data['events']) ? $data['events'] : null;
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
            $externalid = isset($payload['external_id']) ? $payload['external_id'] : null;

            insert_bd($mysqli, $data);

            /*$bandera_select = select_bd($mysqli, $request_id);
            if($bandera_select == true ){
                update_bd($mysqli, $data);
            }else{
                insert_bd($mysqli, $data);
            }*/
            if($externalid == null){
                $externalid_consult = consultaintent($intent_id);
                //$data['events']['payload']['external_id'] = $externalid_consult;
                update_bd($mysqli, $intent_id, $externalid_consult);
                http_response_code(200);
            }

        }
    }
}

/*function select_bd($mysqli, $request_id){
    
    $sql_buscar_request_id = "SELECT * FROM prometeo_transactions WHERE request_id = ?";
    $stmt = $mysqli->prepare($sql_buscar_request_id);
    $stmt->bind_param("s", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }

}*/
function insert_bd($mysqli, $data){
    $id_usuario = "1";
    $verifyToken = isset($data['verify_token']) ? $data['verify_token'] : null;

    $events = $data['events'][0];
    //$events = isset($data['events']) ? $data['events'] : null;
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
    $externalid = isset($payload['external_id']) ? $payload['external_id'] : null;


    // Sentencia de ingresar datos.
    $sql = "INSERT INTO prometeo_transactions ( id_usuario,
                                                verify_token, 
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
                                                intent_id,
                                                external_id)
                                         VALUES ('$id_usuario',
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
                                                '$intent_id',
                                                '$externalid')";
                // Ejecutar la consulta
                if ($mysqli->query($sql) === TRUE) {
                    echo "Datos ingresados";
                    http_response_code(200);
                } else {
                    echo "Datos Error: " . $mysqli->error;
                }
    
}
/*
function update_bd($mysqli, $data){
    $id_usuario = "1";
    $verifyToken = isset($data['verify_token']) ? $data['verify_token'] : null;

    $events = $data['events'][0];
    //$events = isset($data['events']) ? $data['events'] : null;
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
    $externalid = isset($payload['external_id']) ? $payload['external_id'] : null;

    $sql = "UPDATE prometeo_transactions 
            SET
            id_usuario = '$id_usuario',
            verify_token = '$verifyToken',
            event_type = '$event_type',
            timestamp = '$timestamp',
            amount = '$amount',
            concept = '$concept',
            currency = '$currency',
            origin_account = '$origin_account',
            destination_account = '$destination_account',
            destination_institution = '$destination_institution',
            branch = '$branch',
            destination_owner_name = '$destination_owner_name',
            destination_account_type = '$destination_account_type',
            document_type = '$document_type',
            document_number = '$document_number',
            destination_bank_code = '$destination_bank_code',
            mobile_os = '$mobile_os',
            intent_id = '$intent_id',
            external_id = '$externalid'
            WHERE request_id = '$request_id'";
    // Ejecutar la consulta de actualización
    if ($mysqli->query($sql) === TRUE) {
        echo "Datos actualizados";
        http_response_code(200);
    } else {
        echo "Error al actualizar el registro: " . $mysqli->error;
    }
}
*/
function update_bd($mysqli, $intent_id, $externalid_consult){
    $sql = "UPDATE prometeo_transactions set external_id = '$$externalid_consult'
            WHERE intent_id = '$intent_id'";
    if ($mysqli->query($sql) === TRUE) {
        echo "External_id actualizados";
    } else {
        echo "External_id Error: " . $mysqli->error;
    }        
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
        if(!empty($external_id) || !is_null($external_id)){
        //if($external_id !== "" || $external_id !== null){
            echo "Valor de external_id: " . $external_id;
        }else{
            echo "Valor de external_id: " . $external_id;
        }
        return($external_id);
    }

    // Cerrar la sesión cURL
    curl_close($curl);
}

?>