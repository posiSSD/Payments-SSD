<?php

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://apiperu.dev/api/dni/43164698?api_token=2defc180f1ae19bccd495e91e564e181e188a39c377c9b4d714f8b4357bfcdfd",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}


?>