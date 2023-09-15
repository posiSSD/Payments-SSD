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
