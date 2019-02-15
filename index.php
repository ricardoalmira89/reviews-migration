<?php


require_once 'vendor/autoload.php';
require_once 'Migrator/Migrator.php';


$map = array(
    'migration' => ['repc','crossbay', 'bko','livewell', 'nmpc', 'btd', 'pt', 'miracle']
);


foreach ($map as $destination => $sources){
    foreach ($sources as $source){

        echo "\r\n\r\n\r\nMigrando ". $source. " \r\n\r\n";
        $migrator = new Migrator(array(
            'source' => $source,
            'destination' => $destination,
        ));

        $migrator->migrate();
    }
}