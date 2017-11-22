<?php
require_once('debug.php');

define('CONFIG_PATH', 'C:/Users/matr06017/workspace/log4php/');

/**
 *	Showing that we can append to a file
 */
function AppenderExample()
{
	echo "Running " . __METHOD__ . "\n";
	// Appender Example
	Debug::GetLogger( CONFIG_PATH . 'configs/log4php.appender.xml');
	Debug::Log('Append this.', Debug::ERR);
	Debug::Log('Append this, again.', Debug::WARN);

	Debug::ResetLogger(); // demo purposes only
}

/**
 *	Demo rolling a file AND setting the log level range
 */
function FileRollerLogLevel()
{
	echo "Running " . __METHOD__ . "\n";

	// File Roller and Log Level Example
	Debug::GetLogger(CONFIG_PATH . 'configs/log4php.fileroller.xml');
	
	// log to both appenders because of debug level
	Debug::Log('Log notice in appender using MyFileAppender', Debug::INFO);

	$pad = str_pad('x', 5000);
	
	for($i=0; $i<= 100; $i++)
	{
		Debug::Log("Log only to MyFileRoller: $i " . $pad , Debug::ERR);
	}
	
	Debug::ResetLogger(); // demo purposes only
}




/**
 *	Demon filtering the logs
 */
function StringFilter()
{
	echo "Running " . __METHOD__ . "\n";

	// File Roller and Log Level Example
	Debug::GetLogger(CONFIG_PATH . 'configs/log4php.stringfilter.xml');

	Debug::Log('Log this');
	Debug::Log('Filter this - PHP ROCKS');
	Debug::Log('Log this, again');
}


/**
 * Simple function to get and wait for user import prior to running the next example
 * 
 */
function NextExample()
{
	echo "========= Hit Enter for next example\n";
	fgets(STDIN); 	
}

// Main
AppenderExample();
NextExample();

FileRollerLogLevel();
NextExample();

StringFilter();
NextExample();


?>