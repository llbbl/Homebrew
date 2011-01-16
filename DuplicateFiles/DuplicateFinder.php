<?php
include_once('FileCollection.php');

class DuplicateFinder 
{
	private $duplicates;
	
	/**
	 * @return array
	 */
	public function Find(FileCollection $files)
	{
		// sort all the files by md5 sum.
		// this puts all the files with the same md5sum next to each other
		$files->Sort('md5');
		
		$lastFile = null;
		foreach($files->getFileVOs() as $file)
		{
			/* @var FileVO $file */
			if ($lastFile != null && $file->getMd5() == $lastFile->getMd5())
			{
				$this->AddDuplicate($file, $lastFile);
			}
			
			$lastFile = $file;
		}
	
		return $this->duplicates; 
	}
	
	private function AddDuplicate(FileVO $file, FileVO $lastFile)
	{
		$md5 = $file->getMd5();

		if (!isset($this->duplicates[$md5]))
		{
			$this->duplicates[$md5] = new FileCollection();
			
			// I *think* we only need to add the last file if we are starting a new collection
			$this->duplicates[$md5]->AddFile($lastFile);
		}
		
		$this->duplicates[$md5]->AddFile($file);
	}

	/**
	 * Move duplicates to the duplication location.  If $preferred is '', the first duplicate is keep and others are moved 
	 * 
	 * @param string $dupLocation
	 * @param string $preferred
	 */
	public function Move($dupLocation, $preferred='')
	{
		foreach($this->duplicates as $files)
		{
			/* @var $files FileCollection */
			if (count($files->getFileVOs()) > 1)
			{
				$this->Pick($files, $dupLocation, $preferred);
			}
		}
	}
	
	
	/**
	 * If there is a preferred location to keep ( based on substring ), pick that one
	 * Else, move the file
	 * 
	 * @param FileCollection $files
	 * @param string $dupLocation
	 * @param string $preferred
	 */
	public function Pick(FileCollection $files, $dupLocation, $preferred='')
	{
		$i = 0;
		$keeper = $i;
		
		if ($preferred != '')
		{
			foreach($files->getFileVOs() as $file)
			{	
				/* @var $file FileVO */
				if (stripos($file->FullPath(), $preferred) !== false)
				{
					$keeper = $i;
					break;	
				}
				
				$i++;
			}
		}
		
		$i = 0;
		foreach($files->getFileVOs() as $file)
		{	
			/* @var $file FileVO */
			if ($i != $keeper)
			{
				$this->MoveFile($file, $dupLocation);
			}
			
			$i++;
		}
	}
	
	/**
	 * Move the actual file
	 * 
	 * @param FileVO $file
	 * @param string $dupLocation
	 */
	private function MoveFile(FileVO $file , $dupLocation)
	{
		$fullPath = $dupLocation . '/' . $file->getFileName();
		echo $file->FullPath() . "\n";
		echo $fullPath . "\n";
		rename($file->FullPath(), $fullPath);
	}
}

?>