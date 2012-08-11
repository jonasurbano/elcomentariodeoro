<?php require_once 'bootstrap.php'; ?>

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
        <meta property="og:description" content="" />
        <meta property="og:description" content="Demuestra todo lo que sabes de f&uacute;tbol y descubre qui&eacute;n controla. Todo y m&aacute;s en <?= $nombreAplicacion ?>." />

        <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
        <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />
        <link rel="stylesheet" href="stylesheets/ysdf.css" media="Screen" type="text/css" />


        <!--[if IE]>
        <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
        </script>
        <![endif]-->
    </head>

<body style="margin:auto; background-color: #000;">

    <div id=fb-root"></div>

    <script type="text/javascript">

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?php echo AppInfo::appID(); ?>',
            channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html',
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

    <a href="<? AppInfo::getUrl() ?>"><img src="images/elcomentariodeoro-solonombre.jpg" /></a>

<?

$em = GetMyEntityManager();
$repositorioComentarios = $em->getRepository('Comentario');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $comentario = $repositorioComentarios->find($_GET['id']);
}

if (isset($comentario) && $comentario) {

    $fql = "SELECT name, pic FROM profile WHERE id =" .
        $comentario->getEscritor()->getIdFacebook() . ";";

    $array = $facebook->api(array( 'method' => 'fql.query',
                                    'query' => $fql,));

    $a = reset($array);

?>

    <div class="comentario" style="border:none;" id="com-<?= $comentario->getId() ?>">
        <div class="comentario-cabecera">
            <div class="comentario-cabecera-foto" style="background-image:
                url(<?= $a['pic'] ?>);"></div>
            <div class="comentario-cabecera-nombre"><?= $a['name'] ?></div>
        </div>
        <div class="comentario-texto"><?
        $partido = $comentario->getPartido();
            $club1 = $partido->getClub1();
            $club2 = $partido->getClub2();
            echo '<b>Partido: ' . $club1 . ' - ' . $club2 . '.</b> ' .
            $comentario->getComentario() ?></div>
        <div class="comentario-pie">
            <div class="votos"><?
                 $votos = $comentario->getVotos();
                 if ($votos == 1)
                     echo '1 voto';
                 else if ($votos == -1)
                     echo '-1 voto';
                 else
                     echo $comentario->getVotos() . ' votos';
?>
            </div>
        </div>
    </div>
    <? if ($facebook->getUser()) { ?>
        <fb:comments style="background-color:#fff" href="<?= AppInfo::getUrl('verComentario.php?id=' .
            $comentario->getId() ); ?>" num_posts="3" width="670"></fb:comments>
    <? } else { ?>
        <div class="fb-login-button" data-scope="publish_stream"></div>
    <? } } ?>
    <br><br>
    <a href="<? AppInfo::getUrl() ?>"><img src="images/banner.jpg" /></a>
    <div style="width:670px; color:#ff0; font-size:1.6em; text-align:center;
         line-height: 25px;">
Acierta los resultados de la jornada.<br>
Comenta qué pasará en cada partido.<br>
Lee los comentarios de tus amigos.<br>
Vota y responde otros comentarios.
    </div>
</body>
</html>