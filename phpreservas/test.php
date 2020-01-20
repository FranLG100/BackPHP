<?php

require 'vendor/autoload.php';

$client = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');

$db = $client->listDatabases();

$result = $client->pruebas->primera_coleccion->find();//->toArray();
//print_r($result);
foreach ($result as $document) {
    echo $document['nombre'] . "\n";
}
?>