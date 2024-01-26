<?php
$hola = $_SERVER['SERVER_ADDR'];
consolelogdata($hola);
include '../../env.php';
include '../../db.php';
?>
<?php

if(isset($_GET['data'])){
    $data = json_decode($_GET["data"], true);
    $value = $data['value'];
    $uniqueid = null;
    if($data['unique_id'] == '1234567890'){
        $uniqueid = md5(microtime().rand(0,1000));
    }
}
$key_payphone = env('TOKEN_PAYPHONE');
consolelogdata($data);
consolelogdata($value);
consolelogdata($uniqueid);
consolelogdata($key_payphone);


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
        
        document.addEventListener("DOMContentLoaded", () => {
            ppb = new PPaymentButtonBox({
                // Configuraciones de pago

                // Token obtenido desde la consola de desarrollador que identifica la empresa
                token: <?php echo $key_payphone; ?>,
                amount: <?php echo $value; ?>, // monto total de venta
                amountWithoutTax: <?php echo $value; ?>, // monto total que no cobra IVA
                amountWithTax: 0, // monto total que sí cobra IVA
                tax: 0, // monto del IVA
                service: 0, // Si existe monto por servicio
                tip: 0, // Si existe monto por propina

                // storeId: "", Identificador de la sucursal que cobra. Puedes obtener este campo desde la consola de Payphone Developer. Si lo envías se cobra con la sucursal indicada, si no lo envías se cobra con la sucursal matriz.

                reference: "Prueba Cajita de Pagos Payphone", // Referencia de pago
                clientTransactionId: <?php echo $uniqueid; ?>, // Id único. Debe cambiar para cada transacción
            }).render('pp-button');

        });
    </script>
</body>
</html>
<?php
function consolelogdata($data) {
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $pFunction = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'Unknown Function';

    echo '<script>';
    echo 'console.log("'. $pFunction . '");';
    echo 'console.log(": ", ' . json_encode($data) . ');';
    echo '</script>';
}   

?>
