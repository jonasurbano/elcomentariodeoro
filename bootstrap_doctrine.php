<?php

echo 'Cargando bootstrap_doctrine.php...<br>';

$lib = "";
Doctrine\ORM\Tools\Setup::registerAutoloadDirectory($lib);

$host = getenv('MYSQL_DB_HOST');
$user = getenv('MYSQL_USERNAME');
$pass = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DB_NAME');

$conn = array(
    'driver'   => 'pdo_mysql',
    'host'     => $host,
    'dbname'   => $dbname,
    'user'     => $user,
    'password' => $pass,
);

$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);


function GetMyEntityManager()
{
    global $entityManager;
    return $entityManager;
}

echo 'bootstrap_doctrine.php cargado.<br>';

?>