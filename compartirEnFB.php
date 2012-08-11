<?php

require_once 'bootstrap.php';

if (!isset($_GET['mensaje'])) exit('Mensaje no recibido.');
$mensaje = $_GET['mensaje'];

$idFacebook = $facebook->getUser();

try {
    $ret_obj = $facebook->api('/' . $idFacebook . '/feed', 'POST',
        array(
            'link' => AppInfo::getUrl(),
            'message' => $mensaje,
            'name' => $nombreAplicacion,
            'caption' => 'Juega en ' . $nombreAplicacion,
            'description' => 'Cuéntanos qué pasará en ' .
                'la liga. En ' . $nombreAplicacion . ' podrás puntuar ' .
                'y escribir todo lo que quieras sobre los partidos ' .
                'de la jornada. Además podrás leer lo que piensan tus ' .
                'amigos y más...',
            'picture' => 'http://ysdf.phpfogapp.com/images/icono.jpg',
            'type' => 'link',
            'application' => array(
                'name' => $nombreAplicacion,
                'id' => AppInfo::appID() )
        ));
} catch (FacebookApiException $e) {
    echo $e;
}

?>
