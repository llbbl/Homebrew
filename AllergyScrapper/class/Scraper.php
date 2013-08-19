<?php
require_once('Parser.php');
require_once('AllergenVO.php');


class Scraper implements SplSubject
{
    /**
     * 	An array of SplObserver objects
     * 
     *	@var array
     */
    private $observers = array();

    /**
     * URL that we're going to parse
     * 
     * @var string
     */
	private $url;
	
	/**
	 * Full path and file name to the output to parse
	 * 
	 * @var string
	 */
	private $out_file;
	
	/**
	 * Parsed allergans 
	 * 
	 * @var array
	 */
	private $allergens;
	
	
	public function __construct($url)
	{
		$this->url = $url;
		
		$p = explode('/', $this->url);
		
		if (count($p) <= '3')
		{
			$fileName = 'index.html';
		}
		else
		{
			$fileName = array_pop($p);
		}
		
		$this->out_file = $fileName;
		
		$this->allergens = array();
	}
	
	/**
	 * Return the file that we are saving too
	 * 
	 */
	public function get_file()
	{
		return $this->out_file;
	}
	
	/**
	 * Return an array of AllergenVos
	 * 
	 */
	public function get_allergens()
	{
		return $this->allergens;
	}
	
	/**
	 * Attaches an SplObserver to
	 * the ExceptionHandler to be notified
	 * when an uncaught Exception is thrown.
	 *
	 * @param SplObserver        The observer to attach
	 * @return void
	 */
	public function attach(SplObserver $obs)
	{
		$id = spl_object_hash($obs);
		$this->observers[$id] = $obs;
	}
	
	/**
	 * Detaches the SplObserver from the
	 * ExceptionHandler, so it will no longer
	 * be notified when an uncaught Exception is thrown.
	 *
	 * @param SplObserver        The observer to detach
	 * @return void
	 */
	public function detach(SplObserver $obs)
	{
		$id = spl_object_hash($obs);
		unset($this->observers[$id]);
	}
	
	/**
	 * Notify all observers of the uncaught Exception
	 * so they can handle it as needed.
	 *
	 * @return void
	 */
	public function notify()
	{
		foreach($this->observers as $obs)
		{
			$obs->update($this);
		}
	}
	
	
	/**
	 * Controller for Scraper
	 * 
	 */
	public function scrape()
	{
		//$this->retrieve();
		$this->parse();
		$this->notify();
		//$this->cleanup();
	}
	
	/**
	 * Encapsulate the parsing
	 * 
	 */
	public function parse()
	{
		$p = new Parser($this->out_file);
		$this->allergens = $p->parse();
		return $this->allergens;
	}
	
	
	
	/**
	 * Retrieve URL to parse
	 */
	private function retrieve()
	{
		$cmd = 'wget --referer="http://www.google.com" --user-agent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"';
		$cmd .= ' ' . $this->url;
		
		exec($cmd);
	}

	/**
	 * Delete any temporary files
	 * 
	 */
	private function cleanup()
	{
		unlink($this->out_file);
	}
}
