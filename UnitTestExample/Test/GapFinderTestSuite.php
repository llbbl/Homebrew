<?php

require_once 'Test/GapFinderUnitTest.php';

require_once 'Test/GapVOUnitTest.php';

/**
 * Static test suite.
 */
class GapFinderTestSuite extends PHPUnit_Framework_TestSuite {
	
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'GapFinderTestSuite' );
		
		$this->addTestSuite ( 'GapFinderUnitTest' );
		
		$this->addTestSuite ( 'GapVOUnitTest' );
	
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ();
	}
}

