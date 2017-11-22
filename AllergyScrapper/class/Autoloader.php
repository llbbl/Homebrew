<?php

/**
 * Cheap hack to autoload Zend Framework classes
 *
 */
class Autoloader 
{
	public function __construct() 
	{
		spl_autoload_register(array($this, 'loader'));
	}
	
	public function loader($className) 
	{
		$file = $this->recursive_file_exists($className . '.php', '.');
		if ($file == false)
		{
			error_log("Unable to find: $className\n");
		}
	}
	
	function recursive_file_exists($filename, $directory)
	{
		try
		{
			// hack to consider namespaces
			$filename = str_replace("\\", "/", $filename);
			
			//error_log($filename);
			
			/*** loop through the files in directory ***/
			// odd gotcha is that a file has to exist in the directory to reach $file
			foreach(new recursiveIteratorIterator( new recursiveDirectoryIterator($directory)) as $file)
			{
				$ex = explode('/', $file);
				if (count($ex) > 0)
				{
					array_pop($ex);
					
					$check = trim(implode('/' , $ex) . '/' . $filename);
						
					//error_log($check);
					if (file_exists($check))
					{	
						require_once($check);
						return true;
					}
				}
				
			}
			/*** if the file is not found ***/
			return false;
		}
		catch(Exception $e)
		{
			/*** if the directory does not exist or the directory
			 or a sub directory does not have sufficent
			permissions return false ***/
			return false;
		}
	}
}

$autoloader = new Autoloader();