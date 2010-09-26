<?php
require_once 'Queue.php';

class Crawler
{
    protected $queue;
    protected $stayOnDomain = true;
    protected $tasks = array();
    protected $debugMode;
    protected $domain;
    protected $limit;
    protected $counter;
    protected $treeDepth;
    protected $customLinkClass;
    protected $sleep;
    
    /**
     * @var Zend_Http_Response
     */
    protected $currentResponse;
    
    /**
     * @var Zend_Http_Client
     */
    protected $client;
    
	public function __construct($startFrom)
    {
        $this->queue = new Queue();
        $this->queue->push($startFrom);    
        $this->client 	 = new Zend_Http_Client();
        $this->root		 = parse_url($startFrom, PHP_URL_HOST);
        $this->domain 	 = $startFrom; 
        $this->treeDepth = false;
		$this->customLinkClass = "";
		$this->sleep = false;
    }

	/**
	 * @return the $sleep
	 */
	public function getSleep() 
	{
		return $this->sleep;
	}

	/**
	 * @param $sleep the $sleep to set
	 */
	public function setSleep($sleep) 
	{
		$this->sleep = $sleep;
	}
    
    public function registerTask(CrawlTaskInterface $crawlTask)
    {
       $this->tasks[] = $crawlTask;
    }
    
    public function setDebugMode($val)
    {
        $this->debugMode = $val;
    }
    
    public function setQueue(Queue $queue)
    {
    	$this->queue = $queue;
    }
    
    public function setTreeDepth($depth)
    {
    	$this->treeDepth = $depth;
    }
    
    public function getQueue()
    {
    	return $this->queue;
    }
    
    public function setLimit($num)
    {
    	$this->limit = $num;
    }
    
    public function setStayOnDomain($stay)
    {
    	$this->stayOnDomain = $stay;
    }
    
    public function setCustomLinkClass($class)
    {
    	$this->customLinkClass = $class;
    }
    
    public function run()
    {
        if ($this->debugMode) 
        {
            echo "Restricting crawl to $this->domain\n";
        }
        
        $atDepth = 0;
        
        //loop across available items in the queue of pages to crawl
        while (!$this->queue->isEmpty()) 
        {
        	$atDepth++;
        	
        	if (isset($this->limit) && ($this->counter >= $this->limit)) 
        	{
        		break;
        	}
        	
        	$this->counter++;
        	
        	
        	//get a new url to crawl
            $url = $this->queue->pop();
            
            if ($this->debugMode) 
            {
                echo "Queue Length: " . $this->queue->queueLength() . "\n";
                echo "Crawling " . $url . "\n";
            }
            
            //set the url into the http client
            $this->client->setUri($url);
            
            if ($this->getSleep() == true)
            {
            	sleep(rand(0,4));
            }
            
            //make the request to the remote server
            $this->currentResponse = $this->client->request();
            
            //don't bother trying to parse this if it's not text
            if (stripos($this->currentResponse->getHeader('Content-type'), 'text') === false) 
            {
            	if ($this->debugMode) 
            	{
            		echo "\tSkipping current response";
            	}
            	continue;
            }
            
            //search for <a> tags in the document
            $body = $this->currentResponse->getBody();
            $linksQuery = new Zend_Dom_Query($body);
            $links = $linksQuery->query('a' . $this->customLinkClass);
            
            if ($this->debugMode) 
            {
                echo "\tFound " . count($links) . " links...\n";
            }
            
            foreach ($links as $link) 
            {
            	
            	//get the href of the link and find out if it links to the current host
                $href = $link->getAttribute('href');
                $href = $this->ExtractUrl($href);
                
                $urlparts = parse_url($href);

                if ($this->stayOnDomain && isset($urlparts["host"]) && $urlparts["host"] != $this->domain) 
                {
                    continue;
                }
                
                //if it's an absolute link without a domain or a scheme, attempt to fix it
                if (!isset($urlparts["host"])) 
                {
                    $href = $this->ExtractDomain($this->domain) . $href;  //this is a really naive way of doing this!
                }
                
                //push this link into the queue to be crawled
                if ($this->treeDepth == false || $atDepth <= $this->treeDepth)
                {
                	$this->queue->push($href);
                }
            }
            
            //for each page that we see, run every registered task across it
            foreach ($this->tasks as $task) 
            {
                $task->task($this->currentResponse, $this->client);
            }
            
        }
        
        //after we're done with everything, call the shutdown hook on all the tasks
        $this->shutdownTasks();
    }
    
    public function shutdownTasks()
    {
        foreach  ($this->tasks as $task) {
            $task->shutdown();
        }
    }
    
    /**
     * Extract domain from 
     * http://www.texastribune.org/library/data/government-employee-salaries/search/?page=1&q=chisholm+trail&x=14&y=8
     * 
     */
    public function ExtractDomain($url)
    {
    	$domain = $url;
 	  	$pos = strpos($domain, '?'); 	
    	if ($pos > 0)
    	{
    		$domain = substr($domain, 0, $pos);
    	}
   		return $domain;
    }
    
    /**
     * Test whether a url is from a google result
     * 
     * @param string $uri
     * @return bool
     */
    private function IsGoogleResult($uri)
    {
    	if (stripos($uri, "/url?q=") > -1)
    	{
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * Google does some JS trickiness, extract the URL from google results or return the URI 
     * 
     * @param string $uri
     * @return string
     */
    private function ExtractUrl($uri)
    {
    	if (!$this->IsGoogleResult($uri))
    	{	
    		return $uri;
    	}
    	
    	$httpPos = stripos($uri, "http");
    	$uri = substr($uri, $httpPos);
    	
    	$ampPos = striPos($uri, "&amp;");
    	if ($ampPos > -1)
    	{
    		$uri = substr($uri, 0, $ampPos);	
    	}
    	
    	return $uri;
	}
}