<?php

/**
 * Guarda un comentario en la base de datos.
 * Se realizan varias comprobaciones:
 * 1. Los parámetros POST hayan sido establecidos.
 * 2. Exista el partido al que se refiere el parámetro.
 * 3. La jornada existe.
 * 4. La fecha tope de la jornada no haya pasado.
 * 5. El usuario esté autenticado en Facebook.
 * 6. No exista un comentario para ese partido y de ese jugador.
 */

if (!isset($_POST['idPartido'])) exit();
if (!isset($_POST['comentario'])) exit();

require_once 'bootstrap.php';

$em = GetMyEntityManager();

$partido = $em->find('Partido',$_POST['idPartido']);
if (!$partido) exit();

$jornada = $partido->getJornada();
if (!$jornada) exit('error');
if (new DateTime > $jornada->getFechaTope())  exit('error');

$repositorioComentarios = $em->getRepository('Comentario');
$repositorioJugadores = $em->getRepository('Jugador');

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit('error');

$escritor = $repositorioJugadores->getJugador($idFacebook);
if (!$escritor) {
    $escritor = new Jugador($idFacebook);
    $em->persist($escritor);
    $em->flush($escritor);
}

/**
 * Almacena el comentario si no se ha guardado ya. Para evitar
 * que los jugadoes voten comentarios y se modifiquen después.
 */
$existeComentario = $repositorioComentarios->
    existeComentario($_POST['idPartido'],$escritor->getId());

$textComentario = $_POST['comentario'];
$nuevoComentario = NULL;
if (!$existeComentario) {
    $nuevoComentario = new Comentario($textComentario,$escritor,$partido);
    $em->persist($nuevoComentario);
    $em->flush($nuevoComentario);
    echo $nuevoComentario->getId();
} else echo 'error';

?>
