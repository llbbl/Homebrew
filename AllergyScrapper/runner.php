<?php
include_once('config.php');
include_once('class/Scrapper.php');

$s = new Scrapper('http://www.kvue.com/weather/allergy-forecast');
$s->scrap();
