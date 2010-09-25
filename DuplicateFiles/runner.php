<?php
include_once('DirectoryBrowser.php');
include_once('DuplicateFinder.php');

$start = '/home/cmack/Documents/ebay';

$browser = new DirectoryBrowser();
$files = $browser->Walk($start);

$duplicator = new DuplicateFinder();
$dups = $duplicator->Find($files);

echo print_r($dups, true);

?>