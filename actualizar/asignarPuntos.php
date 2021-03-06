<?php

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS.');
} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/JornadaRepositorio.php';
require_once '../repositories/PartidoRepositorio.php';
require_once '../repositories/PronosticoRepositorio.php';
require_once "../entities/Jornada.php";
require_once "../entities/Partido.php";
require_once "../entities/Comentario.php";
require_once "../entities/Jugador.php";
require_once "../entities/Pronostico.php";

$em = GetMyEntityManager();

/**
 * Si se recibe numJornada, hay que restar 1 a este valor
 * para mostrar los partidos de la jornada anterior.
 */
if (isset($_POST['numJornada'])) {
    $numJornada = ((int)$_POST['numJornada']) - 1;
} else $numJornada = $em->getRepository('Jornada')->numUltimaJornada();

header('Content-type: text/html; charset=utf-8');

$jornada = $em->find('Jornada', $numJornada);
if (!isset($jornada)) {
    exit('No se ha podido obtener la información de la jornada.');
}

echo 'Jornada número: ' . $numJornada . '<br>';

$partidos = $jornada->getPartidos();
if (sizeof($partidos) == 0)
    exit('No hay partidos asignados a esta jornada.');

$puntuaciones = array();

foreach ($partidos as $partido) {
    $resultado = $partido->getResultado();
    if (!$resultado) {
        echo 'El partido ' . $partido->getClub1() . ' - ' .
            $partido->getClub2() . ' no se ha puntuado';
        break;
    }

    echo 'Puntuando partido ' . $partido->getClub1() . ' - ' . $partido->getClub2() . '<br>';

    $pronosticos = $em->getRepository('Pronostico')
        ->getPronosticos($partido->getId());

    if (!$pronosticos || sizeof($pronosticos) == 0) {
        echo 'El partido ' . $partido->getClub1() . ' - ' .
            $partido->getClub2() . ' no tiene pronosticos';
        break;
    }

    foreach ($pronosticos as $pronostico) {
        if ($pronostico->getResultado() == $resultado) {
            $jugador = $pronostico->getJugador();
            $jugador->mas3Puntos();

            echo 'El jugador ' . $jugador->getIdFacebook() . ' ha acertado el partido.<br>';

            $em->persist($jugador);

            if (!array_key_exists($jugador->getIdFacebook(), $puntuaciones))
                $puntuaciones[$jugador->getIdFacebook()] = 3;
            else $puntuaciones[$jugador->getIdFacebook()] += 3;
        }
    }
}

$em->flush();

arsort($puntuaciones);

foreach ($puntuaciones as $id => $p) { ?>
<div class="puntuacion">
    <span class="idf"><?= $id ?>'</span>
    <span class="nombre"></span> ha conseguido <span class="puntos"><?= $p ?></span> puntos.
</div>

<? }