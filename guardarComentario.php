<?php

if (!isset($_POST['idPartido'])) die();
if (!isset($_POST['comentario'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioComentarios = $em->getRepository('Comentario');
$repositorioJugadores = $em->getRepository('Jugador');

$idPartido = $_POST['idPartido'];
$partido = $em->find("Partido",$idPartido);

$idFacebook = $facebook->getUser();
$escritor = $repositorioJugadores->getJugador($idFacebook);
if (!isset($escritor)) {
    $escritor = new Jugador($idFacebook);
    $em->persist($escritor);
    $em->flush($escritor);
}

/**
 * Almacena el comentario si no se ha guardado ya. Para evitar
 * que los jugadoes voten comentarios y se modifiquen después.
 */
$existeComentario = $repositorioComentarios->
    existeComentario($idPartido,$escritor->getId());

$textComentario = $_POST['comentario'];
$nuevoComentario = NULL;
if (!$existeComentario) {
    $nuevoComentario = new Comentario($textComentario,$escritor,$partido);  
    $em->persist($nuevoComentario);
    $em->flush($nuevoComentario);
}

?>
