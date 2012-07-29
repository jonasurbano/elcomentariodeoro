<?php

/**
 * @param int offset Primer comentario a devolver por la consulta.
 */

if (!isset($_GET['offset'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();
$repositorioJugadores = $em->getRepository('Jugador');

$offset = $_GET['offset'];

$jugadores = $repositorioJugadores->rankingPronostico($offset);
$hayMasJugadores = $repositorioJugadores->hayMasRanking($offset);

$lista_id = '';
foreach ($jugadores as $jugador) {
    $lista_id .= $jugador->getIdFacebook() . ',';
}
$lista_id = substr($lista_id,0,-1);

$fql = "SELECT name, url, pic FROM profile WHERE id IN (" .$lista_id . ")";
$array = $facebook->api(array( 'method' => 'fql.query',
                                'query' => $fql,));

$i = 1;

?><div class="rankingPronosticos"><?

foreach ($array as $key => $a) {
?>
    <div class="ranking-jugador" id="idf-<?= $jugadores[$key]->getIdFacebook() ?>">
        <div class="ranking-nombre"><?= $a['name']; ?></div>
        <div>
        <div class="ranking-numero"><?= $i ?>º</div>
        <div class="ranking-foto" style="background-image: url(<?= $a['pic'] ?>);"></div>
        <div class="ranking-puntos"><?= $jugadores[$key]->getSumaPronosticos(); ?>
            <div style="font-size:10px">ptos</div></div>
        </div>
        <div style="clear: left"></div>
        <div class="ranking-url">
            <a target="_blank" href="<?= $a['url'] ?>">ver su perfil en Facebook</a>
        </div>
    </div>
<? $i++; }

if ($hayMasJugadores) {
    ?><div class="ranking-masJugadores">M&aacute;s jugadores</div><?
}

?></div><div class="rankingComentarios"><?

$jugadores = $repositorioJugadores->rankingComentarios($offset);
$hayMasJugadores = $repositorioJugadores->hayMasRanking($offset);

$lista_id = '';
foreach ($jugadores as $jugador) {
    $lista_id .= $jugador->getIdFacebook() . ',';
}
$lista_id = substr($lista_id,0,-1);

$fql = "SELECT name, url, pic FROM profile WHERE id IN (" .$lista_id . ")";
$array = $facebook->api(array( 'method' => 'fql.query',
                                'query' => $fql,));


$i = 1;
foreach ($array as $key => $a) {
?>
    <div class="ranking-jugador" id="idf-<?= $jugadores[$key]->getIdFacebook() ?>">
        <div class="ranking-nombre"><?= $a['name']; ?></div>
        <div>
        <div class="ranking-numero"><?= $i ?>º</div>
        <div class="ranking-foto" style="background-image: url(<?= $a['pic'] ?>);"></div>
        <div class="ranking-puntos"><?= $jugadores[$key]->getSumaComentarios(); ?>
            <div style="font-size:10px">ptos</div></div>
        </div>
        <div style="clear: left"></div>
        <div class="ranking-url">
            <a target="_blank" href="<?= $a['url'] ?>">ver su perfil en Facebook</a>
        </div>
    </div>
<? $i++; }

if ($hayMasJugadores) {
    ?><div class="ranking-masJugadores">M&aacute;s jugadores</div><?
}

?></div><div style="clear: both;"></div>
