<?php
$hola = $_SERVER['SERVER_ADDR'];
include '../../env.php';
include '../../db.php';
?>
<?php
if(isset($_GET['data'])){
    $data = json_decode($_GET["data"], true);
    $value = $data['value']*100;
    $uniqueid = $data['unique_id'];
}
$key_payphone = env('TOKEN_PAYPHONE');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Título</title>

    <!-- Añade el script principal -->
    <script type=’module’ src=’https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.js’></script>

    <!-- Añade los enlaces CSS -->
    <link rel=’stylesheet’ href=’https://cdn.payphonetodoesposible.com/box/v1.1/payphone-payment-box.css’>
       
</head>
<body>
    <div id="pp-button"></div>

    <script>
        window.addEventListener("DOMContentLoaded", () => {
            ppb = new PPaymentButtonBox({
                token: "<?php echo $key_payphone; ?>",
                amount: <?php echo $value; ?>,
                amountWithoutTax: <?php echo $value; ?>,
                amountWithTax: 0,
                tax: 0,
                service: 0,
                tip: 0,
                reference: "Pagos Payphone",
                clientTransactionId: "<?php echo $uniqueid; ?>",
            }).render('pp-button');
        });
    </script>
</body>
</html>
