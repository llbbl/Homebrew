<?php 

$toJSON = new stdClass();

$id = new stdClass();
$id->key 	= true;
$id->title	= 'Id';
$id->create = false;
$id->edit 	= false;

$id_name = $type . 'Id';
$toJSON->$id_name = $id;


$date = new stdClass();
$date->title	= 'Date';
$date->width	= '23%';
$date->create = false;
$date->edit 	= true;

$date_name = $type . 'Date';
$toJSON->$date_name = $date;

$name = new stdClass();
$name->title	= 'Event';
$name->create = false;
$name->edit 	= false;

$name_name = $type . 'Name';
$toJSON->$name_name = $name;

$note = new stdClass();
$note->title	= 'Note';
$note->create 	= false;
$note->edit 	= true;

$note_name = $type . 'Note';
$toJSON->$note_name = $note;

echo json_encode($toJSON);
?>