<?php

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS.');
} else die('E2: La conexión tiene que ser HTTPS');


if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

if (!isset($_POST['numJornada']))
    exit('Núm. de jornada no llegó correctamente.');

$numJornada = $_POST['numJornada'];

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/JornadaRepositorio.php';
require_once '../repositories/PartidoRepositorio.php';
require_once "../entities/Jornada.php";
require_once "../entities/Partido.php";
require_once "../entities/Comentario.php";
require_once "../entities/Jugador.php";

$em = GetMyEntityManager();
$hayPartidos = $em->getRepository('Partido')->hayPartidos($numJornada);

if ($hayPartidos) exit('Ya hay partidos para esta jornada.');

$jornada = $em->find('Jornada', $numJornada);
if (!$jornada) exit('No existe la jornada ' . $numJornada . '.');

$partidos = array();
for ($i = 0; $i < 15; $i++) {
    $partidos[$i] = new Partido;
    $partidos[$i]->setClub1($_POST['partido-'.$i.'-1']);
    $partidos[$i]->setClub2($_POST['partido-'.$i.'-2']);
    $partidos[$i]->setJornada($jornada);
    $em->persist($partidos[$i]);
}

$em->flush();

header('Content-type: text/html; charset=utf-8');
echo 'Parece que los partidos se han creado correctamente';

?>
