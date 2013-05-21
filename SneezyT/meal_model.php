<?php
class Meal_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_types($term)
	{
		log_message('error', $term);
		
		$this->db->select('FoodTypeId as id, FoodName as value');
		$this->db->order_by('FoodName', 'asc');
		$this->db->like('FoodName', $term);
		$query = $this->db->get('FoodType', 10);
		return $query->result();
	}

	function insert_meal($food, $meal_date, $meal_note)
	{
		$type_id = $this->get_food_type_id($food);
		
		log_message('error', $meal_date->format("Y-m-d H:i:s"));
		
		$data = array(
				'FoodTypeId' => $type_id ,
				'MealDate' => $meal_date->format("Y-m-d H:i:s"),
				'MealNote' => $meal_note
		);
		
		$this->db->insert('Meal', $data);
		return $this->db->insert_id();
	}
	
	/**
	 * Looks up the food name based user input.  If a food is not found, it is added (probably not the best idea but cheap)
	 * 
	 * @param string $food
	 */
	function get_food_type_id($food)
	{
		$this->db->select('FoodTypeId');
		$query = $this->db->from('FoodType')->where('FoodName', $food)->get();
		$row = $query->first_row();
		
		if (!isset($row->FoodTypeId) || $row->FoodTypeId == null)
		{
			$data = array(
					'FoodName' => $food,
					'FoodLongName' => $food
			);
			
			$this->db->insert('FoodType', $data);
			return $this->db->insert_id();
		}	
		
		return $row->FoodTypeId;
	}
	
	
	function clean_food_types()
	{
		// select COUNT(1), TRIM(LEADING '"' FROM SUBSTRING_INDEX(Food, ',', 1)) as ShortName from FoodType GROUP BY TRIM(LEADING 'X' FROM SUBSTRING_INDEX(Food, ',', 1)) HAVING COUNT(1) > 1;
	
		$sql = <<<SQL
select FoodName from FoodType GROUP BY FoodName HAVING COUNT(1) > 1;
SQL;
		$query = $this->db->query($sql);
		$food_types = $query->result_array();

		$saved_ids = array();
		foreach ($food_types as $row)
		{
			$name = $this->db->escape_str($row['FoodName']);
			
			// find the first id
			$sql = <<<SQL
select FoodTypeId, FoodName from FoodType WHERE FoodName = '$name' ORDER BY FoodTypeId ASC LIMIT 1;
SQL;
			$query = $this->db->query($sql);
			$food_row = $query->row();
			
			$id = $food_row->FoodTypeId;
			
			$sql = <<<SQL
DELETE FROM FoodType WHERE FoodName = '$name' AND FoodTypeId != $id;
SQL;
			//$this->db->query($sql); // commenting out just in case
			$saved_ids[] = $sql;
		}
		
		return $saved_ids;
	}

	/**
	 * Get all the meals in a list
	 */
	function get_meals($index, $pageSize, $sort)
	{
		
		
		$sql = "select MealId, MealDate, FoodName, MealNote from Meal as m join FoodType ft on m.FoodTypeId = ft.FoodTypeId WHERE m.IsDeleted = 0 order by " . $sort . " LIMIT " . $index . ', ' . $pageSize;
		log_message('error', $sql);
		
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	/**
	 * Soft delete a meal from the list
	 */
	function delete($id)
	{
		$sql = "update Meal set IsDeleted = 1 WHERE MealId = " . intval($id);
		$query = $this->db->query($sql);
	}
}
?>