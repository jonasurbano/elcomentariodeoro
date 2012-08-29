<?php

require_once 'bootstrap.php';

//echo $_POST['mensaje'] . ', ' . $_POST['enlace'];

if (!isset($_POST['mensaje'])) exit('no-mensaje');
$mensaje = $_POST['mensaje'];

if (isset($_POST['idComentario']) && is_numeric($_POST['idComentario'])) {
    $enlace = 'https://ysdf.phpfogapp.com/verComentario.php?id=' .
        $_POST['idComentario'];
} else if (isset($_POST['enlace'])) {
    if (substr($_POST['enlace'],0,26) != 'https://ysdf.phpfogapp.com')
        exit('url-incorrecta');
    $enlace = $_POST['enlace'];
} else {
    $enlace = $urlFacebook;
}

//echo 'Enlace ' . $enlace;
//echo 'Mensaje ' . $mensaje;

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit('no-login');

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

    //var_dump($ret_obj);
} catch (FacebookApiException $e) {
    echo $e;
}

?>
