<?php

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
	public function extract($fragment) 
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
		
		$parts = array();
		foreach($ex as $x)
		{
			$and = explode(' and ', $x);
			foreach($and as $y)
			{
                if (trim($y) != '')
                {
				    $parts[] = trim($y);
                }
			}
		}	
		
			
		$allergens = array();

		foreach($parts as $str)
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

		return $this->LookForCategory($str, array('Very High', 'High', 'Medium', 'Low'));
	}

    private function LookForCategory($str, $category)
    {

        foreach($category as $cat)
        {
            if (stripos($str, $cat) !== false)
            {
                $ex = explode($cat, $str);

                $amount = '';
                if (isset($ex[1]))
                {
                    $amount = trim($ex[1]);
                }

                return new AllergenVO(trim($ex[0]), $cat, $amount);
            }
        }

        echo $str . "\n";
        $ex = explode(' ', $str, 2);
        return new AllergenVO(trim($ex[0]), '', trim($ex[1]));
    }

	
}
