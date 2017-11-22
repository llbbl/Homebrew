<?php

require_once('./vendor/autoload.php');

$file = file_get_contents('./json/pact-example.json');

$pathFinder = new Flow\JSONPath\JSONPath(\json_decode($file));
$results = $pathFinder->find("$.consumer.name");
echo print_r($results,true);

$results = $pathFinder->find("$.interactions.*.response.body.types[2].name");
echo print_r($results,true);


$results = $pathFinder->find("$.fail.this");
echo print_r($results,true);
