<?php

if (!isset($_GET['idf'])) die("error");

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$jugador = $repositorioJugadores->getIdJugador($_GET['idf']);
if (!isset($jugador) || !$jugador) echo 'no-jugador';
else echo 'jugador';

?>
