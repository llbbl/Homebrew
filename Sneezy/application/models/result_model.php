<?php

class Result_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function timeline_data() 
	{
		$sql = <<<SQL
select EventId as Id, EventDate as Date, EventName as Name, EventNote as Note, 'Event' as Type 
from Event as e 
join EventType et on e.EventTypeId = et.EventTypeId 
WHERE e.IsDeleted = 0
UNION
select MealId as Id, MealDate as Date, FoodName as Name, MealNote as Note, 'Meal' AS Type
from Meal as m 
join FoodType ft on m.FoodTypeId = ft.FoodTypeId 
WHERE m.IsDeleted = 0
SQL;
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function hours_from_event($final, $interval, $event_id)
	{
/*
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
