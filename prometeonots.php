<?php
/*include 'prometeo/env.php';
include 'prometeo/db.php';
include 'prometeo/sys/helpers.php';
//include '/sys/helpers.php';*/
//include '/api/KushkiController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tu código para procesar los datos

    // Simular un error
    http_response_code(500); // Código 500 para error interno del servidor
    echo json_encode(["error" => "Ha ocurrido un error en el servidor"]);
} else {
    // La solicitud no es de tipo POST
    http_response_code(400); // Código 400 para solicitud incorrecta
    echo json_encode(["error" => "La solicitud debe ser de tipo POST"]);
}


?>