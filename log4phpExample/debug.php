<?php
/**
 * Debug class file definition
 *
 * Wrapper for log4php
 */

require_once('./apache-log4php-2.2.1/src/main/php/Logger.php');

class Debug
{
	const FATAL 	= LoggerLevel::FATAL;  		// Emergency: system is unusable
	const ERR     	= LoggerLevel::ERROR;  		// Error: error conditions (unexpected value that could break something)
	const WARN    	= LoggerLevel::WARN;  		// Warning: warning conditions (unexpected value that will not break anything)
	const INFO    	= LoggerLevel::INFO;  		// Informational: informational messages (minimal looping)
	const DEBUG   	= LoggerLevel::DEBUG;  		// Debug: debug messages (looping OK)
	
	const DEFAULT_LOG4PHP_CONFIG = './log4php.xml'; // default xml file to load
	
	/**
	 * Singleton log4php logger 
	 * 
	 * @var Logger
	 */
	private static $log4php;
	

	/**
	 * Logs a message to the error log if the current debug level is equal to or lower than the debug level passed in.
	 *
	 * @param string $body - Message to error log
	 * @param int $debugLevel - One of the values available as a Debug class constant, default level for developer use
	 */
	public static function Log($logMsg, $debugLevel = self::ERR)
	{
		$logger = self::GetLogger();
		$logger->log(LoggerLevel::toLevel($debugLevel), $logMsg);	
	}
	

	/**
	 * Returns a logger object implemented by log4php
	 * 
	 * @param string $configFile - override the default with a particular XML configuration
	 * @param string $logFile - override the log for a particular appender
	 * @param string $appender - default appender to override
	 *
	 * @return Logger $logger
	 */
	public static function GetLogger($configFile = null, $logFile = null, $defaultAppender=null)
	{
		// return the singleton
		if (self::$log4php instanceof Logger)
		{
			return self::$log4php;
		}
		
		// apply default configs if user-defined doesn't exist
		if (is_null($configFile))
		{
			$configFile = self::DEFAULT_LOG4PHP_CONFIG;
		}
		
		// set the log file to write to
		Logger::configure($configFile);
		$logger = Logger::getRootLogger();

		// get a special appender to read and override the log file
		if (!is_null($defaultAppender) && !is_null($logFile))
		{
			$appender = $logger->getAppender($defaultAppender);
			if ($appender != null)
			{
				$appender->setFile($logFile, true);
				$appender->activateOptions();
			}		
		}
		
		self::$log4php = $logger;
		
		return self::$log4php;
	}

	/**
	 *	Used for demo purposes only
	 *
	 */
	public static function ResetLogger()
	{
		self::$log4php = null;
	}
}
?>