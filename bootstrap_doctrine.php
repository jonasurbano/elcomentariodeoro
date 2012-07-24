<?php

# Extrae las variables $host, $user, $pass y $path.
extract(parse_url($_ENV["DATABASE_URL"]));

use Doctrine\ORM\Tools\Setup;

require_once 'Doctrine/ORM/Tools/Setup.php';

$lib = "";
Doctrine\ORM\Tools\Setup::registerAutoloadDirectory($lib);

$host = getenv('MYSQL_DB_HOST'),
$user = getenv('MYSQL_USERNAME'),
$pass = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DB_NAME');

$conn = array(
    'driver'   => 'pdo_pgsql',
    'host'     => $host,
    'dbname'   => $dbname,
    'user'     => $user,
    'password' => $pass
);

$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);


function GetMyEntityManager()
{
    global $entityManager;
    return $entityManager;
}

?>