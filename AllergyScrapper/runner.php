<?php
include_once('config.php');
include_once('class/Scraper.php');
include_once('class/Persister.php');

$s = new Scraper('http://www.kvue.com/weather/allergy-forecast');
$s->attach(new Persister());
$s->scrape();
