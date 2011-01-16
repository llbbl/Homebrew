<?php
include_once('DirectoryBrowser.php');
include_once('DuplicateFinder.php');
include_once('FileMover.php');
include_once('FileRenamer.php');

$start = '/storage/RecoveredMusic';
$dest = '/storage/Music';

$browser = new DirectoryBrowser();

echo 'Walking ' . $start . "\n";
$files = $browser->Walk($start);

echo "Renaming MP3 files\n";
$renamer = new FileRenamer();
$renamer->setDestBaseLocation($dest);
$renamer->RenameID3($files);


?>