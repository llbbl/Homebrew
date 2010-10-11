<?php

require_once 'PHPUnit/Framework/TestCase.php';
include_once 'Code/GapFinderUT.php';
/**
 * test case.
 */
class GapFinderUnitTest extends PHPUnit_Framework_TestCase 
{

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
	

	public function testGapLogic()
	{
		$users = array();
		
		$user1 = new stdClass();
		$user1->LoginDate = 10;
		$users[] = $user1;
		
		$user2 = new stdClass();
		$user2->LoginDate = 20;
		$users[] = $user2;
		
		$user3 = new stdClass();
		$user3->LoginTime = 50;
		$users[] = $user3;
		
		$gapAnalyzer = new GapFinderUT();
		$gaps = $gapAnalyzer->GapLogic($users);
		$this->assertEquals(count($gaps), 2, "We expect 2 gaps");

		foreach($gaps as $gap)
		{
			$this->assertTrue(($gap instanceof GapVO), "We expect the data structure to contain only instances of GapVO object");
		}
		
		// could test GapVO::getLength() here too.  But it is a different class and an argument could be made against it
		// There is no hard and fast rule.   Development is an art.
		
	}
}

