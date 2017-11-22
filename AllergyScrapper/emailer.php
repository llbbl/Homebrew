<?php
require_once('config.php');
require_once('class/Autoloader.php');

$s = new Scraper('http://www.kvue.com/weather/allergy-forecast');

//$s->add_mode(Scraper::MODE_NO_OVERWRITE);
//$s->add_mode(Scraper::MODE_NO_CLEANUP);

$s->attach(new Alerter());
$s->scrape();
