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
require_once "../entities/Comentario.php";
require_once "../entities/Jugador.php";

$em = GetMyEntityManager();
$numUltimaJornada = $em->getRepository('Jornada')->numUltimaJornada();

?>

<label>Jornada:</label>
<input id="numJornada" type="text" value="<?= $numUltimaJornada ?>" />
<br>

<? for ($i = 0; $i < 15; $i++) { ?>
<div id="partido-<?= $i ?>">

<select id="equipo1">
    <option selected="selected">Athletic de Bilbao</option>
    <option>Atlético de Madrid</option>
    <option>FC Barcelona</option>
    <option>Real Betis</option>
    <option>Celta de Vigo</option>
    <option>Deportivo de La Coruña</option>
    <option>Espanyol</option>
    <option>Getafe</option>
    <option>Granada</option>
    <option>Levante</option>
    <option>Málaga</option>
    <option>Mallorca</option>
    <option>Osasuna</option>
    <option>Rayo vallecano</option>
    <option>Real Madrid</option>
    <option>Real Sociedad</option>
    <option>Sevilla</option>
    <option>Valencia</option>
    <option>Valladolid</option>
    <option>Zaragozz</option>
</select>
<label>-</label>
<select id="equipo2">
    <option selected="selected">Athletic de Bilbao</option>
    <option>Atlético de Madrid</option>
    <option>FC Barcelona</option>
    <option>Real Betis</option>
    <option>Celta de Vigo</option>
    <option>Deportivo de La Coruña</option>
    <option>Espanyol</option>
    <option>Getafe</option>
    <option>Granada</option>
    <option>Levante</option>
    <option>Málaga</option>
    <option>Mallorca</option>
    <option>Osasuna</option>
    <option>Rayo vallecano</option>
    <option>Real Madrid</option>
    <option>Real Sociedad</option>
    <option>Sevilla</option>
    <option>Valencia</option>
    <option>Valladolid</option>
    <option>Zaragoza</option>
</select>
</div>
<? } ?>
<input id="partidosSubmit" type="submit" />
<SCRIPT>
$('#partidosSubmit').click(function() {
    url = "crearPartidosResultado.php";
    var datos = {};
    datos['usuario'] = $('#usuario').val();
    datos['contrasena'] = $('#contrasena').val();
    datos['numJornada'] = $('#numJornada').val();

    var i;
    for (i = 0;i < 15;i++) {
        datos['partido-'+i+'-1'] = $('#partido-'+i+' #equipo1 option:selected').val();
        datos['partido-'+i+'-2'] = $('#partido-'+i+' #equipo2 option:selected').val();
    }

    $.post(url,datos,function(data) {
        $('#resultado').html(data);
    });
});
</SCRIPT>