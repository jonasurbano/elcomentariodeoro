<?php

/**
 * Guarda el equipo que sigue el jugador.
 * Crea el jugador si es necesario.
 * @param $_GET['club'] string. Obligatorio.
 */

if (!isset($_GET['club'])) exit('error');

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$idFacebook = $facebook->getUser();
$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!$jugador) $jugador = new Jugador($idFacebook);

$jugador->setSigueClub($_GET['club']);
$jugador->setUltimaConexion(new DateTime);

$em->persist($jugador);
$em->flush($jugador);

?>
