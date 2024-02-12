<?php
require 'env.php'; //desde la raiz a otro lado
require 'db.php';   
include ROOT_PATH.'/sys/helpers.php';
?>
<?php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "totalbet.com") !== false) {
    
    // construccion del array de la ip visitante.
    $visit = [];
    $visit["init"]=date("Y-m-d H:i:s");
    $visit['ip']=$_SERVER['REMOTE_ADDR'];
    $visit['device']=$_SERVER['HTTP_USER_AGENT'];
    $visit['ref']=(array_key_exists('HTTP_REFERER', $_SERVER)?$_SERVER['HTTP_REFERER']:'direct');
    $visit['url']=$_SERVER['REQUEST_URI'];

    // Manejo de los datos enviados por URI
    $auth_data = null;
    if(isset($_GET['auth_data'])){
        $auth_data = json_decode($_GET["auth_data"],true);
        $auth_token = $auth_data["auth_token"]; /////TOKEN
        $user_id = $auth_data["user_id"];
        $metodo = $auth_data["metodo"];
        $amount = $auth_data["amount"];
        $visit["auth_data"]=$auth_data;
        //consolelogfrontdata($auth_data);
    }
    /*
    else{
        $auth_token = '487259136B05F289C1A501FAB667EAAD';
        $user_id = '1674627753';
        $metodo = 'prometeo';
        $auth_data = array("auth_token" => $auth_token, "user_id" => $user_id);
        $visit["auth_data"]=$auth_data;
        //echo "datos ELSE ";
    }
    */

    // conustruccion de URL 
    $fv=time();
    $url = 'http';
    $url.= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'');
    $url.= '://';
    $url.= (isset($_SERVER["HTTP_HOST"]) ? substr($_SERVER['HTTP_HOST'],0):"");
    $url.= '/';
    $url.= $metodo;
    $url.= '/';

    // save logs
    $log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
    $log_file = date("Y-m-d").".log";
    log_init($log_dir,$log_file);
    log_write($visit);
     
    // registro en bd de la visita.  
    $visit_insert = "INSERT INTO 
					tbl_visits 
					(url,ip,server_info) 
					VALUES ('".$visit["url"]."','".$visit['ip']."','".print_r($_SERVER,true)."')";
    $mysqli->query($visit_insert);
    if($mysqli->error){
        echo $mysqli->error; die; 
    }
    $mysqli->close();

    if($auth_data){
        ?>

        <script type="text/javascript">
			var this_url = "<?php echo $url;?>";
			var user_id= <?php echo $user_id;?>;
			var auth_token="<?php echo $auth_token;?>";
		</script>
		<script type="text/javascript" src="<?php echo $url;?>js/jquery-3.6.0.min.js?<?php echo $fv;?>"></script>		
		<script type="text/javascript" src="<?php echo $url;?>js/bc_ws.js?<?php echo $fv;?>"></script>
		<script type="text/javascript" src="<?php echo $url;?>js/k.js?<?php echo $fv;?>"></script>

        <?php
        $payment_limits=explode(',', env('DEPOSIT_LIMITS'));
        $payment_limits['min']=number_format($payment_limits[0],0);
        $payment_limits['max']=number_format($payment_limits[1],0);
        ?>
        
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Payments.app</title>
            <link rel="stylesheet" href="<?php echo $url; ?>css/k.css">
            <link rel="stylesheet" href="<?php echo $url; ?>css/new.scss">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

            <!--  payphone scripts -->
            <link rel=»stylesheet» href=»https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.css»>
            <script type=»module» src=»https://cdn.payphonetodoesposible.com/box/v1.0/payphone-payment-box.js»></script>

        </head>
        <body>
            <div id="msg" style="font-style: italic;"></div>
            
            <form action="#" id="kushki_payment_form">
                <p class="text-muted text-start write-text" id="texto">Escriba el valor aquí: *</p>
                <div class="input-group mb-3" id="inputtext">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">USD</span>
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
                        aria-describedby="basic-addon3"
                        value="<?php echo htmlspecialchars($amount); ?>"
                        readonly 
                        required onkeyup="validar()">
                </div>
                <p id="sms_alert"></p>
                <div>
                    <button type="button" class="btn btn-secondary" style="font-size: 14px; width: 150px;">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Cargando</span>
                    </button>
                </div>
        
            </form>
            <div id="prometeoembeded">
                <iframe id="prometeoframe" frameborder="0" allowfullscreen></iframe>  
            </div>
            <div id="kushki_payment_holder">
                <div id="kushki_details"></div>
                <br>
                <div>
                    <a id="kushki_btn" target="_top">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Cargando <?php echo $metodo?></span>
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
}else{
 // pruebas en el mismo https://payments.totalbet.com/ sin entrar a totalbet.com
 $visit = [];
 $visit["init"]=date("Y-m-d H:i:s");
 $visit['ip']=$_SERVER['REMOTE_ADDR'];
 $visit['device']=$_SERVER['HTTP_USER_AGENT'];
 $visit['ref']=(array_key_exists('HTTP_REFERER', $_SERVER)?$_SERVER['HTTP_REFERER']:'direct');
 $visit['url']=$_SERVER['REQUEST_URI'];

 // Manejo de los datos enviados por URI
 $auth_data = null;
 if(isset($_GET['auth_data'])){
     $auth_data = json_decode($_GET["auth_data"],true);
     $auth_token = $auth_data["auth_token"]; /////TOKEN
     $user_id = $auth_data["user_id"];
     $metodo = $auth_data["metodo"];
     $visit["auth_data"]=$auth_data;
     //consolelogfrontdata($auth_data);
 }
 
 else{
     $auth_token = '9B48307736CD353975C31E2DE6BF5CA5';
     $user_id = '1674627753';
     $metodo = 'prometeo';
     $amount = 5;
     $auth_data = array("auth_token" => $auth_token, "user_id" => $user_id);
     $visit["auth_data"]=$auth_data;
     //echo "datos ELSE ";
 }
 

 // conustruccion de URL 
 $fv=time();
 $url = 'http';
 $url.= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'');
 $url.= '://';
 $url.= (isset($_SERVER["HTTP_HOST"]) ? substr($_SERVER['HTTP_HOST'],0):"");
 $url.= '/';
 $url.= $metodo;
 $url.= '/';

 // save logs
 $log_dir = str_replace(strrchr($_SERVER['SCRIPT_FILENAME'], "/"), "", $_SERVER['SCRIPT_FILENAME'])."/log/";
 $log_file = date("Y-m-d").".log";
 log_init($log_dir,$log_file);
 log_write($visit);
  
 // registro en bd de la visita.  
 $visit_insert = "INSERT INTO 
                 tbl_visits 
                 (url,ip,server_info) 
                 VALUES ('".$visit["url"]."','".$visit['ip']."','".print_r($_SERVER,true)."')";
 $mysqli->query($visit_insert);
 if($mysqli->error){
     echo $mysqli->error; die; 
 }
 $mysqli->close();

 if($auth_data){
     ?>

     <script type="text/javascript">
         var this_url = "<?php echo $url;?>";
         var user_id= <?php echo $user_id;?>;
         var auth_token="<?php echo $auth_token;?>";
     </script>
     <script type="text/javascript" src="<?php echo $url;?>js/jquery-3.6.0.min.js?<?php echo $fv;?>"></script>		
     <script type="text/javascript" src="<?php echo $url;?>js/bc_ws.js?<?php echo $fv;?>"></script>
     <script type="text/javascript" src="<?php echo $url;?>js/k.js?<?php echo $fv;?>"></script>

     <?php
     $payment_limits=explode(',', env('DEPOSIT_LIMITS'));
     $payment_limits['min']=number_format($payment_limits[0],0);
     $payment_limits['max']=number_format($payment_limits[1],0);
     ?>
     
     <!DOCTYPE html>
     <html>
     <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>Payments.app</title>
         <link rel="stylesheet" href="<?php echo $url; ?>css/k.css">
         <link rel="stylesheet" href="<?php echo $url; ?>css/new.scss">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

     </head>
     <body>
         <div id="msg" style="font-style: italic;"></div>
         
         <form action="#" id="kushki_payment_form">
             <p class="text-muted text-start write-text" id="texto">Escriba el valor aquí: *</p>
             <div class="input-group mb-3" id="inputtext">
                 <div class="input-group-prepend">
                     <span class="input-group-text" id="basic-addon3">USD</span>
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
                     aria-describedby="basic-addon3"
                     value="<?php echo htmlspecialchars($amount); ?>"
                     readonly  
                     required onkeyup="validar()">
             </div>
             <p id="sms_alert"></p>
             <div>
                 <button type="button" class="btn btn-secondary" style="font-size: 14px; width: 150px;">
                     <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                     <span class="sr-only">Cargando</span>
                 </button>
             </div>
     
         </form>         
         <div id="prometeoembeded">
             <iframe id="prometeoframe" frameborder="0" allowfullscreen></iframe>
         </div>
    
         <div id="kushki_payment_holder">
             <div id="kushki_details"></div>
             <br>
             <div>
                 <a id="kushki_btn" target="_top">
                     <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                     <span class="sr-only">Cargando <?php echo $metodo?></span>
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
    
}
?>
   
<?php
/*
function consolelogfrontdata($auth_data) {
   
    echo '<script>';
    echo 'console.log("IF:", ' . json_encode($auth_data ) . ');';
    echo '</script>';
}
*/
?>
 