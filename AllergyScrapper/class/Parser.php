<?php

// load mininal zend framework for DOM library
require_once('library/ZendFramework-2.2.2/library/Zend/Dom/Query.php');
require_once('library/ZendFramework-2.2.2/library/Zend/Dom/Css2Xpath.php');
require_once('library/ZendFramework-2.2.2/library/Zend/Dom/NodeList.php');
require_once('library/ZendFramework-2.2.2/library/Zend/Stdlib/ErrorHandler.php');

/*
// if it is an exception, we'll get a "can't find class" error - no reason to go down the crazy path of Zend includes
require_once('./library/ZendFramework-2.2.2/library/Zend/Dom/BadMethodCallException.php');
require_once('./library/ZendFramework-2.2.2/library/Zend/Dom/ExceptionInterface.php');
require_once('./library/ZendFramework-2.2.2/library/Zend/Dom/RuntimeException.php');
*/

class Parser
{
	private $file;

	public function __construct($file)
	{
		$this->file = $file;
	}
	
	/**
	 * Parse out the allergens 
	 * @return array
	 */
	public function parse()
	{
		$contents = file_get_contents($this->file);
		
		$dom = new Zend\Dom\Query();
		$dom->setDocument($contents);
		
		// @var array  $results of DOMElement
		$results = $dom->execute('#allergyforecastcontent p');
		$fragment = $this->DOMinnerHTML($results[1]);  // should be the 2nd <p>
		$allergens = $this->extract($fragment);
		
		return $allergens;
	}
	
	/**
	 * Extract the innerHTML of the DOMElement
	 * @param DOMElement $element
	 * @return string
	 */
	private function DOMinnerHTML($element)
	{
		$innerHTML = "";
		$children = $element->childNodes;
		foreach ($children as $child)
		{
			$tmp_dom = new DOMDocument();
			$tmp_dom->appendChild($tmp_dom->importNode($child, true));
			$innerHTML.=trim($tmp_dom->saveHTML());
		}
		return $innerHTML;
	}
	
	/**
	 * @test I should really unit test this function!
	 * @param string $fragment
	 */
	private function extract($fragment) 
	{
		// Allergy Count: Molds High.&nbsp; This allergy forecast ...
		/*
		 // Example parse:
		
		Oak Medium 33 gr/m3, Molds Medium
		Molds High
		Ash Medium 20 gr/m3, Elm Medium 27 gr/m3, Oak Medium 27 hr/m3, Molds High
		Trees High 107 gr/m3, Grass High 47 gr/m3, Oak High 1121 gr/m3, Molds High
		Cedar Low 7 gr/m3, Oak Low 7 gr/m3, Ash High 93 gr/m3, Elm Medium 20 gr/m3, Molds Medium
		
		*/
		
		$ex = explode('Allergy Count:', $fragment, 2);
		$fragment = trim($ex[1]);
		
		$ex = explode('.', $fragment, 2);
		$fragment = trim($ex[0]);
		
		$ex = explode(',', $fragment);
		
		$allergens = array();
		foreach($ex as $str)
		{
			$allergens[] = $this->VOfactory($str);
		}
		
		return $allergens;
	}
	
	/**
	 * build a Allergen VO by extracting a substring
	 * 
	 * Examples:
	 * - Cedar Low 7 gr/m3
	 * - Molds Medium
	 * 
	 * @param string $str
	 * @return AllergenVO
	 */
	private function VOfactory($str)
	{
		$str = trim($str);
		
		$ex = explode(' ', $str, 3);
		
		$amount = '';
		if (isset($ex[2]))
		{
			$amount = $ex[2];
		}
		
		return new AllergenVO($ex[0], $ex[1], $amount);
	}

	
}
