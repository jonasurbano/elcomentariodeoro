<?php

use Doctrine\ORM\Tools\Setup;

require_once 'Doctrine/ORM/Tools/Setup.php';
require_once 'Doctrine\ORM\EntityManager';

echo 'Rev 5<br>Cargando bootstrap_doctrine.php...<br>';

if (!isset(getenv('MYSQL_DB_HOST'))) {
    $lib = '';
    
    $conn = array(
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'dbname'   => 'ysdf',
    'user'     => 'root',
    'password' => '');
} else {
    $lib = __DIR__;

    $conn = array(
    'driver'   => 'pdo_mysql',
    'host'     => $host,
    'dbname'   => $dbname,
    'user'     => $user,
    'password' => $pass,);
}

Setup::registerAutoloadDirectory($lib);

echo 'registerAutoloadDirectory<br>';

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(
    array(__DIR__."/entities"), $isDevMode);

echo 'createAnnotationMetadataConfiguration<br>';

$host = getenv('MYSQL_DB_HOST');
$user = getenv('MYSQL_USERNAME');
$pass = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DB_NAME');

echo 'dbname = ' . $dbname . '<br>';

$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

echo 'create';

function GetMyEntityManager()
{
    global $entityManager;
    return $entityManager;
}

echo 'bootstrap_doctrine.php cargado.<br>';

?>