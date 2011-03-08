<?php

/**
 * Simple testing of reflection using pass by reference 
 *
 */
class TestClass
{
	/* @var $property array */
	private $property;
	
	const MY_CONST = 1;
	
	public function __construct()
	{
		$this->property = array();
	}
	
	/**
	 * @return array
	 */
	public function getProperty() 
	{
		return $this->property;
	}

	/**
	 * @param $property array
	 */
	public function setProperty($property = array()) 
	{
		$this->property = $property;
	}

	/**
	 * Random private static
	 * 
	 * @param string $ref
	 * @param string $stub - ignore, just to demostrate passing more than one variable
	 */
	private static function PrivateStaticFunc(&$ref, &$stub)
	{
		$ref .='Ref';
	}
}

?>