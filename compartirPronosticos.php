<?php

if (!isset($_GET['jornada']) ||
    !is_numeric($_GET['jornada']) ||
    $_GET['jornada'] <= 0) exit('error');

require_once 'bootstrap.php';

$idFacebook = $facebook->getUser();
if (!$idFacebook || $idFacebook <= 0) exit('error');

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');
$repositorioPronostico = $em->getRepository('Pronostico');

$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!$jugador) exit('error');

$jornada = $em->find('Jornada', $_GET['jornada']);
if (!$jornada) exit('error');

$partidos = $jornada->getPartidos();
if (!$partidos) exit('error');

$numPronosticos = 0;
foreach ($partidos as $partido) {
    $idPartido = $partido->getId();
    $resultado = $repositorioPronostico->getResultado($idPartido,$jugador->getId());
    if ($resultado) $numPronosticos++;
}

if ($numPronosticos > 0) {
    if (!$jugador->isJornadaPublica($jornada->getId())) {
        $jugador->jornadaPublicaAsignada($jornada);
        $em->persist($jugador);
        $em->flush();
    }
    echo 'https://ysdf.phpfogapp.com/verPronosticos.php?id=' .
        $jugador->getId() . '&j=' . $jornada->getId();;
} else exit('no-pronosticos');
?>
