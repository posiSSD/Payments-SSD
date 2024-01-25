<?php
require 'env.php'; //desde la raiz a otro lado
require 'db.php';   
include ROOT_PATH.'/sys/helpers.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Título</title>
    
    <script type="text/javascript">
			var this_url = "<?php echo 'https://payments.totalbet.com/payphone/';?>";
			var user_id= <?php echo '1674627753';?>;
			var auth_token="<?php echo '0F042972D06A43D60C49525695F5EAB3';?>";
		</script>
    <script type="text/javascript" src="/payphone/js/jquery-3.6.0.min.js?1706194641"></script>	
    <script type="text/javascript" src="/payphone/js/bc_ws.js?1706194641"></script>
    <script type="text/javascript" src="/payphone/js/k.js?1706194641"></script>		
    <script type="text/javascript" src="cajitas.js?1706194641"></script>	
    


    <!-- Añade los enlaces CSS -->
    <link rel="stylesheet" href="https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.css">
    
    <!-- Añade el script principal -->
    <script type="module" src="https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.js"></script>
</head>
<body>
    <div id="pp-button"></div>

   
</body>
</html>
