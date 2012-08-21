<?php

//if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
//    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
//        die('E1: La conexión tiene que ser HTTPS.');
//} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

header('Content-type: text/html; charset=utf-8');

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/JornadaRepositorio.php';
require_once "../entities/Jornada.php";
require_once "../entities/Partido.php";
require_once "../entities/Comentario.php";
require_once "../entities/Jugador.php";

$em = GetMyEntityManager();
$jornadaActual = $em->getRepository('Jornada')->getJornada();
$numUltimaJornada = $em->getRepository('Jornada')->numUltimaJornada();

if ($_POST['numJornada'] <= $numUltimaJornada)
    exit('La jornada ya está creada');
else if ($_POST['numJornada'] > $numUltimaJornada + 1)
    exit('Cree primero la jornada ' . ($numUltimaJornada + 1));

try {
    $fechaTope = new DateTime($_POST['fechaTope']);
} catch (Exception $e) {
    echo $e->getMessage();
    exit('fechaTope mal construída.<br>');
}

try {
    $fechaResultados = new DateTime($_POST['fechaResultados']);
} catch (Exception $e) {
    echo $e->getMessage();
    exit('fechaResultados mal construída.<br>');
}

$jornada = new Jornada;
$jornada->setId($_POST['numJornada']);
$jornada->setFechaTope($fechaTope);
$jornada->setFechaResultados($fechaResultados);

$em->persist($jornada);
$em->flush();

echo 'Parece que la jornada se ha creado correctamente';

?>
