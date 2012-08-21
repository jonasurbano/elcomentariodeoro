<?php

/**
 * Guarda el equipo que sigue el jugador.
 * Crea el jugador si es necesario.
 * @param $_GET['club'] string. Obligatorio.
 */

if (!isset($_GET['club'])) exit('error');

$equipos = array('At. Madrid','Athletic Club','Barcelona','Betis','Celta','Córdoba',
    'Deportivo','Espanyol','Getafe','Granada','Hércules','Las palmas','Levante',
    'Lugo','Málaga','Mallorca','Murcia','Numancia','Osasuna','Racing',
    'Rayo vallecano','R. Madrid','Real Sociedad','Recreativo','Sevilla',
    'Sporting de Gijón','Valencia','Valladolid','Xerez','Zaragoza','Alcorcón',
    'Elche','Girona','Guadalajara','Huesca','Ponferradina','Sabadell');

$encontrado = false;
foreach ($equipos as $equipo) {
    if ($_GET['club'] == $equipo) $encontrado = true;
}
if (!$encontrado) exit('no-encontrado');

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit();

$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!$jugador) $jugador = new Jugador($idFacebook);

$jugador->setSigueClub($_GET['club']);

$em->persist($jugador);
$em->flush($jugador);

?>
