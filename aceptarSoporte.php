<?php

if (!isset($_POST['texto'])) exit('No llegó información.');

require_once 'libs/Swift/lib/swift_required.php';

$transport = Swift_SmtpTransport::newInstance("smtp.gmail.com",465,"ssl")
    ->setUsername("jonasur@gmail.com")
    ->setPassword("mir34anda");

$mailer = Swift_Mailer::newInstance($transport);

$subject = "Soporte";
$to_addresses = "jonasur@gmail.com";
$body = $_POST['texto'];

$message = Swift_Message::newInstance($subject)
    ->setFrom(array("jonasur@gmail.com" => "jonasur"))
    ->setTo($to_addresses)
    ->setBody($body);

$result = $mailer->send($message);

echo $result;

?>