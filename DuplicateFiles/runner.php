<?php
include_once('DirectoryBrowser.php');
include_once('DuplicateFinder.php');
include_once('FileMover.php');
include_once('FileRenamer.php');

$start = '/storage/Pictures'; 
$dupLoc = '/storage/DupPictures';

echo 'Walking ' . $start . "\n";
$browser = new DirectoryBrowser();
$files = $browser->Walk($start);

echo 'Found ' . $files->count() . ' files'. "\n";

$duplicator = new DuplicateFinder();
$dups = $duplicator->Find($files);
$duplicator->Move($dupLoc);
echo print_r($dups, true);

/*
echo "Moving files\n";
$mover = new FileMover();
$mover->Move($files);
*/

/*
echo "Renaming MP3 files\n";
$renamer = new FileRenamer();
$renamer->RenameID3($files);
*/
?>