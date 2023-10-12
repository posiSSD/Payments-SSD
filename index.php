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




<?php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "totalbet.ec") !== false) {
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

        <label>Método de Pago:</label>

        <!-- Tarjeta de Prometeo -->
        <div class="cardview" id="prometeoCard" data-metodo="prometeo">
            <h3>Prometeo</h3>
            <p>Descripción de Prometeo y detalles del método de pago.</p>
        </div>
    </div>

    <script>
        const baseUrl = window.location.protocol + '//' + window.location.host;

        
        const prometeoCard = document.getElementById("prometeoCard");
        
        prometeoCard.addEventListener("click", () => {
            const metodo = prometeoCard.getAttribute("data-metodo");

            const redirectUrl = "/prometeo/";

            window.location.href = redirectUrl;
            
            
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
        background-colecho "¡Hola mundo!";: 20px;
        border: 1px solid #888;
        width: 80%;
    }
    </style>

    </body>
    
    </html>
<?php

} else {
    // El usuario no viene de totalbet.ec, muestra un mensaje de despedida
    echo "Gracias por visitarnos. ¡Hasta luego!";
}
?>






<?php
/*
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "totalbet.ec") !== false) {
    // El usuario viene de totalbet.ec, muestra un saludo
    echo "¡Hola mundo!";
} else {
    // El usuario no viene de totalbet.ec, muestra un mensaje de despedida
    echo "Gracias por visitarnos. ¡Hasta luego!";
}
*/
?>
