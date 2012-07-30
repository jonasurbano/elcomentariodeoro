<!DOCTYPE>
<HTML>
    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <TITLE></TITLE>
    </HEAD>
    <BODY>
<?php

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')
        die('E1: La conexión tiene que ser HTTPS.');
} else die('E2: La conexión tiene que ser HTTPS');

if (!isset($_POST['usuario']) || !isset($_POST['contrasena']))
    die('Autenticación no recibida.');

if ($_POST['usuario'] == 'jonas' && $_POST['contrasena'] == 'jack')
    echo 'ok.';

?>
    </BODY>
</HTML>