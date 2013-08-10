<?php
require_once('./class/Parser.php');
require_once('./class/AllergenVO.php');


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
	
	/**
	 * Controller for Scrapper
	 * 
	 */
	public function scrap()
	{
		$this->retrieve();
		$allergens = $this->parse();
		$this->save($allergens);
		$this->cleanup();
	}
	
	/**
	 * Encapsulate the parsing
	 * 
	 */
	public function parse()
	{
		$p = new Parser($this->outFile);
		return $p->parse();
	}
	
	/**
	 * Save raw file and allergy info
	 * 
	 * @todo break this out to a DAO
	 * @param array $allergens
	 */
	private function save($allergens) 
	{
		// configuration
		$dbhost 	= DB_HOST;
		$dbname		= DB_DB;
		$dbuser		= DB_USER;
		$dbpass		= DB_PASS;
		
		// database connection
		$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
		
		// query
		$this->save_file($conn);
		
 		if (count($allergens) > 0)
 		{
			foreach($allergens as $vo)
			{
				$this->save_environment($conn, $vo);
			}
 		}
	}
	
	/**
	 * Save the raw file
	 * 
	 * @param PDO connection $conn
	 */
	private function save_file($conn) 
	{
		// query
		$sql = "INSERT INTO PollenFile (FileDate,AllergyReport) VALUES (:now,:file)";
		$q = $conn->prepare($sql);
		$q->execute(array(':now'=>date("Y-m-d H:i:s"), ':file'=>file_get_contents($this->outFile)));
	}	
	
	
	
	/**
	 * Save into the Environment table
	 * 
	 * @param PDO $conn
	 * @param AllergenVO $allergenVO
	 */
	private function save_environment($conn, $allergenVO)
	{
		$id = $this->save_environment_type($conn, $allergenVO->get_type());
		$note = $allergenVO->get_category() . " " . $allergenVO->get_amount(); // @todo - break this up
		
		$sql = "INSERT INTO Environment (EnvironmentTypeId,EnvironmentDate, EnvironmentNote ) VALUES (:id, :now,:note)";
		$q = $conn->prepare($sql);
		$q->execute(array(':id'=>$id, ':now'=>date("Y-m-d H:i:s"), ':note'=>$note));
	}
	
	/**
	 * Retrieve EnvironmentTypeId and Save into the Environment Type table
	 *
	 * @param PDO $conn
	 * @param string $type - type of environment
	 * @return int $id - EnvironmentTypeId for $type
	 */
	private function save_environment_type ($conn, $type)
	{
		$sql = "SELECT EnvironmentTypeId FROM EnvironmentType WHERE EnvironmentName = :type";
		
		$q = $conn->prepare($sql);
		$q->execute(array(':type'=>$type));
		$result = $q->fetch(PDO::FETCH_ASSOC);
		
		if (!isset($result['EnvironmentTypeId']) )
		{
			$sql = "INSERT INTO EnvironmentType (EnvironmentName) VALUES (:type)";
			$q = $conn->prepare($sql);
			$q->execute(array(':type'=>$type));
			
			$id = $conn->lastInsertId();
		}
		else
		{
			$id = $result['EnvironmentTypeId'];
		}
		
		return $id;
	}
	
	/**
	 * Retrieve URL to parse
	 */
	private function retrieve()
	{
		$cmd = 'wget --referer="http://www.google.com" --user-agent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"';
		$cmd .= ' ' . $this->url;
		
		exec($cmd);
	}

	/**
	 * Delete any temporary files
	 * 
	 */
	private function cleanup()
	{
		unlink($this->outFile);
	}
}