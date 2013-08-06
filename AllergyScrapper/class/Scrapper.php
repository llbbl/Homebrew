<?php

class Scrapper 
{
	private $url;
	private $outFile;
	
	public function __construct($url)
	{
		$this->url = $url;
		
		$p = explode('/', $this->url);
		
		if (count($p) <= '3')
		{
			$fileName = 'index.html';
		}
		else
		{
			$fileName = array_pop($p);
		}
		
		$this->outFile = $fileName;
	}
	
	public function scrap()
	{
		$this->retrieve();
		$this->save();
		$this->cleanup();
	}
	
	public function save() 
	{
		// configuration
		$dbhost 	= DB_HOST;
		$dbname		= DB_DB;
		$dbuser		= DB_USER;
		$dbpass		= DB_PASS;
		
		// database connection
		$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
		
		// query
		$sql = "INSERT INTO PollenFile (FileDate,AllergyReport) VALUES (:now,:file)";
		
		$q = $conn->prepare($sql);
 		
		$q->execute(array(':now'=>date("Y-m-d H:i:s"), ':file'=>file_get_contents($this->outFile)));
		
	}
	
	private function retrieve()
	{
		$cmd = 'wget --referer="http://www.google.com" --user-agent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"';
		$cmd .= ' ' . $this->url;
		
		exec($cmd);
	}

	
	private function cleanup()
	{
		unlink($this->outFile);
	}
	
	
}