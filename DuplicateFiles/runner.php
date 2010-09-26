<?php
include_once('DirectoryBrowser.php');
include_once('DuplicateFinder.php');
include_once('FileMover.php');

$start = '/storage/recover';

echo 'Walking ' . $start;
$browser = new DirectoryBrowser();
$files = $browser->Walk($start);

echo 'Found ' . count($files) . ' files';
/*
$duplicator = new DuplicateFinder();
$dups = $duplicator->Find($files);
echo print_r($dups, true);
*/

echo 'Moving files';
$mover = new FileMover();
$mover->Move($files);

?>