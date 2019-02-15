<?php


require_once 'vendor/autoload.php';
require_once 'Migrator/Migrator.php';

$migrator = new Migrator($argv[0]);
$migrator->migrate();






