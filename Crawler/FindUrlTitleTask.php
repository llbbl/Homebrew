<?php
require_once 'CrawlTaskInterface.php';
class FindUrlTitleTask implements CrawlTaskInterface
{
    protected $titles = array();
    
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
        $query = new Zend_Dom_Query($response->getBody());
        $titles = $query->query('title');
        
        foreach ($titles as $title) 
        {
            $this->titles[] = $title->textContent;
        }
        
        $this->titles = array_unique($this->titles);
    }
    
    public function shutdown()
    {
        foreach ($this->titles as $title) 
        {
            echo $title . "\n";
        }
    }
}