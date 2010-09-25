<?php
include_once('FileVO.php');
include_once('FileCollection.php');

class DirectoryBrowser 
{
	private $files;
	
	function __construct()
	{
		$this->files = new FileCollection(); 
	}
	
	function Walk( $path = '.', $level = 0 )
	{ 

	    $ignore = array( 'cgi-bin', '.', '..' ); 
	    // Directories to ignore when listing output. Many hosts 
	    // will deny PHP access to the cgi-bin. 
	
	    $dh = @opendir( $path ); 
	    // Open the directory to the handle $dh 
	     
	    while( false !== ( $file = readdir( $dh ) ) )
	    { 
	    // Loop through the directory 
	     
	        if( !in_array( $file, $ignore ) )
	        { 
	        	// Check that this file is not to be ignored 
	             
	            // Just to add spacing to the list, to better 
	            // show the directory tree. 
	             
	            if( is_dir( "$path/$file" ) ){ 
	            // Its a directory, so we need to keep reading down... 
	             
	                $this->Walk( "$path/$file"); 
	                // Re-call this same function but on a new directory. 
	                // this is what makes function recursive. 
	            } 
	            else 
	            { 
	             	$file = new FileVO($file, $path);
	             	$this->files->AddFile($file);
	                // Just print out the filename 
	            } 
	         
	        } 
	     
	    } 
	     
	    closedir( $dh ); 
	    // Close the directory handle

	    return $this->files;
	}
	 
}

?>