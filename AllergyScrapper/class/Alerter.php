<?php

/**
 * Class to send alerts when certain allergies are high
 *
 */
class Alerter implements SplObserver
{
	public function update(SplSubject $subject)
	{
		$this->email($subject);
	}
	
	private function email($subject)
	{
		
		
		$mail = new Zend\Mail\Message();
		$mail->setBody('This is the text of the email.');
		$mail->setFrom('sneezyt@cfmack.com', 'Sneezy');
		$mail->addTo('cfmack@gmail.com', 'Charles');
		$mail->setSubject('TestSubject');
		
		$transport = new Zend\Mail\Transport\Sendmail();
		$transport->send($mail);
	}
}