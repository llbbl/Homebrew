<?php
include_once('DirectoryBrowser.php');
include_once('DuplicateFinder.php');
include_once('FileMover.php');
include_once('FileRenamer.php');

$start1 = '/storage/Pictures';
$start2 = '/storage/Backup'; 
$dupLoc = '/storage/DupPictures';

$preferredLoc = '/storage/Pictures';

$browser = new DirectoryBrowser();

echo 'Walking ' . $start1 . "\n";
$files = $browser->Walk($start1);
echo 'Found ' . $files->count() . " files in {$start1}\n";

echo 'Walking ' . $start2 . "\n";
$files2 = $browser->Walk($start2);
echo 'Found ' . $files2->count() . " files in {$start2}\n";

// Merging
$files->Merge($files2);
echo 'Found ' . $files->count() . " combined files\n";

$duplicator = new DuplicateFinder();
$dups = $duplicator->Find($files);
$duplicator->Move($dupLoc, $preferredLoc);
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