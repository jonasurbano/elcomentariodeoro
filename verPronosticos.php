<?php require_once('bootstrap.php'); ?>

<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="es">
    <head>
        <title><?= $nombreAplicacion ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta property="og:title" content="<?= $nombreAplicacion ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
        <meta property="og:image" content="<?php echo AppInfo::getUrl('/images/icono.png'); ?>" />
        <meta property="og:site_name" content="<?= $nombreAplicacion ?>" />
        <meta property="og:description" content="Demuestra todo lo que sabes de f&uacute;tbol y descubre qui&eacute;n controla. Todo y m&aacute;s en <?= $nombreAplicacion ?>." />

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="stylesheets/ysdf.css" media="Screen" type="text/css" />

    </head>

<body style="margin:auto; background-color: #000;">
    <a href="<?= $urlFacebook ?>"><div id="landingPage-superior"></div></a>
    
<?

if (isset($_GET['id']) && is_numeric($_GET['id']) &&
    isset($_GET['j']) && is_numeric($_GET['j'])) {

$em = GetMyEntityManager();
$jugador = $em->find('Jugador', $_GET['id']);

if ($jugador && $jugador->isJornadaPublica($_GET['j'])) {

    $jornada = $em->find('Jornada', $_GET['j']);
    $partidos = $jornada->getPartidos();

    if ($partidos)
    {

        $repositorioPronostico = $em->getRepository('Pronostico');

        $fql = "SELECT name, pic FROM profile WHERE id =" .
                $jugador->getIdFacebook() . ";";

            $array = $facebook->api(array( 'method' => 'fql.query',
                                            'query' => $fql,));

            $a = reset($array);
        ?>
    
    <div class="jugador-pronostico">
        <img src="<?= $a['pic'] ?>" width="64" height="64" />
        <span><?= $a['name'] ?></span>
    </div>

<?
        foreach ($partidos as $partido) {
            $idPartido = $partido->getId();
            $resultado = $repositorioPronostico->
                getResultado($idPartido,$jugador->getId());
?>
<div class="partido-pronostico" id="<?= $idPartido ?>">
    <div class="club-pronostico"><?= $partido->getClub1() ?></div>
    <div class="resultado-pronostico">
        <div class="uno <? if (isset($resultado) &&  $resultado == '1') echo ', borde-rojo' ?>"
            >1</div><div class="x <? if (isset($resultado) && $resultado == 'x') echo ', borde-rojo' ?>"
            >X</div><div class="dos <? if (isset($resultado) && $resultado == '2') echo ', borde-rojo' ?>"
            >2</div></div>
        <div class="club-pronostico"><?= $partido->getClub2() ?></div>
</div>
<?
        }
    }
}
}

?>

<br><br>
    <a href="<?= $urlFacebook ?>"><div id="landingPage-inferior"></div></a><br><br>
    <div id="landingPage-desc">
Acierta los resultados de la jornada.<br>
Comenta qué pasará en cada partido.<br>
Lee los comentarios de tus amigos.<br>
Vota y responde otros comentarios.
    </div><br><br>
</body>
</html>
