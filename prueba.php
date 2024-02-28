<?php
include 'env.php';
include 'db.php';

$limit_try = 0;
//$bc_deposit = bc_deposit($d);
// 'http_code' => 200, 'status' => 'Ok', 'result' =>  $response]
// {code: 12, message: 'Duplicate transaction number', FirstName: '', LastName: '', txn_id: 125}
$bc_deposit = [];
$bc_deposit['http_code'] = 200;
$bc_deposit['status'] = 'Ok';
$bc_deposit['result']['code'] = 12;
$bc_deposit['result']['message'] = 'OK';
$bc_deposit['result']['FirstName'] = 'Posi';
$bc_deposit['result']['LastName'] = 'Vargas';
$bc_deposit['result']['txn_id'] = 125;


do{
    
    if(array_key_exists('http_code', $bc_deposit)){
        if ($bc_deposit['http_code']==200){
            $ret['http_code']=200;
            $ret['status']='Ok';
            $ret['response']='Order '.'12345'.' paid';
            api_ret($ret);
        }  else {  
            if( $limit_try <= 5 ){
               
                $ret['http_code']=$bc_deposit['http_code'];
                $ret['status']='Error';
                $ret['response']='Prueba';
                $ret['try'] = 'Try :'.$limit_try;
                api_ret($ret);
            } else {
                $ret['http_code']=$bc_deposit['http_code'];
                $ret['status']='Error';
                $ret['response']='Prueba';
                $ret['try'] = 'Try :'.$limit_try;
                api_ret($ret);
            }                                 
        }
    }
    $limit_try++;
    echo "try : ". $limit_try.'<br>';
    //sleep(10);
} while ( ($bc_deposit['http_code'] !== 200) || ($limit_try !== 5) );
//while ($bc_deposit['http_code'] !== 200);
echo "Salio: ". $limit_try.'<br>';

function api_ret($r){
    //global $a;
    //$r['request'] = $a['request'];
	api_activities($r);
    //exit(); 
}

function api_activities($a){
	global $mysqli;
    $bd = 'at_payments_prueba';
    $table = 'api_activities';
    $rq = []; // (array_key_exists("account",$url_data)?$url_data["account"]:null);
    $ip = ( array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : NULL );
    $method = ( array_key_exists('REQUEST_METHOD',$_SERVER) ? $_SERVER['REQUEST_METHOD'] : NULL );
    $request = ( array_key_exists('request', $a) ? json_encode($a['request']) : NULL );
    $response = ( array_key_exists('response', $a) ? json_encode($a['response']) : NULL );
    $http_code = ( array_key_exists('http_code', $a) ? $a['http_code'] : NULL );
    $status = ( array_key_exists('status', $a) ? $a['status'] : NULL );
    $created_at = ( new DateTime('now', new DateTimeZone('America/Lima')) )->format('Y-m-d H:i:s');
    $updated_at = ( new DateTime('now', new DateTimeZone('America/Lima')) )->format('Y-m-d H:i:s');


    $sql_insert = "INSERT INTO $table (ip,method,request,response,http_code,status,created_at,updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $sql_insert = $mysqli->prepare($sql_insert);
    $sql_insert->bind_param("ssssssss", $ip, $method, $request, $response, $http_code, $status, $created_at, $updated_at);
    // Ejecutar la consulta
    if ($sql_insert->execute() === TRUE) {
        $id = $mysqli->insert_id;
        $rq['id'] = $id;
        $rq['ip'] = $ip;
        $rq['method'] = $method;
        $rq['request'] = $request;
        $rq['response'] = $response;
        $rq['http_code'] = $http_code;
        $rq['status'] = $status;
        $rq['created_at'] = $created_at;
        $rq['updated_at'] = $updated_at;
        //consolelogdata($rq); 
        foreach ($rq as $key => $value) {
            echo "$key: $value<br>";
        }
        echo "<br>";
        return $rq;  
    } else {
        $errordb = $sql_insert->error;
        //consolelogdata($errordb);
        return false; 
    }        

}

?>