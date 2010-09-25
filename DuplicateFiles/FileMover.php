<?php

class FileMover 
{
	const BASE_DIR = '/backup/';
	const SMALL_PIC_THRESHOLD = '1mb'; // change to bytes
	private $destList;

	public function __construct()
	{
		$this->destList = array();
		$this->destList['music'] = FileMover::BASE_DIR . 'RecoveredMusic';
		$this->destList['smallPic'] = FileMover::BASE_DIR . 'Pictures/RecoveredPics/Small/';
		$this->destList['largePic'] = FileMover::BASE_DIR . 'Pictures/RecoveredPics/Large/';
	}
	
	public function Move()
	{
		
	}
	
	private function FindDest($ext, $size)
	{
		$ext = strtolower($ext);
		
		if ($ext == 'mp3')
		{
			return $this->destList['music'];
		}
		
		$picFormats = array('gif', 'png', 'jpg', 'jpeg');
		if (in_array($ext, $picFormats))
		{
			if ($size <= FileMover::SMALL_PIC_THRESHOLD)
			{
				return $this->destList['smallPic'];
			}
			
			return $this->destList['largePic'];
		}

		return false;
	}
}

?>