<?php
include 'env.php';
include 'db.php';
$fecha_hora_actual = (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');

$array = [
    "verify_token" => "token",
    "event_type" => "payment.success",
    "event_id" => "fb993ee7-5a96-4d67-9d3d-54c2a9159646",
    "timestamp" => "2024-02-05T22:12:09.880075",
    "amount" => 7.00,
    "concept" => "Recarga Prometeo",
    "currency" => "USD",
    "origin_account" => "6484544638",
    "destination_account" => "1003001234567",
    "destination_institution" => 0,
    "branch" => 0,
    "destination_owner_name" => "TotalBet",
    "destination_account_type" => null,
    "document_type" => null,
    "document_number" => null,
    "destination_bank_code" => "bcp_pe",
    "mobile_os" => null,
    "request_id" => "7f2775a2a34548199ada23e6403b1349",
    "intent_id" => "11111ffec-1be5-4bc4-808e-394623111111",
    "external_id" => "111111111111111111111111",
    "id_usuario" => 1674627753,
    "created_at" => $fecha_hora_actual
];

$array_details = prometeo_api_transactions($array);

print_r($array_details);

function prometeo_api_transactions($data = false) {
    global $mysqli;

    if ($data !== false) {
        $db = 'at_payments_prueba';
        $table = 'prometeo_transactions';

        $insert_arr = [];
        $insert_arr['id_usuario'] = isset($data['id_usuario']) ? $data['id_usuario'] : null;
        $insert_arr['verify_token'] = isset($data['verify_token']) ? $data['verify_token'] : null;
        $insert_arr['event_type'] = isset($data['event_type']) ? $data['event_type'] : null;
        $insert_arr['event_id'] = isset($data['event_id']) ? $data['event_id'] : null;
        $insert_arr['timestamp'] = isset($data['timestamp']) ? $data['timestamp'] : null;
        $insert_arr['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $insert_arr['concept'] = isset($data['concept']) ? $data['concept'] : null;
        $insert_arr['currency'] = isset($data['currency']) ? $data['currency'] : null;
        $insert_arr['origin_account'] = isset($data['origin_account']) ? $data['origin_account'] : null;
        $insert_arr['destination_account'] = isset($data['destination_account']) ? $data['destination_account'] : null;
        $insert_arr['destination_institution'] = isset($data['destination_institution']) ? $data['destination_institution'] : null;
        $insert_arr['branch'] = isset($data['branch']) ? $data['branch'] : null;
        $insert_arr['destination_owner_name'] = isset($data['destination_owner_name']) ? $data['destination_owner_name'] : null;
        $insert_arr['destination_account_type'] = isset($data['destination_account_type']) ? $data['destination_account_type'] : null;
        $insert_arr['document_type'] = isset($data['document_type']) ? $data['document_type'] : null;
        $insert_arr['document_number'] = isset($data['document_number']) ? $data['document_number'] : null;
        $insert_arr['destination_bank_code'] = isset($data['destination_bank_code']) ? $data['destination_bank_code'] : null;
        $insert_arr['mobile_os'] = isset($data['mobile_os']) ? $data['mobile_os'] : null;
        $insert_arr['request_id'] = isset($data['request_id']) ? $data['request_id'] : null;
        $insert_arr['intent_id'] = isset($data['intent_id']) ? $data['intent_id'] : null;
        $insert_arr['external_id'] = isset($data['external_id']) ? $data['external_id'] : null;
        $insert_arr['created_at'] = isset($data['created_at']) ? $data['created_at'] : (new DateTime('now', new DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
        
        $data_to_db = data_to_db($insert_arr); // Asegúrate de que esta función esté definida.
        $insert_command = "INSERT INTO {$db}.{$table} (";
        $insert_command .= implode(", \n", array_keys($insert_arr));
        $insert_command .= ") VALUES ";
        $insert_command .= "(";
        $insert_command .= implode(", \n", $data_to_db);
        $insert_command .= ")";
        $insert_command .= " ON DUPLICATE KEY UPDATE ";

        $uqn = 0;
        foreach ($data_to_db as $k => $v) {
            if ($uqn > 0) { $insert_command .= ", \n"; }
            $insert_command .= "".$k." = VALUES(".$k.")";
            $uqn++;
        }
        $mysqli->query($insert_command);
        if ($mysqli->error) {
            echo $mysqli->error;
            echo "\n";
            echo $insert_command;
            echo "\n";
            exit();
        }
        return $insert_arr;
    }
}

function data_to_db($d){
    global $mysqli;
    $tmp=[];
    $nulls=["null","",false,"false"];
    foreach ($d as $k => $v) {
        if(array_search($v, $nulls) !== false){
            $tmp[$k]="NULL";
        } else {
            if(is_float($v)){
                $tmp[$k]="'".$v."'";
            } elseif(is_int($v)){
                $tmp[$k]=$v;
            } elseif(in_array($v, ["NOW()"])){
                $tmp[$k]=$v;
            } else {
                if(is_string($v)){
                    $tmp[$k]="'".trim($mysqli->real_escape_string($v))."'";
                } else {
                    print_r($k);
                    echo "\n\n";
                    print_r($v);
                    exit();
                }
            }
        }
    }
    return $tmp;
}

?>

