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

	public function Move($dupLocation)
	{
		$movedDups = new FileCollection();
		foreach($this->duplicates as $files)
		{
			/* @var $files FileCollection */
			if (count($files->getFileVOs()) > 1)
			{
				$i = 0;
				foreach($files->getFileVOs() as $file)
				{	
					/* @var $file FileVO */
					// keep the first
					if ($i != 0)
					{
						$movedDups->AddFile($file);
						$fullPath = $dupLocation . '/' . $file->getFileName();
						echo $file->FullPath() . "\n";
						echo $fullPath . "\n";
						rename($file->FullPath(), $fullPath);
					}
					
					$i++;
				}
			}
		}
	}
}

?>