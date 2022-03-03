<?php 
require '/var/www/payments.apuestatotal.app/kushki/env.php';
require '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/html/sys/helpers.php';

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
$url.= "/kushki/";


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

$auth_data = false;
$auth_token = false;
$user_id = false;
if(isset($_GET['auth_data'])){
	// [auth_token] => FAE2579BC8325A2F60B432173CEF4D77
	// [user_id] => 3333200
	// [avatarUrl] => https://static.springbuilder.site/assets/addon/avatar.png	
	$auth_data = json_decode($_GET["auth_data"],true);
	$auth_token = $auth_data["auth_token"];
	$user_id = $auth_data["user_id"];
	$visit["auth_data"]=$auth_data;
}

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
	$payment_limits=[];
		$payment_limits['min']=number_format(20,0);
		$payment_limits['max']=number_format(3000,0);
	?>
	<style type="text/css">
		form#kushki_payment_form{
			position: relative;
			width: 300px;
			left: 50%;
			margin-left: -150px;
			text-align: center;
		}
		form#kushki_payment_form span{
			text-align: left;
		}
		form#kushki_payment_form input.alert{
			/*width: 200px;
			left: 50%;
			margin-left: -100px;
			position: relative;*/
			border-color: #f00;
			outline: none !important;
			border:1px solid red;
			box-shadow: 0 0 10px #719ECE;
		}
		form#kushki_payment_form input.alert:focus {
			border-color: #f00;
			outline: none !important;
			border:1px solid red;
			box-shadow: 0 0 10px #719ECE;
		}
		form#kushki_payment_form button{
			background-color: #999;
			font-family: "Open Sans";
			padding: 5px 10px;
			font-size: 14px;
			color: #fff;
			border-radius: 26px;
			cursor: wait;
			text-decoration: none;
			display: inline-block;
			border: none;
			/*height: 32px;*/
			/*position: relative;
			width: 80px;
			left: 50%;
			margin-left: -40px;*/
		}
		form#kushki_payment_form button.ready{
			background-color: #f00;
			cursor: pointer;
		}
		#kushki_payment_holder{
			position: relative;
			width: 300px;
			left: 50%;
			margin-left: -150px;
			text-align: center;
			display: none;
		}
		#kushki_payment_holder #kushki_btn{
			background-color: #999;
			display: none;
			font-family: "Open Sans";
			padding: 5px 10px;
			font-size: 14px;
			color: #fff;
			border-radius: 26px;
			cursor: wait;
			text-decoration: none;
			display: inline-block;
			border: none;
			color: #193470;
		}
		#kushki_payment_holder #kushki_btn.ready{
			background-color: #00FCB2;
			cursor: pointer;
		}
	</style>
	<div id="msg" style="font-style: italic;">:)</div>
	<form id="kushki_payment_form">
		<div><span>Mínimo S/<?php echo $payment_limits['min'];?> | Máximo S/<?php echo $payment_limits['max'];?></span></div>
		<div><span>Escriba el valor aquí: *</span></div>
		<div><input type="text" placeholder="Min <?php echo $payment_limits['min'];?> | Max <?php echo $payment_limits['max'];?>" autofocus value=""></div>
		<div><button type="button">Cargando...</button></div>
	</form>
	<div id="kushki_payment_holder">
		<div id="kushki_details"></div>
		<div><a id="kushki_btn" target="_top">Cargando Kushki...</a></div>
	</div>
	<?php
}else{
	?>:)<?php
}
?>