<?php

ini_set( "display_errors", 0);

if (!isset($_POST['texto'])) exit('No llegó información.');

require_once 'libs/Swift/lib/swift_required.php';

$transport = Swift_SmtpTransport::newInstance("smtp.gmail.com",465,"ssl")
    ->setUsername("soporte.elcomentariodeoro@gmail.com")
    ->setPassword("miragooglewave");

$mailer = Swift_Mailer::newInstance($transport);

$subject = "Soporte";
$to_addresses = "jonasur@gmail.com";
$body = $_POST['texto'];

$message = Swift_Message::newInstance($subject)
    ->setFrom(array("jonasur@gmail.com" => "jonasur"))
    ->setTo($to_addresses)
    ->setBody($body);

$result = $mailer->send($message);
if ($result)
    echo 'Enviado correctamente. Gracias.';
else echo 'Ha ocurrido un problema. Por favor, inténtelo más tarde.';

?>