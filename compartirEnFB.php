<?php

require_once 'bootstrap.php';

if (!isset($_GET['mensaje'])) die('Mensaje no recibido.');
$mensaje = $_GET['mensaje'];

try {
    $ret_obj = $facebook->api('/me/feed', 'POST',
        array(
            'link' => AppInfo::getUrl('images/logo.png'),
            'message' => $mensaje,
            'name' => 'YoséDeFútbol',
            'caption' => 'Juega en YoSéDeFútbol',
            'description' => 'Cuéntanos qué pasará en ' .
                'la liga. En YoSéDeFútbol podrás puntuar ' .
                'y escribir todo lo que quieras sobre los partidos ' .
                'de la jornada. Además podrás leer lo que piensan tus ' .
                'amigos y más...',
            'picture' => 'http://ysdf.phpfogapp.com/images/icono.png',
            'type' => 'link',
            'application' => array(
                'name' => 'Name of your app',
                'id' => AppInfo::appID() )
        ));
} catch (FacebookApiException $e) {
    echo $e;
}

?>
