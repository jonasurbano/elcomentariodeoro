<?php

//if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
//    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
//        die('E1: La conexión tiene que ser HTTPS.');
//} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/PartidoRepositorio.php';
require_once "../entities/Partido.php";
require_once "../entities/Comentario.php";
require_once "../entities/Jornada.php";
require_once "../entities/Jugador.php";

$em = GetMyEntityManager();

header('Content-type: text/html; charset=utf-8');

$partidos = array();
for ($i = 0; $i < 15; $i++) {
    if (isset($_POST['id-'.$i])) {
        $partidos[$i] = $em->find('Partido', $_POST['id-'.$i]);
        if (isset($_POST['r-'.$i])) {
            $partidos[$i]->setResultado($_POST['r-'.$i]);
            $em->persist($partidos[$i]);
        } else echo 'El partido ' . $_POST['id-'.$i] . ' no se pudo puntuar.';
    } else 'La puntuación de un partido no llegó correctamente.';
}

$em->flush();

echo 'Parece que los partidos se han puntuado correctamente.';

?>
