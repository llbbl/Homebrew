<?php
include_once './TestClass.php';

$obj = new TestClass();
$obj->setProperty(array("Hello World"));

$reflection = new ReflectionClass(get_class($obj)); 

echo "\n********************************\n";
echo "-- Public Methods\n";
PrintMethods($reflection, ReflectionMethod::IS_PUBLIC);

/*
echo "\n********************************\n";
echo "-- Private Methods\n";
PrintMethods($reflection, ReflectionMethod::IS_PRIVATE);
*/

echo "\n********************************\n";
echo "-- Run Public Method\n";
$method = $reflection->getMethod('getProperty');
$result = $method->invoke($obj);
echo array_pop($result) . "\n";

echo "\n********************************\n";
echo "-- Pass variables to a Public Method\n";
$method = $reflection->getMethod('setProperty');
$method->invoke($obj, array("Goodbye."));

$method = $reflection->getMethod('getProperty');
$result = $method->invoke($obj);
echo array_pop($result) . "\n";



echo "\n********************************\n";
echo "\n-- Public Static Func\n";
$method = $reflection->getMethod('PublicStaticFunc');
$result = $method->invokeArgs(null, array());
echo $result . "\n";

echo "\n-- Call User Func\n";
$result = call_user_func(array('TestClass', 'PublicStaticFunc'));
echo $result . "\n";

/*
echo "\n********************************\n";
echo "-- Print Properites and Constants\n";
PrintClassAttrs($reflection);
*/

/**
 * Print all methods in a reflection class
 * @param ReflectionClass $reflection
 * @param $filter 
 * 
 */
function PrintMethods(ReflectionClass $reflection, $filter=null)
{ 
	$methods = $reflection->getMethods($filter);
	foreach($methods as $method)
	{
		/* @var $method ReflectionMethod */
		echo $method->getName() . "\n";
	}	
}

/**
 * Print out properties and class constants of class
 *  
 * @param ReflectionClass $reflection
 */
function PrintClassAttrs(ReflectionClass $reflection)
{
	$properties = $reflection->getProperties();
	echo "\n---- Properties \n";
	foreach($properties as $property)
	{
		/* @var $property ReflectionProperty */
		echo $property->getName()  . "\n";
	}
	
	$consts = $reflection->getConstants();
	echo "\n---- Constants \n";
	foreach($consts as $name=>$value)
	{
		echo $name . ": " . $value . "\n";
	}
}
?>