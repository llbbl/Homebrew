<?php

class GapVO
{
	private $firstUser;
	private $secondUser;
	
	public function __construct($firstUser, $secondUser)
	{
		$this->firstUser 	= $firstUser;
		$this->secondUser 	= $secondUser;
	}
	
	/**
	 * Return the gap between the end of first User and beginning of 2nd User
	 *
	 * Unit testable!
	 * 
	 * @throws Exception if the length is less than zero ( should happen in constructor
	 *  
	 * @return int 
	 */
	public function getLength()
	{
		$length = $this->secondUser->LoginDate - $this->firstUser->LoginDate;
		
		if ($length < 0)
		{
			throw new Exception('Length cannot be less than 0');
		}
		
		return $length;
	}
	
	/**
	 * Totally useless function that returns HTML about the Gap
	 */
	public function toHtml()
	{
		$html = <<<HTML
<div class="user">
	<div>User: PHP</div>
	<div>Single Gap Time: {$this->getLength()}</div>
</div>
HTML;

		return $html;
	}
}

?>