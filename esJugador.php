<?php

if (!isset($_GET['idf']) || !is_numeric($_GET['idf'])) exit("error");

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$jugador = $repositorioJugadores->getIdJugador($_GET['idf']);
if (!$jugador) echo 'no-jugador';
else echo 'jugador';

?>
