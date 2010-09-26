<?php
require_once 'CrawlTaskInterface.php';
class FindImagesTask implements CrawlTaskInterface
{
    protected $images = array();
    
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
        $query = new Zend_Dom_Query($response->getBody());
        $tableRows = $query->query('tr');
        foreach ($tableRows as $tableRows) {
            $this->images[] = $image->getAttribute('src');
        }
        $this->images = array_unique($this->images);
    }
    
    public function shutdown()
    {
        foreach ($this->images as $image) {
            echo $image . "\n";
        }
    }
}