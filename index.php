<?php require_once 'bootstrap.php'; ?>

<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="es" style="overflow:hidden">
    <head>
        <title>YoS&eacute;DeF&uacute;tbol</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta property="og:title" content="YoSéDeFútbol" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
        <meta property="og:image" content="<?php echo AppInfo::getUrl('images/icono.png'); ?>" />
        <meta property="og:site_name" content="YoSéDeFútbol" />
        <meta property="og:description" content="'Cuéntanos qué pasará en la liga. En YoSéDeFútbol podrás puntuar y escribir todo lo que quieras sobre los partidos de la jornada. Además podrás leer lo que piensan tus amigos y más..." />

        <link rel="stylesheet" href="stylesheets/ysdf.css" media="Screen" type="text/css" />

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"
        type="text/javascript"  ></script>

        <script type="text/javascript" src="scripts/script.js"></script>
    </head>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
</head>
<body style="overflow:hidden">
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

<?

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

if (isset($basic)) {

    $em = GetMyEntityManager();

    $jornada = $em->getRepository('Jornada')->getJornada();
    if (!$jornada) exit('E1. Hoy no hay YoSéDeFútbol, lo sentimos.');

    $numJornada = $jornada->getId();
    ?> <div id="jornada"><?= $numJornada ?></div> <?

    if ($jornada->getFechaTope() < new DateTime) {
        ?><div id="jugando" style="display: none;">jugando</div><?
    }

    $partidos = $jornada->getPartidos();
    if (!$partidos) exit('E2. Hoy no hay YoSéDeFútbol, lo sentimos.');

    $repositorioComentarios = $em->getRepository('Comentario');
    $repositorioJugadores = $em->getRepository('Jugador');
    $repositorioPronostico = $em->getRepository('Pronostico');

    /* $jugador puede ser NULL */
    $jugador = $repositorioJugadores->getJugador($idFacebook);

?>
    <div class="principal">
        <div class="cabecera"></div>
        <div class="partidos">
            <div id="partidos-centrar">
                <div class="partidos-texto">¿Cómo quedarán los resultados?</div>
<?

    foreach ($partidos as $partido) {
        $idPartido = $partido->getId();
        if ($jugador) {
            $resultado = $repositorioPronostico->
                getResultado($idPartido,$jugador->getId());
        }
?>
                <div class="partido" id="<?= $idPartido ?>">
                    <div class="club"><?= $partido->getClub1() ?></div>
                    <div class="resultado">
                        <div class="uno <? if (isset($resultado) &&  $resultado == '1') echo ', borde-rojo' ?>"
                        >1</div><div class="x <? if (isset($resultado) && $resultado == 'x') echo ', borde-rojo' ?>"
                        >X</div><div class="dos <? if (isset($resultado) && $resultado == '2') echo ', borde-rojo' ?>"
                        >2</div></div>
                    <div class="club"><?= $partido->getClub2() ?></div>
                    <input type="text" class="comentar" value="
<?
                    if ($jugador) {
                        $c = $repositorioComentarios->getComentario($idPartido,$jugador->getId());
                        if (!$c) echo 'Comentar..."';
                        else echo $c->getComentario() . '" readonly="readonly"';
                    } else {
                        echo 'Comentar..."';
                    }
                    ?> /><div class="btnComentarios"><div class="btnComentariosAmigos">.</div><div class="btnComentariosRecientes">.</div><div class="btnComentariosMejores">.</div>
                    </div>
                    <div class="comentarios" id="comentarios-<?= $idPartido ?>"></div>
                    <div class="comentar-panel"></div>
                </div>
            <? $resultado = NULL; } ?>
                </div>
            </div>
            <div class="partidos-janterior"></div>
            <div class="estadisticas">
                <div class="estadisticas-jugador"></div>
                <div class="estadisticasGlobales" ></div>
            </div>
            <div class="otrasSecciones">
                <div id="btnJornadaAnterior">Jornada anterior</div>
                <div id="btnEstadisticas">Estad&iacute;sticas</div
                <div class="fb-like" data-send="false" data-width="50" data-show-faces="false"></div>
            </div></div>

        <?php } else { ?>
        <div>
            <h1>Bienvenido</h1>
            <div class="fb-login-button" data-scope="publish_stream"></div>
        </div>
        <?php }


        if (isset($jugador) && (!$jugador || !$jugador->getSigueClub())) {
        ?><div id="elegirClub">
            <label>¡Hola! Queremos saber de qué equipo eres</label><br><br>
            <select>
                <option selected="selected">At. Madrid</option>
                 <option>Athletic Club</option>
                 <option>Barcelona</option>
                 <option>Betis</option>
                 <option>Celta</option>
                 <option>Córdoba</option>
                 <option>Deportivo</option>
                 <option>Espanyol</option>
                 <option>Getafe</option>
                 <option>Granada</option>
                 <option>Hércules</option>
                 <option>Las palmas</option>
                 <option>Levante</option>
                 <option>Lugo</option>
                 <option>Málaga</option>
                 <option>Mallorca</option>
                 <option>Murcia</option>
                 <option>Numancia</option>
                 <option>Osasuna</option>
                 <option>Racing</option>
                 <option>Rayo vallecano</option>
                 <option>R. Madrid</option>
                 <option>Real Sociedad</option>
                 <option>Recreativo</option>
                 <option>Sevilla</option>
                 <option>Sporting de Gijón</option>
                 <option>Valencia</option>
                 <option>Valladolid</option>
                 <option>Xerez</option>
                 <option>Zaragoza</option>
            </select>
            <div class="guardar">Guardar</div>
        </div><? } ?>

        <fb:like send="false" width="450" show_faces="false"></fb:like>
    </body>
</html>
