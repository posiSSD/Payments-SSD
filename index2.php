<body>
<div class="container">
        <h1 class="text-center">Payments.TB.app</h1>

        <!-- Elimina los campos ocultos de autenticación -->
        <!-- <input type="hidden" id="auth_token" name="auth_token" value="FAE2579BC8325A2F60B432173CEF4D77">
        <input type="hidden" id="user_id" name="user_id" value="3333200"> -->

        <label>Método de Pago:</label>

        <!-- Tarjeta de Kushki -->
        <div class="cardview" id="kushkiCard" data-metodo="kushki">
            <h3>Kushki</h3>
            <p>Descripción de Kushki y detalles del método de pago.</p>
        </div>

        <!-- Tarjeta de Prometeo -->
        <div class="cardview" id="prometeoCard" data-metodo="prometeo">
            <h3>Prometeo</h3>
            <p>Descripción de Prometeo y detalles del método de pago.</p>
        </div>
    </div>

    <script>
        const baseUrl = window.location.protocol + '//' + window.location.host;

        const kushkiCard = document.getElementById("kushkiCard");
        const prometeoCard = document.getElementById("prometeoCard");

        kushkiCard.addEventListener("click", () => {
            const metodo = kushkiCard.getAttribute("data-metodo");
            
            // Construye la URL para cargar en la ventana emergente
            const auth_token = "FAE2579BC8325A2F60B432173CEF4D77"; // Reemplaza con tu autenticación
            const user_id = "3333200"; // Reemplaza con tu ID de usuario
            const auth_data = JSON.stringify({ auth_token, user_id, metodo });
            const iframeSrc = `${baseUrl}/kushki/index.php?auth_data=${encodeURIComponent(auth_data)}`;
            
            // Abre la ventana emergente con el iframe
            const popupWindow = window.open(iframeSrc, "_blank", "width=800,height=600");
            
            // Agrega una función para cerrar la ventana emergente cuando sea necesario
            const checkPopupClosed = setInterval(() => {
                if (popupWindow.closed) {
                    clearInterval(checkPopupClosed);
                    // Realiza acciones adicionales después de que se cierre la ventana emergente
                }
            }, 1000);
        });

        prometeoCard.addEventListener("click", () => {
            const metodo = prometeoCard.getAttribute("data-metodo");

            // Construye la URL para cargar en la ventana emergente
            const auth_token = "FAE2579BC8325A2F60B432173CEF4D77"; // Reemplaza con tu autenticación
            const user_id = "3333200"; // Reemplaza con tu ID de usuario
            const auth_data = JSON.stringify({ auth_token, user_id, metodo });
            const iframeSrc = `${baseUrl}/prometeo/index.php?auth_data=${encodeURIComponent(auth_data)}`;
            
            // Abre la ventana emergente con el iframe
            const popupWindow = window.open(iframeSrc, "_blank", "width=800,height=600");
            
            // Agrega una función para cerrar la ventana emergente cuando sea necesario
            const checkPopupClosed = setInterval(() => {
                if (popupWindow.closed) {
                    clearInterval(checkPopupClosed);
                    // Realiza acciones adicionales después de que se cierre la ventana emergente
                }
            }, 1000);
        });
    </script>


    <!-- Agrega esto al final de tu página principal, justo antes de cerrar el body -->
    <div id="paymentModal" class="modal">
    <div class="modal-content">
        <!-- Contenido de tu iframe o popup -->
        <iframe id="paymentIframe" name="mi_iframe" frameborder="0" width="100%" height="600"></iframe>
    </div>
    </div>

    <style>
    /* Estilos para el modal */
    .modal {
        display: none; /* Oculta el modal por defecto */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }
    </style>

</body>



<body>
    <div class="container">
        <h1 class="text-center">Payments.TB.app</h1>
        
        <form id="paymentForm" action="process_payment.php" method="POST">
            <input type="text" id="user_id" name="user_id" value="3333200" required class="form-control mb-3" hidden>
            
            <label>Método de Pago:</label>
            
            
            <div class="cardview" id="kushkiCard">
                <input type="hidden" name="metodo" value="kushki">
                <h3>Kushki</h3>
                <p>Descripción de Kushki y detalles del método de pago.</p>
            </div>
            
            
            <div class="cardview" id="prometeoCard">
                <input type="hidden" name="metodo" value="prometeo">
                <h3>Prometeo</h3>
                <p>Descripción de Prometeo y detalles del método de pago.</p>
            </div>
            
            <input type="hidden" name="auth_token" value="FAE2579BC8325A2F60B432173CEF4D77" hidden>
        </form>
    </div>
    
    <script>
        // Manejo de selección de tarjetas
        const kushkiCard = document.getElementById("kushkiCard");
        const prometeoCard = document.getElementById("prometeoCard");

        kushkiCard.addEventListener("click", () => {
            kushkiCard.classList.add("selected-card");
            prometeoCard.classList.remove("selected-card");
            document.getElementById("paymentForm").submit();
        });

        prometeoCard.addEventListener("click", () => {
            prometeoCard.classList.add("selected-card");
            kushkiCard.classList.remove("selected-card");
            document.getElementById("paymentForm").submit();
        });
    </script>
</body>


<script>
        var widget = Prometeo.init('aa2b08c8-b9e1-4fb2-a971-c3ec850c5692');
        // const redirectUrl = $url;
		// Llama a openWidget cuando se carga el documento
        document.addEventListener('DOMContentLoaded', function () {
			console.log("cargando el widget");
            openWidget();
        });

        widget.on(Prometeo.Messaging.CLOSE, () => {
            console.log('Event: CLOSE');
        });

        widget.on(Prometeo.Messaging.LOGIN, (session) => {
            console.log('Event: LOGIN');
            session.getOwnerInfo();
            session.getAccounts();
        });

        widget.on(Prometeo.Messaging.GET_OWNER_INFO, (ownerInfo) => {
            console.log('Event: GET_OWNER_INFO');
            console.log(`ownerInfo: ${ownerInfo}`);
        });

        widget.on(Prometeo.Messaging.GET_ACCOUNTS, (accounts) => {
            console.log('Event: GET_ACCOUNTS');
            console.log(`accounts: ${accounts}`);
        });

        widget.on(Prometeo.Messaging.ERROR, (error) => {
            console.log('Event: ERROR');
            console.log(`error: ${error}`);
        });

        widget.on(Prometeo.Messaging.PAYMENT_SUCCESS, (payload) => {
			console.log('Event: PAYMENT_SUCCESS');
			console.log(`payload: ${payload}`);
			console.log(JSON.stringify(payload, null, 2)); // Convierte el objeto a una cadena JSON formateada
		});       

        const openWidget = () => {
            widget.open({
                allowedFeatureLevel: 2,
                currency: 'PEN',
                amount: 1,
                concept: "Order 1234"
            });
        }
		
    </script>



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
            $externalid = isset($payload['external_id']) ? $payload['external_id'] : null;
            // Crear una consulta SQL para insertar los datos en la tabla de la base de datos
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
                                                        '$intent_id',
                                                        '$externalid')";

            // Ejecutar la consulta
            if ($mysqli->query($sql) === TRUE) {

                http_response_code(200);
            } else {
                echo "Error al insertar el registro: " . $mysqli->error;
            }

            if($externalid !==  null || $externalid !== ""){
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
            }
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////