<?php

$endpoint_url = "https://payments1.betconstruct.com";

try {
    // Realizar una solicitud GET al endpoint
    $response = file_get_contents($endpoint_url);

    if ($response === false) {
        throw new Exception($response);
    }

    // Manejar la respuesta, por ejemplo, imprimir el contenido
    echo "Respuesta del endpoint: " . $response;

} catch (Exception $e) {
    // Manejar la excepción
    echo "Excepción capturada: " . $e->getMessage();
}
?>
