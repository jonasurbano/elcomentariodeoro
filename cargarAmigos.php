<?php

/**
 * Carga la lista de amigos del jugador autenticado.
 *
 * Esquema si todo OK:
 *
 * Tantas veces como usuarios:
 * <div id="amigos">
 *  <div class="amigo" id="amigos-primera"></div> // Una vez
 *  <div class="amigo" div="ami-idFb">Id de la app.</div>
 * </div>
 *
 * Una vez:
 * <div id="listaAmigos"> Lista con ids de la app </div>
 */

require_once 'bootstrap.php';

$idFacebook = $facebook->getUser();
if (!$idFacebook) exit();

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$consulta = $facebook->api('/' . $idFacebook . '/friends?');
$amigosFb = idx($consulta, 'data', array());

?>

<div id="amigos">
    <div id="amigos-primera" class="amigo">Busca a tus amigos</div>
<?
$listaAmigos = '';
foreach ($amigosFb as $amigo) {
    $idFbAmigo = idx($amigo,'id');
    $idAmigo = $repositorioJugadores->getIdJugador($idFbAmigo);
    if ($idAmigo) $listaAmigos .= "'" . $idAmigo . "',";
?>
    <div id="ami-<?= $idFbAmigo ?>" class="amigo">
        <img width="25" height="25" src="https://graph.facebook.com/<?= $idFbAmigo ?>/picture?type=square" />
        <span><?= idx($amigo,'name'); ?></span>
    </div>
<? } ?>

</div>

<div id="listaAmigos" style="display:none;"><?=substr($listaAmigos,0,-1)?></div>
