<?php

require_once 'bootstra.php';

$jugador = $_GET['jugador'];
if (!isset($_GET['jugador'])) die();

try {
    $ret_obj = $facebook->api('/me/feed', 'POST',
        array(
            'link' => 'www.example.com',
            'message' => $jugador . ' ha escrito un comentario
                muy interesante que puedes ver en YoS&eacute;DeF&uacute;tbol.'
        ));
} catch (FacebookApiException $e) {}
    
?>
