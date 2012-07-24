<?php

use Doctrine\ORM\Tools\Setup;

require_once 'Doctrine/ORM/Tools/Setup.php';

echo 'Rev 5<br>Cargando bootstrap_doctrine.php...<br>';

if (getenv('MYSQL_DB_HOST') == FALSE) {
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
    'host'     => getenv('MYSQL_DB_HOST'),
    'dbname'   => getenv('MYSQL_DB_NAME'),
    'user'     => getenv('MYSQL_USERNAME'),
    'password' => getenv('MYSQL_PASSWORD'),);
}

Setup::registerAutoloadDirectory($lib);

echo 'registerAutoloadDirectory<br>';

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(
    array(__DIR__."/entities"), $isDevMode);

echo 'createAnnotationMetadataConfiguration<br>';

$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

echo 'create';

function GetMyEntityManager()
{
    global $entityManager;
    return $entityManager;
}

echo 'bootstrap_doctrine.php cargado.<br>';

?>