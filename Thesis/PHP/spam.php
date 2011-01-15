<?php

class Spam
{
	function Run()
	{
		$link = mssql_connect('SITE', '*******', '********');
		mssql_select_db('Spam');

		$files 		= array();
		//$files[] 	= "dataset1-csv.txt";
		$files[] 	= "dataset2-csv.txt";
		$files[] 	= "dataset3-csv.txt";
		$files[] 	= "dataset4-csv.txt";
		$files[] 	= "dataset5-csv.txt";

		foreach ($files as $fileName)
		{
			$file = file($fileName);
			foreach ($file as $line)
			{
				$this->Populate($line, $link);
			}
		}

		mssql_close();
	}

	function Populate($line, $link)
	{
		$words 	= explode(',', $line);
		$count  = count($words);
		$title 	= $words[0];
		$cat 	= $words[1];

		$body = '';
		for($i = 2; $i <= ($count - 1); $i++ )
		{
			$pair = $words[$i];
			$x = explode(':', $pair);
			if (count($x) == 2 && $x[0] == 'W')
			{
				$body .= ' ' . $x[1];
			}
		}

		//
		$sql = "INSERT INTO Spam ( Title, Category, Body ) VALUES ('$title', '$cat', '$body'); ";
		echo $sql . "<br />";
		mssql_query($sql, $link);

	}
}

$spam = new Spam();
$spam->Run();

?>