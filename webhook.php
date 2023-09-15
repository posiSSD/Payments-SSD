<?php
include 'prometeo/env.php';
include 'prometeo/db.php';

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud como JSON
    $payload = file_get_contents("php://input");

    // Verificar si el cuerpo de la solicitud es JSON válido
    $data = json_decode($payload, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Verificar si existe el campo "events" en los datos
        if (isset($data['events'])) {
            // Acceder a los valores del JSON
            $verifyToken = $data['verify_token'];
            $events = $data['events'];

            // Serializar los eventos como JSON
            $eventsJson = json_encode($events);

            // Crear una consulta SQL para insertar los datos en la tabla de la base de datos
            $sql = "INSERT INTO prometeo_prueba (id_usuario, verify_token, events) 
                    VALUES ('1', '$verifyToken', '$eventsJson')";
            
            // Ejecutar la consulta
            if ($mysqli->query($sql) === TRUE) {
                echo "Registro insertado correctamente.";
            } else {
                echo "Error al insertar el registro: " . $mysqli->error;
            }
        } else {
            // Si no se encuentra el campo "events", responde con un error
            http_response_code(400);
            echo "Campo 'events' faltante en los datos";
            exit;
        }
    } else {
        // Si el cuerpo de la solicitud no es JSON válido, responde con un error
        http_response_code(400);
        echo "La solicitud no contiene datos JSON válidos";
        exit;
    }
} else {
    // Si la solicitud no es POST, responde con un error
    http_response_code(405);
    echo "Método no permitido";
    exit;
}

?>








