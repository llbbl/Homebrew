<?php
require_once 'CrawlTaskInterface.php';
require_once 'EducatorVO.php';

class FindTeacherSalaryTask implements CrawlTaskInterface
{
    protected $educator = array();
    protected $hint = 'round-rock-isd';
    
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
        $query = new Zend_Dom_Query($response->getBody());
        $rows = $query->query('tr');
        $i = 0;
        foreach ($rows as $row) 
        {
        	$this->ExtractColumn(simplexml_import_dom($row));
        }
        
        return;
    }
    
    function ExtractColumn($xml)
    {
    	$vo = new EducatorVO();
        $i = 0;
        foreach($xml->children() as $td)
        {
        	foreach($td->children() as $link)
        	{
        		/* @var $link SimpleXMLElement */
        		$attributes = $link->attributes();
        		if ($i == 0 && count($attributes) > 0)
        		{
        			foreach($attributes as $key => $value)
        			{
        				if ($key == 'href' && (stripos($value[0], $this->hint) > 0))
        				{
        					$text = "$link[0]";
        					$vo->setName($text);
        					$i = 1;
        				}
						        				
        			}

        		}
        		else if ($i == 1)
        		{
        			$vo->setAgency("$link[0]");
        			$i++;
        		}
        		else if ($i == 2)
        		{
        			$vo->setDepartment("$link[0]");
        			$i++;
        		}
        		else if ($i == 3)
        		{
        			$vo->setTitle("$link[0]");
        			$i++;
        		}
        	}
        	
        	// skip to the next td to make sure we don't get the td of the last XML node
        	if ($i == 4)
        	{
        		$i++;
        		continue;
        	}
        	
        	if ($i == 5 && isset($td[0]))
        	{
        		$salary = $td[0];
        		$vo->setSalary("$salary");
        		$i = 0;
        			
        		$this->educator[] = $vo;
        		$vo = new EducatorVO();
        	}
       	}
    }
    
    public function shutdown()
    {
    	//$fp = fopen('chisom_trail_salaries.csv', 'w+');
    	$fp = fopen('round_rock_hs_salaries.csv', 'w+');
    	
		fwrite($fp, "Name,Agency,Department,Title,Salary\n");

		foreach ($this->educator as $educator) 
        {
        	/* @var $educator EducatorVO */ 
        	fwrite($fp, "$educator");

        }
        
        fclose($fp);
    }
}