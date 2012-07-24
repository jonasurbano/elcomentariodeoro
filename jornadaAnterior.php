<?php

if (!isset($_GET['jornada'])) die();

require_once 'bootstrap.php';

$em = GetMyEntityManager();

$numJornada = (int)$_GET['jornada'] - 1;
$partidos = $em->getRepository('Partido')->getPartidos($numJornada);
$repositorioComentarios = $em->getRepository('Comentario');
$repositorioPronostico = $em->getRepository('Pronostico');

$idFacebook = $facebook->getUser();
$repositorioJugadores = $em->getRepository('Jugador');
$jugador = $repositorioJugadores->getJugador($idFacebook);

$sumaSemanal = 0;

?>

<div id="partidos-centrar-janterior">
    <?
    foreach ($partidos as $partido) {
        $comentario = $repositorioComentarios->getComentario($partido->getId(),$jugador->getId());
        $resultado = $repositorioPronostico->getResultado($partido->getId(), $jugador->getId());
    ?>
    <div class="partido-janterior" id="<?= $partido->getId() ?>">
        <div class="club"><?= htmlentities($partido->getClub1()); ?></div><div class="resultado"><div class="uno <? if (isset($resultado) && $resultado == '1') echo ', borde-rojo' ?>
            ">1</div><div class="x <? if (isset($resultado) && $resultado == 'x') echo ', borde-rojo' ?>">X</div><div class="dos <? if (isset($resultado) && $resultado == '2') echo ', borde-rojo' ?>">2</div></div><div class="club"><?= htmlentities($partido->getClub2()); ?></div><input type="text" class="comentar" value="<?
            if (isset($jugador)) {
                $c = $repositorioComentarios->getComentario($partido->getId(), $jugador->getId());
                if (isset($c)) {
                    echo $c->getComentario();
                } else {
                    echo 'Ens&eacute;&ntilde;anos f&uacute;tbol...';
                }
            } else {
                echo 'Ens&eacute;&ntilde;anos f&uacute;tbol...';
            }
            ?>" /><div class="btnComentariosAmigos">A</div><div class="btnComentariosRecientes">R</div><div class="btnComentariosMejores">M</div><div class="puntuacionPronostico<?
            if ($partido->getResultado() == $resultado) {
                $sumaSemanal += 3;
                echo '">+3';
            } else echo 'Cero">0'; ?></div><div class="<? 
            if (isset($comentario)) {
                $votosComentarios = $comentario->getVotos();
            } else $votosComentarios = 0;
            $sumaSemanal += $votosComentarios;
            if ($votosComentarios < 0) 
                echo 'puntuacionComentarioNegativa';
            else if ($votosComentarios == 0)
                echo 'puntuacionComentarioCero';
            else echo 'puntuacionComentarioPositiva';
            echo '">' . $votosComentarios; 
            
            ?></div>
            <div class="comentarios" id="comentarios-<?= $partido->getId() ?>"></div>
            <div class="comentar-panel"></div>
        </div>
<? $resultado = NULL; 
$votosComentarios = null;
$comentario = null;
} ?><div class="resultadoSemanal">Resultado semanal: <?= $sumaSemanal ?></div></div>
