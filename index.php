<?php

//$auth_data = true;
//$auth_token = 'FAE2579BC8325A2F60B432173CEF4D77';
//$user_id = '3333200';
//$metodo = 'kushki';
//$metodo = 'prometeo';

// Construir la URL con los parámetros
//$url = 'http';
//$url .= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '');
//$url .= '://';
//$url .= (isset($_SERVER["HTTP_HOST"]) ? substr($_SERVER['HTTP_HOST'], 0) : "");
//$url .= "/kushki/";
//$url .= "/kushki/index.php";
// Agregar los parámetros a la URL
//$url .= "?auth_data=" . json_encode(array("auth_token" => $auth_token, "user_id" => $user_id, "metodo" => $metodo));
// Redireccionar al usuario a la URL
//header("Location: " . $url);
//exit; // Asegurarse de detener la ejecución del script después de la redirección
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>
    <!-- Agregar enlace al archivo CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados para centrar el formulario */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 400px;
        }
        .cardview {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .cardview:hover {
            border-color: #007bff;
        }
        .selected-card {
            border-color: #007bff;
        }
    </style>
</head>

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
            const redirectUrl = "/prometeo/";
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
            // const auth_token = "FAE2579BC8325A2F60B432173CEF4D77"; // Reemplaza con tu autenticación
            // const user_id = "3333200"; // Reemplaza con tu ID de usuario
            // const auth_data = JSON.stringify({ auth_token, user_id, metodo });
            // const iframeSrc = `${baseUrl}/prometeo/index.php?auth_data=${encodeURIComponent(auth_data)}`;
            // const redirectUrl = "/prometeo/";

            //const baseUrl = ""; // Reemplaza con la URL base adecuada
            const auth_token = "FAE2579BC8325A2F60B432173CEF4D77"; // Reemplaza con tu autenticación
            const user_id = "3333200"; // Reemplaza con tu ID de usuario
            const auth_data = JSON.stringify({ auth_token, user_id});
            
            //const redirectUrl = `${baseUrl}/prometeo/index.php?auth_data=${encodeURIComponent(auth_data)}`;
            const redirectUrl = `/Payments-SSD/prometeo/index.php?auth_data=${encodeURIComponent(auth_data)}`;
            window.location.href = redirectUrl;

            //// Abre la ventana emergente con el iframe
            //const popupWindow = window.open(iframeSrc, "_blank", "width=800,height=600");
            //window.location.href = redirectUrl;
            
            // Agrega una función para cerrar la ventana emergente cuando sea necesario
            //const checkPopupClosed = setInterval(() => {
            //    if (popupWindow.closed) {
            //        clearInterval(checkPopupClosed);
                    // Realiza acciones adicionales después de que se cierre la ventana emergente
            //    }
            //}, 1000);
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
    



</html>

