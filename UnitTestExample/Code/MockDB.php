<?php

class MockDB
{
	
	function setArrayReturn($calls)
	{
		$this->arrayOfCalls = $calls;
	}
	
	function FetchObject($sql)
	{
		return $this->arrayOfCalls();
	}
}

?>