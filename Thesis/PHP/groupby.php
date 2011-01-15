<?php

class GroupBy
{
	function Run()
	{
		
		$files 		= array();
		//$files[] 	= "dataset1-csv.txt";
		$files[] 	= "all_reuters_words.csv";
		$files[] 	= "best_reuters_words.csv";
		$prefix = 'group-by-';
		
		foreach ($files as $fileName)
		{
			$file = file($fileName);
			$agg = array();
			
			foreach ($file as $line)
			{
				$words = explode(",", $line);
				if (isset($words[1]))
				{
					$num = trim($words[1]);
					
					if (!isset($agg[$num]))
					{
						$agg[$num] = 0;
					}
					
					// add to array
					$agg[$num]++;
				}
			}
			
			$toFileName = $prefix . $fileName;
			$toFile =  fopen($toFileName, 'w+');
			foreach($agg as $key=>$count)
			{
				$str = $key . ',' . $count . "\n";
				fwrite($toFile, $str);
			}
			
			fclose($toFile);
		}
	}
}

$groupBy = new GroupBy();
$groupBy->Run();

?>