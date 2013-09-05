<?php

class Result_model extends CI_Model {

    const NO_FILTER = 'no-filter';

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
	
	function hours_from_reaction($index, $page_size, $num_of_gaps, $scale, $sort, $start_date, $end_date, $reaction_id, $min_eaten, $initial_hour, $food_filter)
	{
        //($index, $page_size, $sort_str)
		$hour_gaps = array();
        $hour = $initial_hour;
		for($i = 1; $i <= $num_of_gaps; $i++)
		{
			if ($scale == 'quadratic')
			{
				$hour_gaps[$i] = pow($hour, 2);
			}
			else if ($scale == 'exponential')
			{
				$hour_gaps[$i] = pow(2, $hour);
			}
			else //if ($scale == 'linear')
			{
				$hour_gaps[$i] = $hour;
			}

            $hour += 1;
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
				$sub .= " FROM Food AS f JOIN FoodType ft ON ft.FoodTypeId = f.FoodTypeId  ";
				$sub .= " WHERE FoodDate >= '$start_date' ";
				$sub .= " AND FoodDate <= '$end_date' ";
				$sub .= " GROUP BY ft.FoodName ";
				$subs[] = $sub;
			}
			else 
			{
				$sub  = "SELECT ft.FoodName, {$column_str} ";
				$sub .= " FROM Food AS f JOIN Reaction AS r ON TIMESTAMPDIFF( HOUR , r.ReactionDate, f.FoodDate ) ";
				$sub .= " BETWEEN -" . $hour_gaps[$i] . " AND 0 ";
				$sub .= " JOIN FoodType ft ON ft.FoodTypeId = f.FoodTypeId ";
				$sub .= " WHERE r.ReactionTypeId = $reaction_id ";
				$sub .= " AND FoodDate >= '$start_date' ";
				$sub .= " AND FoodDate <= '$end_date' ";
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
            $columns[] = "SUM(NumOf" . $hour_gaps[$i] . "Reactions)/SUM(NumOfFood) as PercentOf" . $hour_gaps[$i] . "Reactions";
		}
		
		$select  = "SELECT " . implode($columns, " , ") . " FROM ( ";
		$select .= implode($subs, " UNION ALL ");
		$select .= " ) AS FoodCounts ";

        if ($food_filter != self::NO_FILTER)
        {
            $select .= " WHERE FoodName LIKE '$food_filter' ";
        }

        $select .= " GROUP BY FoodName ";
        $select .= " HAVING SUM(NumOfFood) >= $min_eaten ";

        $sort = explode(' ', $sort);


		
		if (trim($sort[0]) == "FoodName")
		{
			$select .= " ORDER BY " . trim($sort[0]). " " . trim($sort[1]);
		}
        else if (stripos($sort[0], "Percent") !== false)
        {
            $sort[0] = str_replace("Percent","Num", $sort[0]);
            $select .= " ORDER BY SUM(" . trim($sort[0]). ") / SUM(NumOfFood) " . trim($sort[1]); // only works because of the naming convention
        }
		else
		{
			$select .= " ORDER BY SUM(" . trim($sort[0]). ") " . trim($sort[1]); // only works because of the naming convention
		}
		
		$select .= " LIMIT $index, $page_size"; 

		$query = $this->db->query($select);
		
		return $query->result_array();
	}
	
}
