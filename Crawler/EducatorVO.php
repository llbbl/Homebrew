<?php

class EducatorVO 
{
	private $name;
	private $agency;
	private $department; // aka school
	private $title;
	private $salary;
	
	function __construct() 
	{
	
	}
	
	public function __toString()
	{
		$x = array();
		$x[] = $this->name;
		$x[] = $this->agency;
		$x[] = $this->department;
		$x[] = $this->title;
		$x[] = $this->salary;
		
		return implode(",", $x) ."\n";
	}
	
	
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $agency
	 */
	public function getAgency() {
		return $this->agency;
	}

	/**
	 * @return the $department
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $salary
	 */
	public function getSalary() 
	{
		return $this->salary;
	}

	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param $agency the $agency to set
	 */
	public function setAgency($agency) {
		$this->agency = $agency;
	}

	/**
	 * @param $department the $department to set
	 */
	public function setDepartment($department) {
		$this->department = $department;
	}

	/**
	 * @param $title the $title to set
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param $salary the $salary to set
	 */
	public function setSalary($salary) 
	{
		$this->salary = str_ireplace(',', '', $salary);
	}
	
}

?>