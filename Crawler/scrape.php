<?php
require_once 'Crawler.php';
require_once 'FindTeacherSalaryTask.php';

/*
require_once 'CrawlerTask.php';
require_once 'FindImagesTask.php';
require_once 'FindUrlTitleTask.php';
require_once 'AverageResponseTimeTask.php';
*/

// zend framework autoloading
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


//$startFrom = 'http://www.google.com/search?q=nice+kicks';
//$startFrom = 'http://www.texastribune.org/library/data/government-employee-salaries/search/?page=1&q=chisholm+trail&x=14&y=8';
//$startFrom = 'http://www.texastribune.org/library/data/government-employee-salaries/search/?page=1&q=%22Round+Rock+High+Sch%22&x=0&y=0';
$startFrom = 'http://www.texastribune.org/library/data/government-employee-salaries/search/?page=1&q=round+rock+isd&x=0&y=0';

$crawler = new Crawler($startFrom);
$useQueue = false;

if (file_exists('queue') && $useQueue == true) 
{
	$queue = unserialize(file_get_contents('queue'));
	$crawler->setQueue($queue);
}

$crawler->setDebugMode(true);
$crawler->registerTask(new FindTeacherSalaryTask());
$crawler->setLimit(200); // limit to just crawling the single website right now
$crawler->setStayOnDomain(true);
$crawler->setCustomLinkClass(".page"); // only find google search results and add to queue
$crawler->setTreeDepth(false); // limit to only find links on the first page
$crawler->setSleep(true);
$crawler->run();

$queue = $crawler->getQueue();
file_put_contents('queue', serialize($queue));