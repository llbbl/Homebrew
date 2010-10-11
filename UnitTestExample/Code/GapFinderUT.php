<?php

include_once 'Code/GapVO.php';

class GapFinderUT 
{
	public function __construct()
	{
	}
	
	/**
	 * Encapsulate the business logic to make it unit testable
	 *
	 */
	public function UnitTestProcess()
	{
		$users = $this->LoadUserTimes();
		$gaps = $this->GapLogic($users);
		$this->SaveGap($gaps);
	}
	
	
	/**
	 * Load the users necessary.  
	 * Tough to unit test.  
	 * Could be encapulated in a Model file ( a different show-and-tell )
	 *
	 */
	public function LoadUserTimes($db, $userId)
	{
		$sql = "/* Get my data */ SELECT TOP 10 UserId, LoginDate FROM User Where UserId = {$userId} ORDER LoginStart ASC ";
		
		// In theory, you could transpose this into a real UserVO but outside of this scope
		return $this->db->FetchObjects($sql);
	}
	
	
	/**
	 * Encapsule the finding gaps between two calls 
	 *
	 * Unit testable!
	 * 
	 * @param array $users
	 */
	public function GapLogic($users)
	{
		// Find the gaps - large assumptions that user log in times never overlap!
		$gaps = array();
		$count = count($users);
		for($i=0; $i < ($count - 1); $i++)
		{
			$gaps[] = new GapVO($users[$i], $users[$i+1]);
		}
		
		return $gaps;
	}
	
	/**
	 * Save each gap.  
	 * Not unit testable
	 *
	 * @param array $gaps
	 */
	public function SaveGap($gaps)
	{
		foreach($gaps as $gap)
		{
			/* @var $gap GapVO */
			
			$sql  = "/* SAVE GAPS */ INSERT INTO Gaps (GapLength) ";
			$sql .= " VALUES ( {$gap->getLength()} ) ";
			$this->db->Query($sql);
		}
	}
}

?>