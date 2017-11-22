<?php
/**
 * 
 * Simple value object to store allergen data
 *
 */
class AllergenVO
{
	private $type;
	private $category;
	private $amount;
	
	public function __construct($type, $category='', $amount = '')
	{
		$this->type = trim($type);
		$this->category = trim($category);
		$this->amount = trim($amount);
	}
	
	public function get_type() 
	{
		return $this->type;
	}
	
	public function get_category()
	{
		return $this->category;
	}
	
	public function get_amount()
	{
		return $this->amount;
	}

}