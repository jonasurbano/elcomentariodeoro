<?php

require_once 'bootstrap.php';

if (!isset($_POST['mensaje'])) exit('error');
$mensaje = $_POST['mensaje'];

if (isset($_POST['idComentario']) && is_numeric($_POST['idComentario'])) {
    $enlace = 'https://ysdf.phpfogapp.com/verComentario.php?id=' .
        $_POST['idComentario'];
} else if (isset($_POST['enlace'])) {
    if (substr($_POST['enlace'],0,26) != 'https://ysdf.phpfogapp.com')
        exit('error');
    $enlace = $_POST['enlace'];
} else {
    $enlace = AppInfo::getUrl();
}

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
