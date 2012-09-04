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
<label>-</label>
<select id="equipo2">
<option selected="selected">Alcorcón</option>
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
 <select id="equipo2">

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