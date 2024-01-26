<?php
$hola = $_SERVER['SERVER_ADDR'];
//consolelogdata($hola);
include '../../env.php';
include '../../db.php';
?>
<?php
if(isset($_GET['data'])){
    $data = json_decode($_GET["data"], true);
    $value = $data['value']*100;
    $uniqueid = null;
    if($data['unique_id'] == '1234567890'){
        $uniqueid = md5(microtime().rand(0,1000));
    }else{
        $uniqueid = $data['unique_id'];
    }
}
$key_payphone = env('TOKEN_PAYPHONE');
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
                token: "<?php echo $key_payphone; ?>",
                amount: <?php echo $value; ?>,
                amountWithoutTax: <?php echo $value; ?>,
                amountWithTax: 0,
                tax: 0,
                service: 0,
                tip: 0,
                reference: "Prueba Cajita de Pagos Payphone",
                clientTransactionId: "<?php echo $uniqueid; ?>",
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
