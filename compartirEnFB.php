<?php

require_once 'bootstrap.php';

if (!isset($_GET['mensaje'])) exit('Mensaje no recibido.');
$mensaje = $_GET['mensaje'];

if (isset($_GET['idComentario']) && is_numeric($_GET['idComentario'])) {
    $enlace = 'https://ysdf.phpfogapp.com/verComentario.php?id=' .
        $_GET['idComentario'];
} else $enlace = AppInfo::getUrl();

$idFacebook = $facebook->getUser();

try {
    $ret_obj = $facebook->api('/' . $idFacebook . '/feed', 'POST',
        array(
            'link' => $enlace,
            'message' => $mensaje,
            'name' => $nombreAplicacion,
            'caption' => 'Juega en ' . $nombreAplicacion,
            'description' => 'Cuéntanos qué pasará en ' .
                'la liga. En "' . $nombreAplicacion . '" podrás puntuar ' .
                'y escribir todo lo que quieras sobre los partidos ' .
                'de la jornada, leer lo que piensan tus amigos y más...',
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
