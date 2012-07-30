<!DOCTYPE>
<HTML>
    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <TITLE></TITLE>
    </HEAD>
    <BODY>

<?php

$fecha = new DateTime('now',new DateTimeZone('UTC'));
echo 'Tiempo del sistema: ' . date_format($fecha,DateTime::W3C) . '<BR>';

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS');
} else die('E2: La conexión tiene que ser HTTPS');



?>
        <FORM action="actualizar.php/" metho="POST">
            <LABEL>Usuario:</LABEL>
            <INPUT id="usuario" name="usuario" type="text" />
            <LABEL>Contraseña:</LABEL>
            <INPUT id="contrasena" name="contrasena" type="password" />
            <INPUT id="submit" type="submit" />
        </FORM>
    </BODY>
</HTML>