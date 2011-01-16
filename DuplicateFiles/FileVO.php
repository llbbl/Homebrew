<?php

class FileVO 
{
	private $md5;
	private $fileName;
	private $path;
	private $size;
	private $extension;
	private $isDir;
	
	public function __construct($fileName, $path)
	{
		$this->setFileName($fileName);
		$this->setPath($path);
		$this->FindMd5();
		$this->FindFileSize();
		$this->FindExtension();
		
		$this->isDir = is_dir($this->FullPath());
	}
	
	/**
	 * @return the $extension
	 */
	public function getExtension() {
		return $this->extension;
	}
	
	/**
	 * @return the $size
	 */
	public function getSize() {
		return $this->size;
	}
	
	/**
	 * @return the $md5
	 */
	public function getMd5() {
		return $this->md5;
	}

	/**
	 * @return the $fileName
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * @return the $path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return bool true if the file is a directory
	 */
	public function IsDir()
	{
		return $this->isDir;
	}
	
	/**
	 * Find the md5sum of the file
	 */
	public function FindMd5() 
	{
		if (!file_exists($this->FullPath()))
		{
			throw new Exception('Unable to find file: ' . $this->FullPath());
		}
		
		$this->md5 = md5_file($this->FullPath());
	}

	/**
	 * Find the size of the file
	 */
	public function FindFileSize()
	{
		if (!file_exists($this->FullPath()))
		{
			throw new Exception('Unable to find file: ' . $this->FullPath());
		}
		
		$this->size = filesize($this->FullPath());
	}
	
	/**
	 * Find the extension of the file
	 */
	public function FindExtension()
	{
		if (!file_exists($this->FullPath()))
		{
			throw new Exception('Unable to find file: ' . $this->FullPath());
		}
		
		$parts = pathinfo($this->FullPath());
		if (isset($parts['extension']))
		{
			$this->extension = strtolower($parts['extension']);
		}
		else if (!is_dir($this->FullPath()))
		{
			error_log('No extension for ' . $this->FullPath());
		}
	}
	
	public function FullPath()
	{
		return $this->path . '/' . $this->fileName;
	}
	
	/**
	 * @param $fileName the $fileName to set
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * @param $path the $path to set
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	public function __toString()
	{
		return $this->path . '/'. $this->fileName . ' - ' . $this->md5; 
	}
	
}

?>