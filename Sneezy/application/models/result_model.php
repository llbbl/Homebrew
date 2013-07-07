<?php

class Result_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function timeline_data() 
	{
		$types = array('Reaction', 'Food', 'Medicine', 'Environment');
		
		$sql = array();
		foreach ($types as $type)
		{
			$first = substr($type, 0, 1);
			$subselect = <<<SQL
select {$type}Id as Id, {$type}Date as Date, {$type}Name as Name, {$type}Note as Note, '{$type}' as Type
from $type as $first
join {$type}Type {$first}t on {$first}.{$type}TypeId = {$first}t.{$type}TypeId
WHERE {$first}.IsDeleted = 0
SQL;
			$sql[] = $subselect;
		}
		
		$query = $this->db->query(implode(' UNION ', $sql));
		return $query->result_array();
	}
	
	function hours_from_event($index = 0, $page_size = 10, $num_of_gaps = 2, $event_id = 1, $scale = 'linear', $start_date = null)
	{
		//($index, $page_size, $sort_str)
		
		$event_id = 1; // vomit
		$hour_gaps = array();
		for($i = 1; $i <= $num_of_gaps; $i++)
		{
			if ($scale == 'quadratic')
			{
				$hour_gaps[$i] = ($i ^ 2);
			}
			else if ($scale == 'exponential')
			{
				$hour_gaps[$i] = (2 ^ $i);
			}
			else //if ($scale == 'linear')
			{
				$hour_gaps[$i] = ($i + 1);
			}
		}
		
		// build the subselects
		$subs = array();
		for($i = 0; $i <= $num_of_gaps; $i++)
		{
			// build columns
			$columns = array();
			
			// handle num of food count
			if ($i==0) 
			{
				$columns[] = 'COUNT( 1 ) AS NumOfFood';
			}
			else 
			{
				$columns [] = 'MAX( 0 ) AS NumOfFood';
			}
			
			// handle time based number of reactions
			for($j = 1; $j <= $num_of_gaps; $j++)
			{
				if ($j == $i)
				{
					$columns[] = 'COUNT( 1 ) AS NumOf' . $hour_gaps[$j] . 'Reactions';
				}
				else  
				{
					$columns[] = 'MAX( 0 ) AS NumOf' . $hour_gaps[$j] . 'Reactions';
				}
			}
			
			$column_str = implode($columns, " , ");
			
			if ($i == 0)
			{
				$sub  = "SELECT ft.FoodName, {$column_str} ";
				$sub .= " FROM Food AS f JOIN FoodType ft ON ft.FoodTypeId = f.FoodTypeId GROUP BY ft.FoodName ";
				$subs[] = $sub;
			}
			else 
			{
				$sub  = "SELECT ft.FoodName, {$column_str} ";
				$sub .= " FROM Food AS f JOIN Reaction AS r ON TIMESTAMPDIFF( HOUR , r.ReactionDate, f.FoodDate ) ";
				$sub .= " BETWEEN -" . $hour_gaps[$i] . " AND 0 ";
				$sub .= " JOIN FoodType ft ON ft.FoodTypeId = f.FoodTypeId ";
				$sub .= " WHERE r.ReactionTypeId = $event_id ";
				$sub .= " GROUP BY ft.FoodName ";
				$subs[] = $sub;
			}
		}
		
		// build outer select
		$columns = array();
		$columns[] = 'FoodName';
		$columns[] = "SUM(NumOfFood) AS NumOfFood";
		for($i = 1; $i <= $num_of_gaps; $i++)
		{
			$columns[] = "SUM(NumOf" . $hour_gaps[$i] . "Reactions) as NumOf" . $hour_gaps[$i] . "Reactions";
		}
		
		$select  = "SELECT " . implode($columns, " , ") . " FROM ( ";
		$select .= implode($subs, " UNION ALL ");
		$select .= " ) AS FoodCounts ";
		$select .= " GROUP BY FoodName HAVING SUM(NumOfFood) > 1 ";
		
		$max_hour = max($hour_gaps);
		$select .= " ORDER BY SUM(NumOf{$max_hour}Reactions) DESC ";
		$select .= " LIMIT $index, $page_size"; 
		
		return $select;
/*
 * 
SELECT FoodName, SUM(NumOfMeals) as NumOfMeals SUM(NumOf24Vomits) as NumOf24Vomits, SUM(NumOf6Vomits) as NumOf6Vomits, SUM(NumOf2Vomits) as NumOf2Vomits, SUM(NumOf2Vomits)  / SUM(NumOfMeals)
		FROM (
		
 * SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, COUNT( 1 ) AS NumOf24Vomits, MAX( 0 ) AS NumOf6Vomits, MAX( 0 ) AS NumOf2Vomits
FROM Food AS f
JOIN Reaction AS r ON TIMESTAMPDIFF( HOUR , r.ReactionDate, f.FoodDate ) 
BETWEEN -24
AND 0 
JOIN FoodType ft ON ft.FoodTypeId = f.FoodTypeId
WHERE r.ReactionTypeId =1-- Vomit 
				
				
 		$ticks = round($final/$interval);
		$column_names = array();
		$at_tick = $final;
		for($i; $i<=$ticks, $i++)
		{
			$column_names[$at_tick] = 'Hours' . $at_tick . 'FromVomit';
			$at_tick = $at_tick - $tick;
			$ticks++;
		}

		$at_tick = $final;
		$unions = array();
		for($i; $i<=$ticks, $i++)
		{
			$unionColumns = 
			$union = <<<UNION
SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, COUNT( 1 ) AS NumOf24Vomits
		FROM Meal AS m
		JOIN Event AS e ON TIMESTAMPDIFF( HOUR , e.EventDate, m.MealDate )
		BETWEEN -24
		AND 0
		JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
		WHERE e.EventTypeId =1
		GROUP BY ft.FoodName
					
UNION;

			
			$ticks++;
		
		
		
		SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, COUNT( 1 ) AS NumOf24Vomits
		FROM Meal AS m
		JOIN Event AS e ON TIMESTAMPDIFF( HOUR , e.EventDate, m.MealDate )
		BETWEEN -24
		AND 0
		JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
		WHERE e.EventTypeId =1-- Vomit
		GROUP BY ft.FoodName
		
		/*
		SELECT ft.FoodName, COUNT( 1 ) AS NumOfMeals, MAX( 0 ) AS NumOf24Vomits, MAX( 0 ) AS NumOf6Vomits, MAX( 0 ) AS NumOf2Vomits
		FROM Meal AS m
		JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
		GROUP BY ft.FoodName
		
		SELECT FoodName, SUM(NumOfMeals) as NumOfMeals SUM(NumOf24Vomits) as NumOf24Vomits, SUM(NumOf6Vomits) as NumOf6Vomits, SUM(NumOf2Vomits) as NumOf2Vomits, SUM(NumOf2Vomits)  / SUM(NumOfMeals)
		FROM (
		
				SELECT ft.FoodName, COUNT( 1 ) AS NumOfMeals, MAX( 0 ) AS NumOf24Vomits, MAX( 0 ) AS NumOf6Vomits, MAX( 0 ) AS NumOf2Vomits
				FROM Meal AS m
				JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
				GROUP BY ft.FoodName
		
				UNION ALL
		
				SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, COUNT( 1 ) AS NumOf24Vomits, MAX( 0 ) AS NumOf6Vomits, MAX( 0 ) AS NumOf2Vomits
				FROM Meal AS m
				JOIN Event AS e ON TIMESTAMPDIFF( HOUR , e.EventDate, m.MealDate )
				BETWEEN -24
				AND 0
				JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
				WHERE e.EventTypeId =1-- Vomit 
				GROUP BY ft.FoodName
		
				UNION ALL
		
				SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, MAX( 0 ) AS NumOf24Vomits, COUNT( 1 ) AS NumOf6Vomits, MAX( 0 ) AS NumOf2Vomits
				FROM Meal AS m
				JOIN Event AS e ON TIMESTAMPDIFF( HOUR , e.EventDate, m.MealDate )
				BETWEEN -6
				AND 0
				JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
				WHERE e.EventTypeId =1-- Vomit 
				GROUP BY ft.FoodName
		
				UNION ALL
		
				SELECT ft.FoodName, MAX( 0 ) AS NumOfMeals, MAX( 0 ) AS NumOf24Vomits, MAX(0) AS NumOf6Vomits, COUNT(1) AS NumOf2Vomits
				FROM Meal AS m
				JOIN Event AS e ON TIMESTAMPDIFF( HOUR , e.EventDate, m.MealDate )
				BETWEEN -2
				AND 0
				JOIN FoodType ft ON ft.FoodTypeId = m.FoodTypeId
				WHERE e.EventTypeId =1-- Vomit 
				GROUP BY ft.FoodName
		
		) AS FoodCounts
		GROUP BY FoodName
		HAVING SUM(NumOfMeals) > 1
		ORDER BY SUM(NumOf2Vomits)  / SUM(NumOfMeals)  DESC
		*/
	}
}
