<?php

if (!isset($_GET['opcion'])) exit();
if ($_GET['opcion'] != 1 && $_GET['opcion'] != 2) exit();
if (!isset($_GET['idComentario']) || !is_numeric($_GET['idComentario'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$comentario = $em->find("Comentario",$_GET['idComentario']);
if (!$comentario) exit('error');

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit();

$jugador = $em->getRepository('Jugador')->getJugador($idFacebook);
if (!$jugador) {
    $jugador = new Jugador($idFacebook);
    $em->persist($jugador);
    $em->flush($jugador);
}

$opcion = $_GET['opcion'];
if (($opcion == 1) && (!$jugador->gustaComentarioAJugador($comentario))) {
    if ($jugador->noGustaComentarioAJugador($comentario)) {
        $jugador->eliminarcomentarioNoGustado($comentario);
    }
    $jugador->comentarioGustado($comentario);
    $em->flush();

} else if (($opcion == 2) && (!$jugador->noGustaComentarioAJugador($comentario)))
{
    if ($jugador->gustaComentarioAJugador($comentario)) {
        $jugador->eliminarcomentarioGustado($comentario);
    }
    $jugador->comentarioNoGustado($comentario);
    $em->flush();
}

$votos = $comentario->getVotos();
if ($votos == 1) echo '1 voto';
else if ($votos == -1) echo '-1 voto';
else echo $comentario->getVotos() . ' votos';

?>
