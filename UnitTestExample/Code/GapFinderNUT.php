<?php
/**
 *
 * GapFinder - Non-Unit Testable
 * 
 * Sinlg 
 * @author cmack
 *
 */
class GapFinderNUT 
{
	
	public function __construct()
	{
		
	}
	
	
	/**
	 * Existing Function.  How can we unit test this?!
	 *
	 */
	public function NonUnitTestableProcess($db, $userId)
	{
		// Get all the calls needed
		$sql = "/* Get my data */ SELECT TOP 10 UserId, LoginDate FROM User Where UserId = {$userId} ORDER LoginStart ASC ";
		$logins = $this->db->FetchObjects($sql);
		
		// Find the gaps - large assumptions that calls never overlap!
		$gaps = array();
		$count = count($logins);
		for($i=0; $i < ($count - 1); $i++)
		{
			$gap = array();
			$gap['length'] = ($logins[$i]->LoginDate) - ($calls[($i+1)]->LoginDate);
			$gaps[] = $gap;
		}
		
		// save the gaps
		foreach($gaps as $gap)
		{
			$sql  = "/* SAVE GAPS */ INSERT INTO Gaps (GapLength) ";
			$sql .= " VALUES ( {$gap['length']} ) ";
			$this->db->Query($sql);
		} 
	}
}

?>