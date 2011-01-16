<?php
include_once('FileVO.php');

class FileCollection
{
	private $fileVOs;
	
	public function __construct()
	{
		$this->fileVOs = array();
	}
	
	/**
	 * @return the $fileVOs
	 */
	public function getFileVOs() 
	{
		return $this->fileVOs;
	}

	public function AddFile(FileVO $file)
	{
		// only add the file it this exact fileVO has no been added before
		if (!in_array("$file", $this->fileVOs))
		{
			$this->fileVOs["$file"] = $file;
		}
	}
	
	public function count()
	{
		if (!isset($this->fileVOs))
		{
			return 0;
		}
		
		return count($this->fileVOs);
	}
	
	public function getIterator()
	{
		return new ArrayIterator($this->fileVOs);
	}
	
	public function Sort($sortMethod)
	{
		if ($sortMethod == 'md5')
		{
			uasort($this->fileVOs, array($this, 'SortByMd5'));
		}
		else
		{	
			throw new Exception('Unsupported sort method: ' . $sortMethod);
		}
	}
	
	public function SortByMd5(FileVO $a, FileVO $b)
	{
		if ($a->getMd5() < $b->getMd5())
		{
			return 1;
		}
		
		return -1;
	}
	
	/**
	 * Merge this collection with another collection
	 * 
	 * @param FileCollection $collection
	 */
	public function Merge(FileCollection $collection)
	{
		if ($collection->count() > 0)
		{
			foreach($collection->getFileVOs() as $file)
			{
				$this->AddFile($file);
			}
		}
	}
	
	public function __toString()
	{
		$x = array();
		foreach($this->fileVOs as $file)
		{
			$x[] = "$file";			
		}
		return implode("\n", $x);
	}
}

?>