<? require_once 'bootstrap.php'; ?>

<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="es" style="overflow:hidden">
    <head>
        <title>El comentario de oro</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta property="og:title" content="<?= $nombreAplicacion ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<? echo AppInfo::getUrl(); ?>" />
        <meta property="og:image" content="<? echo AppInfo::getUrl('images/icono.png'); ?>" />
        <meta property="og:site_name" content="<?= $nombreAplicacion ?>" />
        <meta property="og:description" content="'Cuéntanos qué pasará en la liga. En <?= $nombreAplicacion ?> podrás puntuar y escribir todo lo que quieras sobre los partidos de la jornada. Además podrás leer lo que piensan tus amigos y más..." />

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="stylesheets/ysdf.css" media="Screen" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"
        type="text/javascript"></script>
        <script type="text/javascript" src="scripts/script.js"></script>


    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->

    </head>
</head>
<body style="overflow:hidden">
    <div id=fb-root"></div>

    <script type="text/javascript">

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<? echo AppInfo::appID(); ?>',
            channelUrl : '//<? echo $_SERVER["HTTP_HOST"]; ?>/channel.html',
            status     : true,
            cookie     : true,
            xfbml      : true,
            oauth      : true,
            xfbml      : true
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
        js.src = "//connect.facebook.net/es_ES/all.js";
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
    if (!$jornada) exit('Jornada. Hoy no hay ' . $nombreAplicacion . ', lo sentimos.');

    $numJornada = $jornada->getId();
    ?> <div id="jornada"><?= $numJornada ?></div> <?

    if ($jornada->getFechaTope() < new DateTime) {
        ?><div id="jugando" style="display: none;">jugando</div><?
    }

    $partidos = $jornada->getPartidos();
    if (!$partidos) exit('Partidos. Hoy no hay "' . $nombreAplicacion . '", lo sentimos.');

    $repositorioComentarios = $em->getRepository('Comentario');
      $repositorioJugadores = $em->getRepository('Jugador');
    $repositorioPronostico = $em->getRepository('Pronostico');

    /* $jugador puede ser NULL */
    $jugador = $repositorioJugadores->getJugador($idFacebook);

?>
    <div class="principal">
       <div class="cabecera">
           <a href="index.php"><div style="width:120px; height:95px; float:left;"></div></a>
       </div>
        <div class="partidos">
            <div id="partidos-centrar">
                <div class="partidos-texto">¿Cómo quedarán los equipos? Tienes hasta el sábado para comentar y puntuar los partidos.</div>
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
                    <input type="text" class="comentar" value="<?
                    if ($jugador) {
                        $c = $repositorioComentarios->getComentario($idPartido,$jugador->getId());
                        if (!$c) echo 'Comentar..."';
                        else echo $c->getComentario() . '" readonly="readonly" id="com-' . $c->getId() . '"';
                    } else {
                        echo 'Comentar..."';
                    }
                    ?> /><div class="btnComentarios"><div class="btnComentariosAmigos"></div><div class="btnComentariosRecientes"></div><div class="btnComentariosMejores"></div>
                    </div>
                    <div class="comentarios" id="comentarios-<?= $idPartido ?>"></div>
                    <div class="comentar-panel"></div>
                </div>
            <? $resultado = NULL; } ?>
            <div class="compartirPronosticos">Compartir mis pronósticos en Facebook</div>
        </div></div>
            <div class="partidos-janterior"></div>

            <div class="estadisticas">
                <div class="estadisticas-jugador"></div>
                <div class="estadisticasGlobales" ></div>
            </div>
    </div>
            <div class="otrasSecciones">
                <div id="btnJornadaAnterior">Jornada anterior</div>
                <div id="btnEstadisticas">Rankings</div>
            </div>

        <? } else { ?>
        <div>
            <h1>Bienvenido</h1>
            <div class="fb-login-button" data-scope="publish_stream"></div>
        </div>
        <? }


        if (isset($jugador) && (!$jugador || !$jugador->getSigueClub()
            || $jugador->getSigueClub() == '')) {
        ?><div id="elegirClub">
            <label>¡Hola! Queremos saber de qué equipo eres</label><br><br>
            <select>
                 <option selected="selected">Alavés</option>
                 <option>Alcorcón</option>
                 <option>Almería</option>
                 <option>At. Madrid</option>
                 <option>Athletic Club</option>
                 <option>Barcelona</option>
                 <option>Barcelona B</option>
                 <option>Betis</option>
                 <option>Cacereño</option>
                 <option>Celta</option>
                 <option>Córdoba</option>
                 <option>Deportivo</option>
                 <option>Elche</option>
                 <option>Espanyol</option>
                 <option>Getafe</option>
                 <option>Girona</option>
                 <option>Granada</option>
                 <option>Guadalajara</option>
                 <option>Hércules</option>
                 <option>Huesca</option>
                 <option>Las palmas</option>
                 <option>Levante</option>
                 <option>Lugo</option>
                 <option>Málaga</option>
                 <option>Mallorca</option>
                 <option>Melilla</option>
                 <option>Mirandés</option>
                 <option>Murcia</option>
                 <option>Numancia</option>
                 <option>Osasuna</option>
                 <option>Ourense</option>
                 <option>Ponferradina</option>
                 <option>R. M. Castilla</option>
                 <option>R. Madrid</option>
                 <option>Racing</option>
                 <option>Rayo Vallecano</option>
                 <option>Real Sociedad</option>
                 <option>Real Unión</option>
                 <option>Reus Deportivo</option>
                 <option>Recreativo</option>
                 <option>Sabadell</option>
                 <option>Sant Andreu</option>
                 <option>Sevilla</option>
                 <option>Sporting de Gijón</option>
                 <option>Tenerife</option>
                 <option>Valencia</option>
                 <option>Valladolid</option>
                 <option>Villarreal</option>
                 <option>Xerez</option>
                 <option>Zaragoza</option>
            </select>
            <div class="guardarClub">Guardar</div>
            <div class="cerrarElegirClub">Cerrar</div>
        </div><? } ?>

        <fb:like send="false" width="450" show_faces="false"></fb:like>
    </body>
</html>
