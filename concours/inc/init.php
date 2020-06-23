<?php 

$config = parse_ini_file( __DIR__ . '/../config/config.ini');

define('URL', $config['url']);

$pdo = new PDO(
    'mysql:host=' . $config['host'] .';dbname=' . $config['dbname'] .';chartset=utf8',
    $config['user'],
    $config['password'],
    array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);
 
require_once('functions.php');