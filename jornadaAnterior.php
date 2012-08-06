<?php

if (!isset($_GET['jornada'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();

$numJornada = (int)$_GET['jornada'] - 1;
$partidos = $em->getRepository('Partido')->getPartidos($numJornada);
if (!$partidos) exit('No encontramos los partidos. Lo sentimos.');

$repositorioComentarios = $em->getRepository('Comentario');
$repositorioPronostico = $em->getRepository('Pronostico');

$idFacebook = $facebook->getUser();
$repositorioJugadores = $em->getRepository('Jugador');
$jugador = $repositorioJugadores->getJugador($idFacebook);

$sumaSemanal = 0;

?>

<div id="partidos-centrar-janterior">
    <div class="partidos-texto">Estos son los resultados de la jornada anterior. Para cada partido verás a la derecha los puntos conseguidos por el pronóstico (1, X, 2) y los puntos conseguidos por los votos de tu comentario.</div>
<?
foreach ($partidos as $partido) {
    if ($jugador) {
        $comentario = $repositorioComentarios->getComentario($partido->getId(),$jugador->getId());
        $resultado = $repositorioPronostico->getResultado($partido->getId(), $jugador->getId());
    }
?>
    <div class="partido-janterior" id="<?= $partido->getId() ?>">
        <div class="club"><?= he($partido->getClub1()); ?></div><div class="resultado"><div class="uno <? if (isset($resultado) && $resultado == '1') echo ', borde-rojo' ?>
            ">1</div><div class="x <? if (isset($resultado) && $resultado == 'x') echo ', borde-rojo' ?>">X</div><div class="dos <? if (isset($resultado) && $resultado == '2') echo ', borde-rojo' ?>">2</div></div><div class="club"><?= htmlentities($partido->getClub2()); ?></div><input type="text" class="comentar" readonly="readonly" value="<?
            if (isset($comentario) && $comentario) echo $comentario->getComentario();
            else echo '';
            ?>" /><div class="btnComentariosAmigos">A</div><div class="btnComentariosRecientes">R</div><div class="btnComentariosMejores">M</div><div class="puntuacion puntuacionPronostico<?
            if ($partido->pronosticoAcertado($resultado)) {
                $sumaSemanal += 3;
                echo ' puntuacionPositiva">3';
            } else echo ' puntuacionCero">0'; ?></div><div class="puntuacion puntuacionComentario<?
            if ($comentario) $votosComentarios = $comentario->getVotos();
            else $votosComentarios = 0;
            $sumaSemanal += $votosComentarios;
            if ($votosComentarios < 0) echo ' puntuacionNegativa"';
            else if ($votosComentarios == 0) echo ' puntuacionCero"';
            else echo ' puntuacionPositiva"'; echo '>' . $votosComentarios;
            ?></div>
            <div class="comentarios" id="comentarios-<?= $partido->getId() ?>"></div>
            <div class="comentar-panel"></div>
        </div>
<? $resultado = NULL;
$votosComentarios = null;
$comentario = null;
} ?><div class="resultadoSemanal">Resultado semanal:<?
if ($sumaSemanal == 1 || $sumaSemanal == -1) echo ' 1 punto.';
else echo ' <span class="puntos">' . $sumaSemanal . '</span> puntos. ';
?><span class="compartirPuntuacionSemanalEnFb">Compartir</span></div></div>
