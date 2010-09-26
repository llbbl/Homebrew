<?php
include_once('FileCollection.php');

class FileMover 
{
	const BASE_DIR = '/storage/';
	const SMALL_PIC_THRESHOLD = '524288'; // change to bytes
	
	private $destList;
	private $failures;
	private $existed;
	
	private $successCnt = 0; // 
	private $failCnt = 0;	 // moving  the file failed
	private $existedCnt = 0; // files that were not moved because the file already existed in the destination
	
	public function __construct()
	{
		$this->destList = array();
		$this->destList['music'] = FileMover::BASE_DIR . 'RecoveredMusic';
		$this->destList['smallPic'] = FileMover::BASE_DIR . 'Pictures/RecoveredPics/Small/';
		$this->destList['largePic'] = FileMover::BASE_DIR . 'Pictures/RecoveredPics/Large/';
	}
	
	public function Move(FileCollection $files)
	{
		foreach($files->getFileVOs() as $file)
		{
			/* @var $file FileVO */
			$dest = $this->FindDest($file->getExtension(), $file->getSize());
			
			// unable to find destination
			if ($dest == false)
			{
				continue;
			}
			
			$fullPath = $dest . $file->getFileName();
			
			if (!file_exists($fullPath))
			{
				$result = rename($file->FullPath(), $fullPath);
				
				if (!$result)
				{
					$this->failures[] = array('Old'=>$file->FullPath(), 'New'=>$fullPath);
					$this->failCnt++;
				}
				else
				{
					$this->successCnt++;
				}
				
			}
			else
			{
				$this->existed[] = array('Old'=>$file->FullPath(), 'New'=>$fullPath);
				$this->existedCnt++;
			}
		}

		$this->WriteReport();
	}
	
	private function WriteReport()
	{
		$fp = fopen('filemover.log', 'w+');
    	
		fwrite($fp,"Success Count: {$this->successCnt}\n");
		fwrite($fp,"Failure Count: {$this->failCnt}\n");
		fwrite($fp,"Existed Count: {$this->existedCnt}\n");

		if (count($this->failures) > 0)
		{
			fwrite($fp,"Failures:\n");
			foreach ($this->failures as $arr) 
	        {
	        	/* @var $educator EducatorVO */ 
	        	fwrite($fp, "Old Fail:" . $arr['Old'] . "\n");
	        	fwrite($fp, "New Fail:" . $arr['New'] . "\n");
	
	        }
		}
		
		if (count($this->existed) > 0)
		{
	        fwrite($fp,"Existed:\n");
			foreach ($this->existed as $arr) 
	        {
	        	/* @var $educator EducatorVO */ 
	        	fwrite($fp, "Old Exist:" . $arr['Old'] . "\n");
	        	fwrite($fp, "New Exist:" . $arr['New'] . "\n");
	
	        }
		}
        
        fclose($fp);
	}
	
	
	private function FindDest($ext, $size)
	{
		$ext = strtolower($ext);
		
		if ($ext == 'mp3')
		{
			return $this->destList['music'];
		}
		if ($ext == 'jpeg')
		{
			$ext = 'jpg';
		}
		
		$picFormats = array('gif', 'png', 'jpg');
		if (in_array($ext, $picFormats))
		{
			if ($size <= FileMover::SMALL_PIC_THRESHOLD)
			{
				return $this->destList['smallPic'] . $ext . '/';
			}
			
			return $this->destList['largePic'] . $ext . '/';
		}

		return false;
	}
}

?>