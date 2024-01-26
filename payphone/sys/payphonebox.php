<?php
include '../../env.php';
include '../../db.php';
include 'helpers.php';
?>
<?php

if(isset($_GET['data'])){
    $data = json_decode($_GET["data"], true);
    $kushki_value = $data['kushki_value'];
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Título</title>
    <!-- Añade los enlaces CSS -->
    <link rel="stylesheet" href="https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.css">
    
    <!-- Añade el script principal -->
    <script type="module" src="https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.js"></script>
</head>
<body>
    <div id="pp-button"></div>

    <script>
        function generateUniqueId() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            ppb = new PPaymentButtonBox({
                // Configuraciones de pago

                // Token obtenido desde la consola de desarrollador que identifica la empresa
                token: env('BC_PAYPHONE_SECRET_KEY'),

                // Monto a cobrar: Debe cumplir la siguiente regla
                // Amount = amountWithoutTax + AmountWithTax + AmountWithTax + Tax + service + tip
                // Todos los valores se multiplican por 100, es decir $1 = 100, $15.67 = 1567
                amount: $kushki_value, // monto total de venta
                amountWithoutTax: $kushki_value, // monto total que no cobra IVA
                amountWithTax: 0, // monto total que sí cobra IVA
                tax: 0, // monto del IVA
                service: 0, // Si existe monto por servicio
                tip: 0, // Si existe monto por propina

                // storeId: "", Identificador de la sucursal que cobra. Puedes obtener este campo desde la consola de Payphone Developer. Si lo envías se cobra con la sucursal indicada, si no lo envías se cobra con la sucursal matriz.

                reference: "Prueba Cajita de Pagos Payphone", // Referencia de pago
                clientTransactionId: generateUniqueId(), // Id único. Debe cambiar para cada transacción
            }).render('pp-button');

        });
    </script>
</body>
</html>
