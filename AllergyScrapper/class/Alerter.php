<?php

/**
 * Class to send alerts when certain allergies are high
 *
 */
class Alerter implements SplObserver
{
	private $emails = array();
	private $watch_list = array();
	private $body = '';	

	public function __construct()
	{	
		$this->emails[EMAIL_1] = 'Charles';
		$this->emails[EMAIL_2] = 'Deborah';
		$this->initialize_watch_list();
	}

	private function initialize_watch_list()
	{
		$this->watch_list = array();
		
		$this->watch_list['Molds'] = array();
		$this->watch_list['Molds'][] = 'None';

		$this->watch_list['Virginia Oak'] = array();
		$this->watch_list['Virginia Oak'][] = 'None';
	
		$this->watch_list['Live Oak'] = array();
		$this->watch_list['Live Oak'][] = 'None';
		
		$this->watch_list['Bermuda Grass'] = array();
		$this->watch_list['Bermuda Grass'][] = 'None';
		
		$this->watch_list['Juniper'] = array();
		$this->watch_list['Juniper'][] = 'None';
		
		$this->watch_list['Mt. Cedar'] = array();
		$this->watch_list['Mt. Cedar'][] = 'None';

		$this->watch_list['Ragweed'] = array();
		$this->watch_list['Ragweed'][] = 'Bananas';
		$this->watch_list['Ragweed'][] = 'Melons';
		$this->watch_list['Ragweed'][] = 'Tomatoes';
		$this->watch_list['Ragweed'][] = 'Zucchini';
		$this->watch_list['Ragweed'][] = 'Sunflower Seeds';
		$this->watch_list['Ragweed'][] = 'Dandelions';
		$this->watch_list['Ragweed'][] = 'Camomile';
		$this->watch_list['Ragweed'][] = 'Echinacea';
		
		$this->watch_list['Birch'] = array();
		$this->watch_list['Birch'][] = 'Kiwi';
		$this->watch_list['Birch'][] = 'Apples';
		$this->watch_list['Birch'][] = 'Pears';
		$this->watch_list['Birch'][] = 'Peaches';
		$this->watch_list['Birch'][] = 'Coriander';
		$this->watch_list['Birch'][] = 'Fennel';
		$this->watch_list['Birch'][] = 'Parsley';
		$this->watch_list['Birch'][] = 'Celery';
		$this->watch_list['Birch'][] = 'Cherries';
		$this->watch_list['Birch'][] = 'Carrot';
		$this->watch_list['Birch'][] = 'Hazelnuts';
		$this->watch_list['Birch'][] = 'Almonds';

		$this->watch_list['Grass'] = array();
		$this->watch_list['Grass'][] = 'Peaches';
		$this->watch_list['Grass'][] = 'Celery';
		$this->watch_list['Grass'][] = 'Tomatoes';
		$this->watch_list['Grass'][] = 'Melons';
		$this->watch_list['Grass'][] = 'Oranges';
	}

	public function update(SplSubject $subject)
	{
		$this->email($subject);
	}
	
	private function email($subject)
	{
		if (count($this->emails) < 1)
		{
			echo "No emails configured.\n";
			return false;
		}
		
		$allergens = $subject->get_allergens();
		
		$body = $this->build_body($allergens);
		if (trim($body) == '')
		{
			echo "No email alert needed or sent.\n";
			return false;
		}

	    	$htmlPart = new Zend\Mime\Part($body);
    		$htmlPart->type = "text/html";

    		$textPart = new Zend\Mime\Part($body);
    		$textPart->type = "text/plain";

    		$mime_body = new Zend\Mime\Message();
    		$mime_body->setParts(array($textPart, $htmlPart));	

		$mail = new Zend\Mail\Message();
		$mail->setFrom('sneezyt@cfmack.com', 'Sneezy');
		$mail->setBody($mime_body);
		$mail->setEncoding("UTF-8");
		$mail->getHeaders()->get('content-type')->setType('multipart/alternative');

		foreach($this->emails as $email=>$name)
		{
			$mail->addTo($email, $name);
			echo "Emailing: $email \n";
		}

		$mail->setSubject('Sneezy Allergy Alert');
		
		$transport = new Zend\Mail\Transport\Sendmail();
		$transport->send($mail);
		
		return true;
	}

	/*	
		Only send an email if something is on the watch lis
	*/	
	private function build_body($allergens)
	{
		$html = array();
		foreach($this->watch_list as $watch=>$list)
		{
			$active = $this->check_for_allergen($allergens, $watch);
			if ($active)
			{
				$html[] = "<h3>{$watch}</h3>";
				$html[] = "Avoid<ul>";
				foreach($list as $avoid)
				{
					$html[]= "<li>$avoid</li>";
				}
				$html[] = "</ul>";
			}
	
		}

		
		$today = array();
		if (count($html) > 0)
		{
			$today[] = "<h2>Today's Allergies</h2><ul>";
			
			foreach($allergens as $allergen)
			{
				$today[] = "<li>" . $allergen->get_type() . " " . $allergen->get_category() . " " . $allergen->get_amount() . "</li>"; 
			}
			$today[] = "</ul>";
		}

		return implode("\n", $today) . implode("\n", $html);
	}

	/**
	* test if a specific allergen is in the watch list
	*/
	private function check_for_allergen($allergens, $test)
	{
		foreach($allergens as $allergen)
		{
			if ($allergen->get_type() == $test)
			{
				return true;
			}
		}
		
		return false;
	}	
}
