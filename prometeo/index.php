<?php 
//require 'env.php';
require '/var/www/html/prometeo/env.php'; //amazon instace
//require 'db.php';
require '/var/www/html/prometeo/db.php'; //amazon instace
//include '/var/www/gestion/sys/helpers.php';
//include '../sys/helpers.php';
include '/var/www/html/sys/helpers.php'; //amazon instace


$test_users = [];
	$test_users[]="3333200"; //mllaguno
	$test_users[]="132328430"; //andrea
	$test_users[]="2926797"; //gonzalo
	$test_users[]="102826577"; //merino zw
	$test_users[]="119273784"; //merino peru
	$test_users[]="120387760"; //tania
	$test_users[]="3586027"; //helpdesk

// print_r($_SERVER); exit();
$fv=time();

$url = 'http';
$url.= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'');
$url.= '://';
$url.= (isset($_SERVER["HTTP_HOST"]) ? substr($_SERVER['HTTP_HOST'],0):"");
//$url.= "/Payments-SSD/prometeo/";
$url.= "/prometeo/";
//$url.= "/kushki/index.php";

echo "la dir es: ".$url;

$log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
$log_file = date("Y-m-d").".log";
log_init($log_dir,$log_file);

$visit = [];
$visit["init"]=date("Y-m-d H:i:s");
$visit['ip']=$_SERVER['REMOTE_ADDR'];
$visit['device']=$_SERVER['HTTP_USER_AGENT'];
$visit['ref']=(array_key_exists('HTTP_REFERER', $_SERVER)?$_SERVER['HTTP_REFERER']:'direct');
$visit['url']=$_SERVER['REQUEST_URI'];

$visit_insert = "INSERT INTO 
					tbl_visits 
					(url,ip,server_info) 
					VALUES ('".$visit["url"]."','".$visit['ip']."','".print_r($_SERVER,true)."')";
$mysqli->query($visit_insert);
if($mysqli->error){
	echo $mysqli->error; die; 
}
$mysqli->close();

//$auth_data = true;
//$auth_token = 'FAE2579BC8325A2F60B432173CEF4D77';
//$user_id = '3333200';
$auth_data = null;
if(isset($_GET['auth_data'])){
	// [auth_token] => FAE2579BC8325A2F60B432173CEF4D77
	// [user_id] => 3333200
	// [avatarUrl] => https://static.springbuilder.site/assets/addon/avatar.png	
	$auth_data = json_decode($_GET["auth_data"],true);
	$auth_token = $auth_data["auth_token"]; /////TOKEN
	$user_id = $auth_data["user_id"];
	$visit["auth_data"]=$auth_data;
}

echo "         auth_data = ".$auth_token." ".$user_id;
if($auth_data){
	if(in_array($user_id, $test_users)){
		?>
		<script type="text/javascript">
			var this_url = "<?php echo $url;?>";
			var user_id=<?php echo $user_id;?>;
			var auth_token="<?php echo $auth_token;?>";
		</script>
		<script type="text/javascript" src="<?php echo $url;?>js/jquery-3.6.0.min.js?<?php echo $fv;?>"></script>
			
		
		<script type="text/javascript" src="<?php echo $url;?>js/bc_ws.js?<?php echo $fv;?>"></script>
		<script type="text/javascript" src="<?php echo $url;?>js/k.js?<?php echo $fv;?>"></script>
		
		
		<?php
	}else{
		$auth_data=false;
	}
}


log_write($visit);
if($auth_data){
	$payment_limits=explode(',', env('DEPOSIT_LIMITS'));
		$payment_limits['min']=number_format($payment_limits[0],0);
		$payment_limits['max']=number_format($payment_limits[1],0);
	?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>prometeo.app</title>
    <link rel="stylesheet" href="<?php echo $url; ?>css/k.css">
    <link rel="stylesheet" href="<?php echo $url; ?>css/new.scss">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
	<div id="msg" style="font-style: italic;"></div>

	<p class="text-start margin-title">
		<span>Mínimo S/<?php echo $payment_limits['min'];?> | Máximo S/<?php echo $payment_limits['max'];?></span>
	</p>

	<form action="#" id="kushki_payment_form">
		<p class="text-muted text-start write-text">Escriba el valor aquí: *</p>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon3">PEN</span>
			</div>

			<input 
				autocomplete="off"
				data-bs-toggle="tooltip"
				data-bs-placement="top"
				type="text" 
				placeholder="Min <?php echo $payment_limits['min'];?> | Max <?php echo $payment_limits['max'];?>" 
				data-min="<?php echo $payment_limits[0];?>"
				data-max="<?php echo $payment_limits[1];?>"
				class="form-control" 
				id="basic-url" 
				aria-describedby="basic-addon3" required onkeyup="validar()">
		</div>
		<p id="sms_alert"></p>
		<div>
			<button type="button" class="btn btn-secondary" style="font-size: 14px; width: 150px;">
				<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
  				<span class="sr-only">Cargando Prometeo</span>
			</button>
		</div>

	</form>
	<div id="kushki_payment_holder">
		<div id="kushki_details"></div>
		<br>
		<div>
			<a id="kushki_btn" target="_top">
				<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
  				<span class="sr-only">Cargando Prometeo</span>
			</a>
		</div>
	</div>
</body>
</html>
	<?php
}else{
	?>
		<div> <h5 style="text-align: center;  margin-top: 50px;">Por favor vuelva a intentarlo</h5> </div>
	<?php
}
?>