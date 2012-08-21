<?php

date_default_timezone_set("Europe/Madrid");

echo 'Tiempo del sistema: ' . date_format(new DateTime,DateTime::W3C) . '<BR>';

//if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
//    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
//        die('E1: La conexión tiene que ser HTTPS');
//} else die('E2: La conexión tiene que ser HTTPS');

?>
<!DOCTYPE>
<HTML>
    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <TITLE></TITLE>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"
            type="text/javascript"  ></script>
    </HEAD>
    <BODY>
        <div id="login" style="display:inline-block">
        <LABEL>Usuario:</LABEL>
        <INPUT id="usuario" name="usuario" type="text" />
        <LABEL>Contraseña:</LABEL>
        <INPUT id="contrasena" name="contrasena" type="password" />
        </div>
        <SELECT>
            <OPTION selected="selected">Crear jornada</OPTION>
            <OPTION>Crear partidos</OPTION>
            <OPTION>Puntuar partidos</OPTION>
            <OPTION>Asignar puntos a jugadores</OPTION>
        </SELECT>

        <INPUT id="submit" name="submit" type="submit" />

        <div id="contenedor"></div>
        <div id="resultado"></div>
        <SCRIPT type="text/javascript">
            $('#submit').click(function() {
                var seleccion = $('option:selected').html();
                if (seleccion == 'Crear jornada') url = 'crearJornada.php';
                else if (seleccion == 'Crear partidos') url = 'crearPartidos.php';
                else if (seleccion == 'Puntuar partidos') url = 'puntuarPartidos.php';
                else if (seleccion == 'Asignar puntos a jugadores') url = 'asignarPuntos.php';

                if (url) {
                    $.post(url, { usuario: $('#usuario').val(), contrasena: $('#contrasena').val()
                        },function(data) {
                            $('#login').hide();
                            $('#contenedor').html(data);
                            $('#resultado').empty();
                        });
                }
            })
        </SCRIPT>

    </BODY>
</HTML>