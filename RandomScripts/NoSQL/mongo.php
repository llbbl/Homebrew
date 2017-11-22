<?php

$m = new Mongo();
$db = $m->test;
$collection = $db->TestCollection;

$obj = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
//$collection->insert($obj);

// find everything in the collection
$cursor = $collection->find(array("name"=>"Mattersight"));

// iterate through the results
foreach ($cursor as $obj) 
{
	echo print_r($obj, true);
}

