<?php

if (!isset($_GET['idPartido'])) die();
if (!isset($_GET['resultado'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');
$repositorioPronostico = $em->getRepository('Pronostico');

$idPartido = $_POST['idPartido'];
$partido = $em->find("Partido",$idPartido);

$idFacebook = $facebook->getUser();
$jugador = $repositorioJugadores->getJugador($idFacebook);
if (!isset($jugador)) {
    $escritor = new Jugador($idFacebook);
    $em->persist($escritor);
    $em->flush($escritor);
}

$pronostico = $repositorioPronostico->
    getPronostico($idPartido,$jugador->getId());

$resultado = $_POST['resultado'];
if (!isset($pronostico)) {
    $partido = $em->find("Partido", $idPartido);
    $pronostico = new Pronostico($jugador,$partido,$resultado);
    $em->persist($pronostico);
} else {
    if ($pronostico->getResultado() != $resultado) {
        $pronostico->setResultado($resultado);
        $em->persist($pronostico);
    }
}
$em->flush();

?>
