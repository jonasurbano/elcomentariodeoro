<?php

if (!isset($_GET['opcion'])) die();
if (!isset($_GET['idComentario'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$idComentario = $_GET['idComentario'];
$comentario = $em->find("Comentario", (int)$idComentario);

$idFacebook = $facebook->getUser();
$jugador = $em->getRepository('Jugador')->getJugador($idFacebook);
if (!isset($jugador)) {
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
