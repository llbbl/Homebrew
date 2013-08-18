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
		// Allergy Count: Allergy Count: Ragweed Low 7 gr/m3,&nbsp;Mulberry 7 gr/m3 and Molds High.&nbsp; forecast ...
		
		// remove &nbsp; and other html entities trim does not remove
		$fragment = preg_replace("/&#?[a-z0-9]+;/i","",$fragment);
		
		// Allergy Count: Ragweed Low 7 gr/m3, Mulberry 7 gr/m3 and Molds High.&nbsp;
		$ex = explode('Allergy Count:', $fragment, 2);
		$fragment = trim($ex[1]);
		
		// Ragweed Low 7 gr/m3, Mulberry 7 gr/m3 and Molds High.&nbsp;
		$ex = explode('.', $fragment, 2);
		$fragment = trim($ex[0]);

		// Ragweed Low 7 gr/m3
		// Mulberry 7 gr/m3 and Molds High.&nbsp;
		$ex = explode(',', $fragment);
		
		$last_key = count($ex) - 1;
		
		if ($last_key != 0)
		{
			// Mulberry 7 gr/m3 and Molds High.&nbsp;
			$and = explode(' and ', $ex[$last_key], 2);
			// Mulberry 7 gr/m3 
			$ex[$last_key] = trim($and[0]);
			
			// Molds High.&nbsp;
			$ex[] = trim($and[1]);
			$last_key += 1;
		}

		// Ragweed Low 7 gr/m3
		// Mulberry 7 gr/m3 
		// Molds High.&nbsp;
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
