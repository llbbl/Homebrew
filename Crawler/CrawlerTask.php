<?php

require_once ('CrawlTaskInterface.php');

class CrawlerTask implements CrawlTaskInterface 
{
	
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
    	$crawler = new Crawler($client->getUri(true));
    	$crawler->setDebugMode(true);
		$crawler->registerTask(new FindUrlTitleTask());
		$crawler->setLimit(1); // limit to just crawling google
		$crawler->setStayOnDomain(false);
		$crawler->setCustomLinkClass(".l"); // only find google search results and add to queue
		$crawler->run();
    }
    
    public function shutdown()
    {
    	
    }
}

?>