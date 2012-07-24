<?php

use Doctrine\ORM\Tools\Setup;

echo 'Rev 2<br>Cargando bootstrap_doctrine.php...<br>';

$lib = "";
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

$conn = array(
    'driver'   => 'pdo_mysql',
    'host'     => $host,
    'dbname'   => $dbname,
    'user'     => $user,
    'password' => $pass,
);

$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

echo 'create';

function GetMyEntityManager()
{
    global $entityManager;
    return $entityManager;
}

echo 'bootstrap_doctrine.php cargado.<br>';

?>