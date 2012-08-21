<?php

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
header('Content-type: text/html; charset=utf-8');

date_default_timezone_set("Europe/Madrid");

if (!class_exists("Doctrine\Common\Version", false)) {
    require_once "bootstrap_doctrine.php";
}

require_once 'repositories/PronosticoRepositorio.php';
require_once 'repositories/JornadaRepositorio.php';
require_once 'repositories/PartidoRepositorio.php';
require_once 'repositories/ComentarioRepositorio.php';
require_once 'repositories/JugadorRepositorio.php';
require_once "entities/Partido.php";
require_once "entities/Jornada.php";
require_once "entities/Pronostico.php";
require_once "entities/Jugador.php";
require_once "entities/Comentario.php";

require_once('AppInfo.php');

if (substr(AppInfo::getUrl(), 0, 8) != 'https://' &&
    $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
    header('Location: https://' . $_SERVER['HTTP_HOST']
        . $_SERVER['REQUEST_URI']);
    exit();
}

require_once('utils.php');
require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
    'appId' => AppInfo::appID(),
    'secret' => AppInfo::appSecret(),
    'cookie' => true,
));

$nombreAplicacion = 'El comentario de oro';
$urlFacebook = 'https://apps.facebook.com/394014370642216/';
?>