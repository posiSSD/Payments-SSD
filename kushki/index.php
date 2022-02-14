<?php 
require '/var/www/payments.apuestatotal.app/kushki/env.php';
require '/var/www/payments.apuestatotal.app/kushki/db.php';
include '/var/www/html/sys/helpers.php';

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
	if(in_array($user_id, [3333200])){
		?>
		<script type="text/javascript">
			var this_url = "<?php echo "http".(array_key_exists("HTTPS", $_SERVER)?"s":"")."://".$_SERVER["HTTP_HOST"];?>";
			// console.log(this_url);
			var user_id=<?php echo $user_id;?>;
			var auth_token="<?php echo $auth_token;?>";
			var connecting = false;
			var site_id = 279;
			var sws;
			// var ws_url = "wss://us-swarm-ws.betconstruct.com/";
			var ws_url = "wss://eu-swarm-springre.betconstruct.com/";
			// var ws_url = "wss://soe-swarm-apuesta.betconstruct.com/";
			var message_queue = [];
			var swsid;
			var ws_session = false;
			var fw_user = [];
			var fw_active = true;
			// var fw_active = false;
			var interval_fw_get_balance = false;
			var interval_sw_get_user_balance = false;
		</script>
		<script type="text/javascript" src="<?php echo $url;?>js/jquery-3.6.0.min.js?<?php echo $fv;?>"></script>
		<script type="text/javascript" src="<?php echo $url;?>js/bc_ws.js?<?php echo $fv;?>"></script>
		<script type="text/javascript" src="<?php echo $url;?>js/k.js?<?php echo $fv;?>"></script>

		<?php
	}
}
log_write($visit);
?>
<div id="msg">:)</div>