<?php

require_once 'bootstrap.php';

if (!isset($_GET['id'])) {
    ?><a href="index.php">
    <img src="images/logo_454_340.jpg" />
    </a> <?exit();
}

$em = GetMyEntityManager();
$repositorioComentarios = $em->getRepository('Comentario');

$id = (int)$_GET['id'];
$comentario = $repositorioComentarios->find($id);

if (!isset($comentario)) {
    ?><a href="index.php">
    <img src="images/logo_454_340.jpg" /></a><? exit();
}

?>
<html>
    <head>
        <title>YoS&eacute;DeF&uacute;tbol</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta property="og:title" content="YoS&eacute;DeF&uacute;tbol" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
        <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
        <meta property="og:site_name" content="YoS&eacute;DeF&uacute;tbol" />
        <meta property="og:description" content="" />
        <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />

        <link rel="stylesheet" href="stylesheets/ysdf_styles.css" type="text/css" />

        <script type="text/javascript" src="scripts/jquery.js"></script>
        <script type="text/javascript" src="scripts/script2.js"></script>
    </head>
<body>
    
<div class="comentario" id="com-<?= $comentario->getId() ?>">
<?
    $fql = "SELECT name, pic FROM profile WHERE id =" . 
        $comentario->getEscritor()->getIdFacebook() . ";";
            
    $array = $facebook->api(array( 'method' => 'fql.query',
                                    'query' => $fql,));

    $a = reset($array);?>
    
    <div class="comentario-cabecera">
        <div class="comentario-cabecera-foto" style="background-image: 
                url(<?= $a['pic'] ?>);"></div>
            <div class="comentario-cabecera-nombre"><?= $a['name'] ?></div>
    </div>
    <div class="comentario-texto"><?= $comentario->getComentario() ?></div>
    <div class="comentario-pie">
        <div class="votos"><? $votos = $comentario->getVotos(); 
        if ($votos == 1) echo '1 voto';
        else if ($votos == -1) echo '-1 voto';
        else echo $comentario->getVotos() . ' votos'; ?>
        </div>
        <div class="btnCompartirComentario">Compartir en Facebook</div>
    </div>
</div>

<?

$repositorioJugadores = $em->getRepository('Jugador');

$rankingPronosticos = $repositorioJugadores->
    posicionRankingPronosticos($comentario->getEscritor()->getId());

$rankingComentarios = $repositorioJugadores->
    posicionRankingComentarios($comentario->getEscritor()->getId());

?>

<div class="jugador-cabecera" id="
    <?= $comentario->getEscritor()->getIdFacebook() ?>">
    <p class="jugador-foto" style="background-image: url(
        <?= $a['pic'] ?>);"></p>
    <div class="jugador-nombre"><a target="_blank" href="
        <?= $a['url'] ?>"><?= $a['name'] ?></a></div>
    <div class="jugador-puntosPronostico">Puntos por pron&oacute;stico: 
        <?= $comentario->getEscritor()->getSumaPronosticos() ?></div>
    <div class="jugador-ranking-pronostico">Ranking: 
        <?= $rankingPronosticos ?>º</div>
    <div class="jugador-puntosComentario">Puntos por comentario: 
        <?= $comentario->getEscritor()->getSumaComentarios() ?></div>
    <div class="jugador-ranking-comentario">Ranking: 
        <?= $rankingComentarios ?>º</div>
</div><br><a href="index.php">
    <img src="images/logo_454_340.jpg" /></a>    
</body>
</html>