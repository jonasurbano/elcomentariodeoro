<?php

/**
 * Guarda un pronóstico en la base de datos.
 * Se realizan varias comprobaciones:
 * 1. Los parámetros GET hayan sido establecidos.
 * 2. Exista el partido al que se refiere el parámetro.
 * 3. La jornada existe.
 * 4. La fecha tope de la jornada no haya pasado.
 * 5. El usuario esté autenticado en Facebook.
 * 6. No exista un pronóstico para ese partido y de ese jugador.
 */

if (!isset($_GET['idPartido']) || !is_numeric($_GET['idPartido'])) exit();
if (!isset($_GET['resultado'])) exit();
if ($_GET['resultado'] != '1' && $_GET['resultado'] != 'x' &&
    $_GET['resultado'] != 'X' && $_GET['resultado'] != '2') exit();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');
$repositorioPronostico = $em->getRepository('Pronostico');

$idPartido = $_GET['idPartido'];
$partido = $em->find("Partido",$idPartido);
if (!$partido) exit();

$jornada = $partido->getJornada();
if (!$jornada) exit();
if (new DateTime > $jornada->getFechaTope()) exit('en-juego');

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit();
$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!$jugador) {
    $escritor = new Jugador($idFacebook);
    $em->persist($escritor);
    $em->flush($escritor);
}

$pronostico = $repositorioPronostico->
    getPronostico($idPartido,$jugador->getId());

$resultado = $_GET['resultado'];
if (!$pronostico) {
    $pronostico = new Pronostico();
    $pronostico->setJugador($jugador);
    $pronostico->setPartido($partido);
    $pronostico->setResultado($resultado);
    $em->persist($pronostico);
} else {
    if ($pronostico->getResultado() != $resultado) {
        $pronostico->setResultado($resultado);
        $em->persist($pronostico);
    }
}
$em->flush();

?>
