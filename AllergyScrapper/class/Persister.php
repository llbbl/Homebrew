<?php

class Persister implements SplObserver
{
    /**
     * 
     * 
     * @param SplSubject $subject   The ExceptionHandler
     * 
     * @return boolean
     */
    public function update(SplSubject $subject)
    {
    	// configuration
    	$dbhost 	= DB_HOST;
    	$dbname		= DB_DB;
    	
    	// database connection
    	$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",DB_USER,DB_PASS);
    	
    	// save file
    	$this->save_file($conn, $subject->get_file());
    	
    	$allergens = $subject->get_allergens();
    	if (count($allergens) > 0)
    	{
    		foreach($allergens as $vo)
    		{
    			$this->save_environment($conn, $vo);
    		}
    	}
    }

	/**
	 * Save the raw file// configuration
	 *
	 * @param PDO connection $conn
	 */
	private function save_file($conn, $file)
	{
		// query
		$sql = "INSERT INTO PollenFile (FileDate,AllergyReport) VALUES (:now,:file)";
		$q = $conn->prepare($sql);
		$q->execute(array(':now'=>date("Y-m-d H:i:s"), ':file'=>file_get_contents($file)));
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
}