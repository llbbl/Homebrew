<?php
/*
 	System Info 
 	
	PHP 5.3.2-1ubuntu4.7 with Suhosin-Patch (cli) (built: Jan 12 2011 18:36:08) 
	Copyright (c) 1997-2009 The PHP Group
	Zend Engine v2.3.0, Copyright (c) 1998-2010 Zend Technologies
	    with Xdebug v2.0.5, Copyright (c) 2002-2008, by Derick Rethans

 	error_reporting = E_ALL | E_STRICT
 	allow_call_time_pass_reference = Off
 */

$method = new ReflectionMethod('TestClass', 'PrivateStaticFunc');
$method->setAccessible(true);

$stub = 'ignore this.  just using this variable to show I have multiple items passed in';
try
{
	$actual = 'Test';
	$expect = 'TestRef';
	$method->invoke(null, &$actual, &$stub);
	CheckResult($actual, $expect, "Testing with an &");
}
catch(ReflectionException $e)
{
	echo "Reflection Exception caught: $e"; 
}

echo "\n------------------------------------\n";

try
{
	$actual = 'Test';
	$expect = 'TestRef';
	$method->invoke(null, $actual, &$stub);
	CheckResult($actual, $expect, 'Testing without an &');
}
catch(ReflectionException $e)
{
	echo "Reflection Exception caught: {$e->getMessage()}" ; 
}

echo "\n------------------------------------\n";

// This allows the best of both words
try
{
	$actual = 'Test';
	$expect = 'TestRef';
	$method->invokeArgs(null, array(&$actual, &$stub));
	CheckResult($actual, $expect, 'Testing with invokeArgs');
}
catch(ReflectionException $e)
{
	echo "Reflection Exception caught: {$e->getMessage()}" ; 
}




/**
 * Simple function to echo the result of the test
 * 
 * @param $actual
 * @param $expect
 * @param $msg
 */
function CheckResult($actual, $expect, $msg)
{
	echo "\n$msg\n";
	if ($actual == $expect)
	{
		echo "True: '$actual' == '$expect'\n";
	}
	else
	{
		echo "False: '$actual' != '$expect'\n";
	}
}




/**
 * Simple testing of reflection using pass by reference 
 *
 */
class TestClass
{
	/**
	 * Append 'Ref' to the passed in string
	 * 
	 * @param string $ref
	 * @param string $stub - just a dummy variable to demonstrate passing more than one arg in
	 */
	private static function PrivateStaticFunc(&$ref, &$stub)
	{
		$ref .='Ref';
	}
}

?>