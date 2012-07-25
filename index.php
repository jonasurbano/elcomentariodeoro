<?php

require_once 'bootstrap.php';

echo time();

?>

<!DOCTYPE html>
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
        <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
        <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"
            type="text/javascript"  ></script>
        <script type="text/javascript" src="scripts/script.js"></script>
        <script type="text/javascript" src="scripts/script2.js"></script>
        <script type="text/javascript" src="scripts/fb.js"></script>
        
    </head>
    <body>
        <div id=fb-root"></div>
        
        <script type="text/javascript">

    window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo AppInfo::appID(); ?>',
          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html',
          status     : true,
          cookie     : true,
          xfbml      : true,
          oauth      : true
        });

        FB.Event.subscribe('auth.login', function(response) {
          window.location = window.location;
        });

        FB.Canvas.setAutoGrow();
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>

        
    <h2>Bienvenido a YoS&eacute;DeF&uacute;tbol</h2>
        
        <? 
        
require_once('utils.php');
require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
    'appId' => AppInfo::appID(),
    'secret' => AppInfo::appSecret(),
    'cookie' => true,
));

        
$idFacebook = $facebook->getUser();
if ($idFacebook) {
    try {
        $basic = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        if (!$facebook->getUser()) {
            header('Location: ' . AppInfo::getUrl($_SERVER['REQUEST_URI']));
            exit();
        }
    }
}

    echo 'id de fb: ' . $idFacebook . '<br>';

$em = GetMyEntityManager();
$numJornada = $em->getRepository('Jornada')->getNumJornada();
$partidos = $em->getRepository('Partido')->getPartidos($numJornada);

$repositorioComentarios = $em->getRepository('Comentario');
$repositorioJugadores = $em->getRepository('Jugador');
$repositorioPronostico = $em->getRepository('Pronostico');
$jugador = $repositorioJugadores->getJugador($idFacebook);
        
        
        
        if (isset($basic)) { ?>
        <div class="principal">
            <div class="cabecera">
                <div class="cabecera-foto" style="background-image: url(https://graph.facebook.com/<?php echo he($idFacebook); ?>/picture?type=normal)"></div>
                <div class="cabecera-nombre"><?= he(idx($basic, 'name')) ?></div>
            </div>
            <div class="partidos">
                <div id="partidos-centrar">
            <? foreach ($partidos as $partido) {
                if (isset($jugador)) {
                    $resultado = $repositorioPronostico->
                        getResultado($partido->getId(),$jugador->getId());
                }
            ?>
                <div class="partido" id="<?= $partido->getId() ?>">
                    <div class="club"><?= htmlentities($partido->getClub1()); ?></div><div class="resultado"><div class="uno <? if (isset($resultado) &&  $resultado == '1') echo ', borde-rojo' ?>
                        ">1</div><div class="x <? if (isset($resultado) && $resultado == 'x') echo ', borde-rojo' ?>">X</div><div class="dos <? if (isset($resultado) && $resultado == '2') echo ', borde-rojo' ?>">2</div></div><div class="club"><?= htmlentities($partido->getClub2()); ?></div><input type="text" class="comentar" value="<?
                    if (isset($jugador)) {
                        $c = $repositorioComentarios->getComentario($partido->getId(),$jugador->getId());
                        if (!isset($c)) {
                            echo 'Ens&eacute;&ntilde;anos f&uacute;tbol...';
                        } else {
                            echo $c->getComentario();
                        }
                    } else {
                        echo 'Ens&eacute;&ntilde;anos f&uacute;tbol...';
                    }
                    ?>" /><div class="btnComentariosAmigos">A</div><div class="btnComentariosRecientes">R</div><div class="btnComentariosMejores">M</div>
                    <div class="comentarios" id="comentarios-<?= $partido->getId() ?>"></div>
                    <div class="comentar-panel"></div>
                </div>
            <? $resultado = NULL; } ?>
                </div>
            </div>
            <div class="partidos-janterior"></div>
            <div class="estadisticas">
                <div class="estadisticas-jugador"></div>
                <div class="estadisticasGlobales">
                    <div class="rankingPronosticos"></div>
                    <div class="rankingComentarios"></div>
                </div>
            </div>
            <div class="otrasSecciones">
                <div class="btnJornadaAnterior">Jornada anterior</div>
                <div class="btnEstadisticas">Estad&iacute;sticas</div>
            </div></div>
        <div id="jornada"><?= $numJornada ?></div>
        <div id="depuracion">Jornada: <?= $numJornada; ?><br><?= he(idx($basic, 'id')) ?></div>
        <?php } else { ?>
        <div>
            <h1>Bienvenido</h1>
            <div class="fb-login-button" data-scope="user_likes,user_photos"></div>
        </div>
        <?php } ?>

    </body>
</html>
