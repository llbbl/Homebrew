<?php
// run non-scrapper scheduled tasks

require_once('config.php');
require_once('class/Autoloader.php');

$s = new MedicineScheduler();

$s->Run('today');

/*
// uncomment if you want to run a set of dates
$date = new DateTime('2013-09-15');

for($i = 0; $i<51; $i++)
{
    $date = $date->add(new DateInterval("P1D"));
    $s->Run($date->format('Y-m-d'));
}
*/


