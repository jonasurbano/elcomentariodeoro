<?php

/**
 * @param int offset Primer comentario a devolver por la consulta.
 */

if (!isset($_GET['offset']) || !is_numeric($_GET['offset'])
    || $_GET['offset'] < 0) exit();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$offset = $_GET['offset'];

$jugadores = $repositorioJugadores->rankingPronostico($offset);

$hayMasJugadores = sizeof($jugadores) >= 6;
if ($hayMasJugadores) {
    unset($jugadores[5]);
}


$lista_id = '';
foreach ($jugadores as $jugador) {
    $lista_id .= $jugador->getIdFacebook() . ',';
}
$lista_id = substr($lista_id,0,-1);

$fql = "SELECT name, url, pic FROM profile WHERE id IN (" .$lista_id . ")";
$array = $facebook->api(array( 'method' => 'fql.query',
                                'query' => $fql,));

$i = $offset + 1 ;

?>

<div class="rankingPronosticos">
    <div class="rankingPronosticos-cabecera"></div><?

foreach ($array as $key => $a) {
?>
    <div class="ranking-jugador-pronosticos" id="idf-<?= $jugadores[$key]->getIdFacebook() ?>">
        <div class="ranking-nombre"><?= $a['name']; ?></div>
        <div>
        <div class="ranking-numero"><?= $i ?>º</div>
        <div class="ranking-foto" style="background-image: url(<?= $a['pic'] ?>);"></div>
        <div class="ranking-puntos"><?= $jugadores[$key]->getSumaPronosticos(); ?>
            <div style="font-size:10px">ptos</div></div>
        </div>
        <div style="clear: left"></div><div class="ranking-url">
<? if ($jugadores[$key]->getIdFacebook() != $facebook->getUser()) { ?>
            <a target="_blank" href="<?= $a['url'] ?>">ver su perfil en Facebook</a>
<? } else { ?>
        <div class="compartirRankingEnFb">compartir en Facebook</div>
<? } ?>
    </div></div>
<? $i++; }

?></div><div class="rankingComentarios">
    <div class="rankingComentarios-cabecera"></div><?

$jugadores = $repositorioJugadores->rankingComentarios($offset);


if ($hayMasJugadores) {
    unset($jugadores[5]);
}

$lista_id = '';
foreach ($jugadores as $jugador) {
    $lista_id .= $jugador->getIdFacebook() . ',';
}
$lista_id = substr($lista_id,0,-1);

$fql = "SELECT name, url, pic FROM profile WHERE id IN (" .$lista_id . ")";
$array = $facebook->api(array( 'method' => 'fql.query',
                                'query' => $fql,));


$i =  $offset + 1;
foreach ($array as $key => $a) {
?>
    <div class="ranking-jugador-comentarios" id="idf-<?= $jugadores[$key]->getIdFacebook() ?>">
        <div class="ranking-nombre"><?= $a['name']; ?></div>
        <div>
        <div class="ranking-numero"><?= $i ?>º</div>
        <div class="ranking-foto" style="background-image: url(<?= $a['pic'] ?>);"></div>
        <div class="ranking-puntos"><?= $jugadores[$key]->getSumaComentarios(); ?>
            <div style="font-size:10px">ptos</div></div>
        </div>
        <div style="clear: left"></div>
        <div class="ranking-url">
<? if ($jugadores[$key]->getIdFacebook() != $facebook->getUser()) { ?>
            <a target="_blank" href="<?= $a['url'] ?>">ver su perfil en Facebook</a>
<? } else { ?>
           <div class="compartirRankingEnFb">compartir en Facebook</div>
<? } ?>
        </div>
    </div>
<? $i++; }

if ($hayMasJugadores) {
    ?><div class="ranking-masJugadores">M&aacute;s jugadores</div><?
}

if ($offset == 0) {

$clubes = $repositorioJugadores->rankingClubes();

?></div><div style="clear: both;"></div><div class="rankingClubes">
    <div class="partidos-texto">Este es el ranking de los clubes.</div>
    <div class="partidos-texto">Cada equipo obtiene los puntos que han conseguido sus seguidores.</div>
    <?

    foreach ($clubes as $club) {
    ?><div class="ranking-club">
        <span class="ranking-club-nombre"><?= $club['sigueClub'] ?></span>
        <span class="ranking-club-puntos"><?
        if ($club['suma'] == -1 || $club['suma'] == 1) echo $club['suma'] . ' punto';
        else echo $club['suma'] . ' puntos';
        ?>
        </span>
    </div>
    <?
}

}

?></div><div style="clear: both;"></div>