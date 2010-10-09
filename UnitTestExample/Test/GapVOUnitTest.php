<?php

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * test case.
 */
class GapVOUnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * Constructs the test case.
	 */
	public function __construct() 
	{
	}
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() 
	{
		parent::setUp ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() 
	{
			parent::tearDown ();
	}
	
	public function testgetLength()
	{
		
		// simple test
		$user1 = new stdClass();
		$user1->LoginDate = 10;
		
		$user2 = new stdClass();
		$user2->LoginDate = 20;
		
		$gap = new GapVO($user1, $user2);
		$this->assertEquals($gap->getLength(), 10, "We expect a gap of 10 seconds between call1 and call2");
		
		// there are a few ways to test exceptions
		$hasException = false;
		try 
		{
			$user1 = new stdClass();
			$user1->LoginDate = 20;
			
			$user2 = new stdClass();
			$user2->LoginDate = 10;
			
			$gap = new GapVO($user1, $user2);
			$gap->getLength(); // should throw an error because it returns 0
		}catch(Exception $e)
		{
			$hasException = true;
		}
		
		$this->assertTrue($hasException, "An exception should have been thrown because the gap was less than zero");
	}

}

