<?php
include_once('getid3/getid3/getid3.php');
class FileRenamer 
{
	private $destBaseLocation;
	
	
	/**
	 * @return the $destBaseLocation
	 */
	public function getDestBaseLocation() {
		return $this->destBaseLocation;
	}

	/**
	 * @param $destBaseLocation the $destBaseLocation to set
	 */
	public function setDestBaseLocation($destBaseLocation) {
		$this->destBaseLocation = $destBaseLocation;
	}

	public function RenameID3(FileCollection $files)
	{
		$getID3 = new getID3;
		foreach($files->getFileVOs() as $file)
		{
			/* @var $file FileVO */
			$info = $getID3->analyze($file->FullPath());
			if (isset($info["tags"]) && isset($info["tags"]["id3v2"]))
			{
				$this->HandleMp3($file, $info["tags"]["id3v2"] );
			}
		}
	}

	/**
	 * 
	 * @param $file
	 * @param $id3
	 */
	public function HandleMp3(FileVO $file, $tags)
	{
		$title  = FileRenamer::CleanString($tags["title"]["0"]);
		$album  = FileRenamer::CleanString($tags["album"]["0"]);
		$artist = FileRenamer::CleanString($tags["artist"]["0"]);
		
		$track  = FileRenamer::CleanString($tags["track_number"]["0"]);
		if ((int)$track < 10)
		{
			$track = "0{$track}";
		}
		
		// remove 'the' from the front of the artist name
		if (substr($artist, 0, 2) == 'the')
		{
			$artist = substr($artist, 3);
		}
		
		// remove 'the' from the front of the artist name
		if (substr($album, 0, 2) == 'the')
		{
			$album = substr($album, 3);
		}
		
		$dir = $this->BuildDirectoryStructure($album, $artist);
		
		$fullPath = $this->BuildFileName($dir, $title, $album, $artist, $track);
		
		echo $file->FullPath() . "\n";
		echo $fullPath . "\n";
		rename($file->FullPath(), $fullPath);
	}
	
	/**
	 * Build the proper directory structure to place the new MP3
	 * 
	 * @param string $album
	 * @param string $artist
	 */
	private function BuildDirectoryStructure($album, $artist)
	{
		$artistDir = $this->destBaseLocation . '/' . $artist;
		$albumDir = $artistDir . '/' . $album;
		
		if (!file_exists($artistDir))
		{
			mkdir($artistDir);
		}
		
		if (!file_exists($albumDir))
		{
			mkdir($albumDir);
		}
		
		return $albumDir;
	}
	
	/**
	 * Returns the full path and file name of where to write the file
	 * 
	 * @param string $title
	 * @param string $album
	 * @param string $artist
	 * @param string $track
	 */
	private function BuildFileName($dir, $title, $album, $artist, $track, $count=0)
	{
		$name = "{$artist}_-_{$album}_-_{$track}_-_{$title}";

		if ($count > 0)
		{
			$name = $name . "_($count)";
		}
		
		$name = $name . '.mp3';
		$fullPath = $dir . '/' . $name;
		if (file_exists($fullPath))
		{
			error_log('File already exists: ' . $fullPath);
			return $this->BuildFileName($dir, $title, $album, $artist, $track, $count + 1);
		}
		else
		{
			return $fullPath;
		}
	}
	
	public static function CleanString($str)
	{
		$str = strtolower($str);
		$str = trim($str);
		$str = str_replace('&', 'and', $str);	
		return $str;	
	}
}
?>