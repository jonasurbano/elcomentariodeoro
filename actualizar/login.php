<!DOCTYPE>
<HTML>
    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <TITLE></TITLE>
    </HEAD>
    <BODY>

<?php

$fecha = new DateTime;
echo 'Tiempo del sistema: ' . date_format($fecha,DateTime::W3C);

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
    && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
    die('La conexión tiene que ser HTTPS');
}

phpinfo();

?>
        <FORM action="actualizar.php/" metho="POST">
            <LABEL>Usuario:</LABEL>
            <INPUT id="usuario" type="text" />
            <LABEL>Contraseña:</LABEL>
            <INPUT id="contrasena" type="password" />
            <INPUT id="submit" type="submit" />
        </FORM>
    </BODY>
</HTML>