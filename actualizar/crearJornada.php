<?php

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS.');
} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

header('Content-type: text/html; charset=utf-8');

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/JornadaRepositorio.php';
require_once "../entities/Jornada.php";
require_once "../entities/Partido.php";

$em = GetMyEntityManager();
$jornadaActual = $em->getRepository('Jornada')->getJornada();
$numUltimaJornada = $em->getRepository('Jornada')->numUltimaJornada();

if (!isset($jornadaActual)) echo 'La jornada actual no está definida.';

?>

<h2>Crear siguiente jornada:</h2>
<LABEL>Núm. Jornada: </LABEL>
<INPUT id="numJornada" type="string" value="<?= $numUltimaJornada + 1 ?>" /><br>
<LABEL>Fecha/Hora cuando empieza el 1er partido:</LABEL>
<INPUT id="fechaTope" type="datetime-local" /><br>
<LABEL>Fecha/Hora cuando acaba el último partido:</LABEL>
<INPUT id="fechaResultados" type="datetime-local" /><br>
<INPUT id="submitJornada" type="submit" />

<SCRIPT>
    $('#submitJornada').click(function() {
        url = 'crearJornadaResultado.php';

       $.post(url,{ usuario: $('#usuario').val(),
            contrasena: $('#contrasena').val() ,
            numJornada:  $('#numJornada').val(),
            fechaTope: $('#fechaTope').val(),
            fechaResultados: $('#fechaResultados').val()
        }, function(data) {
            $('#resultado').html(data);
        });
    });

</script>