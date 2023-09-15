<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth_token = $_POST['auth_token'];
    $user_id = $_POST['user_id'];
    $metodo = $_POST['metodo'];

    // Obtén la URL de Prometeo con los parámetros
    $url = 'http';
    $url .= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '');
    $url .= '://';
    $url .= (isset($_SERVER["HTTP_HOST"]) ? substr($_SERVER['HTTP_HOST'], 0) : "");
    $url .= "/prometeo/";
    
    // Agrega los parámetros POST como parámetros de consulta en la URL
    //$url .= "?auth_token=" . urlencode($auth_token) . "&user_id=" . urlencode($user_id) . "&metodo=" . urlencode($metodo);
    // Agregar los parámetros a la URL
    $url .= "?auth_data=" . json_encode(array("auth_token" => $auth_token, "user_id" => $user_id, "metodo" => $metodo));

    // Redireccionar al usuario a la URL
    header("Location: " . $url);
    // Imprime un iframe que carga la página de Prometeo

    // Imprime un iframe que carga la página de Prometeo
    //echo '<iframe src="' . $url . '" frameborder="0" width="100%" height="600"></iframe>';
}
?>

