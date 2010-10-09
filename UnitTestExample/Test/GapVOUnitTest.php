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
	
	/**
	 * Using annotations, expect an exception to be thrown. 
	 * Disadvantage is that there is only one case in this function
	 * 
     * @expectedException Exception
     */
    public function testLengthException()
    {
    	$user1 = new stdClass();
		$user1->LoginDate = 20;
		
		$user2 = new stdClass();
		$user2->LoginDate = 10;
		
		$gap = new GapVO($user1, $user2);
		$gap->getLength(); // should throw an error because it returns 0
    }
	
    
    /**
     * How do you test Views?  How do you make it robust, and less brittle
     */
    public function testtoHtml()
    {
		// simple test
		$user1 = new stdClass();
		$user1->LoginDate = 10;
		
		$user2 = new stdClass();
		$user2->LoginDate = 20;
		
		$gap = new GapVO($user1, $user2);
		$html = $gap->toHTML();
		
		// test number of divs
   		$query = new Zend_Dom_Query($html);
        $divs = $query->query('div');
        $this->assertEquals(count($divs), 3, "Regardless of text, there should always be 3 divs");
        
        
        // test the css class used
        $div = $divs[0];
       	$class = $div->getAttribute('class');
       	$this->assertEquals($class, 'user', "The css class in the gap length html should be 'user'"); 
    }
}

