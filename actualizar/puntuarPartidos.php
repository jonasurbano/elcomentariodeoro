<?php

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS.');
} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.<br>');
if ($_POST['usuario'] != 'jonas' || $_POST['contrasena'] != 'jack')
    die('Autenticación no correcta.<br>');

if (!class_exists("Doctrine\Common\Version", false)) {
    $lib = '../';
    require_once "../bootstrap_doctrine.php";
}

require_once '../repositories/JornadaRepositorio.php';
require_once '../repositories/PartidoRepositorio.php';
require_once '../repositories/PronosticoRepositorio.php';
require_once "../entities/Jornada.php";
require_once "../entities/Partido.php";
require_once "../entities/Comentario.php";
require_once "../entities/Jugador.php";
require_once "../entities/Pronostico.php";

$em = GetMyEntityManager();

/**
 * Si se recibe numJornada, hay que restar 1 a este valor
 * para mostrar los partidos de la jornada anterior.
 */
if (isset($_POST['numJornada'])) {
    $numJornada = ((int)$_POST['numJornada']) - 1;
} else $numJornada = $em->getRepository('Jornada')->numUltimaJornada();

echo 'Núm. de jornada: <span id="numJornada">' . $numJornada . '</span><br>';

$jornada = $em->find('Jornada', $numJornada);
if (!$jornada) exit('No existe la jornada ' . $numJornada . '.');

$partidos = $jornada->getPartidos();
foreach ($partidos as $partido) {
    ?>
    <div class="partido" id="<?= $partido->getId() ?>">
        <label><?= $partido->getClub1() ?> - <?= $partido->getClub2() ?></label>
        <select>
            <option>1</option>
            <option>x</option>
            <option>2</option>
        </select>
    </div>
<? } ?>
<input id="submitPuntuaciones" type="submit" value="Puntuar partidos" />
<input id="jornadaAnterior" type="submit" value="Jornada anterior" />
<script>
$('#jornadaAnterior').click(function() {
    $.post('puntuarPartidos.php',{ usuario: $('#usuario').val(),
        contrasena: $('#contrasena').val(), numJornada: $('#numJornada').html()
    },function(data) {
        $('#contenedor').html(data);
    });
});

$('#submitPuntuaciones').click(function() {
    var datos = {};
    datos['usuario'] = $('#usuario').val();
    datos['contrasena'] = $('#contrasena').val();

    $('div.partido').each(function(index) {
        datos['id-'+index] = $(this).attr('id');
        datos['r-'+index] = $(this).find('option:selected').val();
    });
    $.post('puntuarPartidoResultado.php',datos,function(data) {
       $('#resultado').html(data);
    });
})

</script>