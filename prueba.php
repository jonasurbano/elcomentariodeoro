<?php

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

if (isset($jugador1)) echo 'jugador1 establecido.<br>';
else echo 'jugador1 no establecido<br>';
if (isset($jugador2)) echo 'jugador2 establecido.<br>';
else echo 'jugador2 no establecido<br>';
if (isset($jugador3)) echo 'jugador3 establecido.<br>';
else echo 'jugador3 no establecido<br>';

if ($jugador1) echo 'jugador1 trueo.<br>';
else echo 'jugador1 false<br>';
if ($jugador2) echo 'jugador2 true.<br>';
else echo 'jugador2 false<br>';
if ($jugador3) echo 'jugador3 true.<br>';
else echo 'jugador3 false<br>';

/* Recoge objeto Jugador */
$jugador1 = $em->find('Jugador',1);

/* Recoge */
$jugador2 = $em->find('Jugador',50);

/* Recoge NULL */
$jugador3 = $repositorioJugadores->getJugador(55);

if (isset($jugador1)) echo 'jugador1 establecido.<br>';
else echo 'jugador1 no establecido<br>';
if (isset($jugador2)) echo 'jugador2 establecido.<br>';
else echo 'jugador2 no establecido<br>';
if (isset($jugador3)) echo 'jugador3 establecido<br>.';
else echo 'jugador3 no establecido<br>';

if ($jugador1) echo 'jugador1 true.<br>';
else echo 'jugador1 false<br>';
if ($jugador2) echo 'jugador2 true.<br>';
else echo 'jugador2 false<br>';
if ($jugador3) echo 'jugador3 true.<br>';
else echo 'jugador3 false<br>';

if ($jugador1 == NULL) echo 'jugador1 == NULL.<br>';
else echo 'jugador1 != NULL<br>';
if ($jugador == NULL) echo 'jugador2 == NULL.<br>';
else echo 'jugador2 != NULL<br>';
if ($jugador3 == NULL) echo 'jugador3 == NULL.<br>';
else echo 'jugador3 != NULL<br>';

echo '$jugador1->getId()' . $jugador1->getId() . '<BR>';

echo 'sizeof(NULL): ' . sizeof(NULL);

?>
