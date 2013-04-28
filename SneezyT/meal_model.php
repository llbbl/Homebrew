<?php
class Meal_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_food_types($term)
	{
		$this->db->select('FoodTypeId as id, Food as value');
		$this->db->order_by('Food', 'asc');
		$this->db->like('Food', $term);
		$query = $this->db->get('FoodType', 10);
		return $query->result();
	}

	function insert_meal($food, $mealDate)
	{
		$typeId = $this->getFoodId($food);
		
		log_message('error', $mealDate->format("Y-m-d H:i:s"));
		
		$data = array(
				'FoodTypeId' => $typeId ,
				'MealDate' => $mealDate->format("Y-m-d H:i:s")
		);
		
		$this->db->insert('Meal', $data);
		return $this->db->insert_id();
	}
	
	function getFoodId($food)
	{
		$this->db->select('FoodTypeId');
		$query = $this->db->from('FoodType')->where('Food', $food)->get();
		$row = $query->first_row();
		return $row->FoodTypeId;
		
	}
}
?>