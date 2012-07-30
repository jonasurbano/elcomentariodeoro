<!DOCTYPE>
<HTML>
    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <TITLE></TITLE>
    </HEAD>
    <BODY>
<?php

/*
if (!isset($_SERVER['HTTPS']) ||
    ($_SERVER['HTTPS'] != 'on' || $_SERVER['HTTPS'] != 1) ||
    !isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ||
    $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
    die('La conexión tiene que ser https.');
}
*/

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.');


if ($_POST['usuario'] == 'jonas' && $_POST['contrasena'] == 'jack')
    echo 'ok.';

?>
    </BODY>
</HTML>