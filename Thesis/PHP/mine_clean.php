<?php

class Mine
{
	public $cats = array();

	function Run()
	{

		$link = mssql_connect('SITE', '********', '**********');
		mssql_select_db('Reuters21578');
		//mssql_select_db('Spam');

		$sql = $this->ReutersTrainSql();
		$results = mssql_query($sql, $link);

		while($row = mssql_fetch_object($results))
    	{
    		if (!isset($this->cats[$row->Name]))
    		{
    			$this->cats[$row->Name] = new Articles($row->Name);
    		}
    		$this->cats[$row->Name]->articleCount++;

    		$row->Body = $this->Clean($row->Body);
    		$words = explode($this->Delimiter(), $row->Body);
    		foreach($words as $word)
    		{
    			$art = $this->cats[$row->Name];
    			$art->AddWord($word);
    			$this->cats[$row->Name] = $art;
    		}
    	}
		mssql_close();

		$this->Display();
	}

	function Clean($article)
	{
		$remove = array('.',',','-','#', '@', '!', '?', '^', '%');
		foreach($remove as $rem)
		{
			$article = str_replace($rem, '', $article);
		}
		$article = trim(strtolower($article));

		return $article;
	}

	function Delimiter()
	{
		return " ";
	}

	function SpamTrainSql()
	{
		return "	SELECT	Body, Category AS Name
					FROM	Spam
					WHERE	ArticleId % 3 > 0";
	}

	function SpamTestSql()
	{
		return "	SELECT	Body, Category AS Name
					FROM	Spam
					WHERE	ArticleId % 3 = 0";
	}

	function ReutersTestSql()
	{
		return "SELECT a.Body, ct.Name
		FROM	Article AS a
		JOIN	ArticleCategory AS ac
		ON		a.ArticleId = ac.ArticleId
		JOIN	Category AS c
		ON		c.CategoryId = ac.CategoryId
		JOIN	CategoryType AS ct
		ON		c.CategoryTypeId = ct.CategoryTypeId
		WHERE	a.Body IS NOT NULL
		AND		a.Body NOT LIKE '%Shr%vs%'
		AND		a.Body NOT LIKE 'Shr%vs%'
		AND		a.LewisSplit = 'Test'";
	}

	function ReutersTrainSql()
	{
		return "SELECT a.Body, ct.Name
		FROM	Article AS a
		JOIN	ArticleCategory AS ac
		ON		a.ArticleId = ac.ArticleId
		JOIN	Category AS c
		ON		c.CategoryId = ac.CategoryId
		JOIN	CategoryType AS ct
		ON		c.CategoryTypeId = ct.CategoryTypeId
		WHERE	a.Body IS NOT NULL
		AND		a.Body NOT LIKE '%Shr%vs%'
		AND		a.Body NOT LIKE 'Shr%vs%'
		AND		a.LewisSplit = 'Train'";
	}

	function Display()
	{
		foreach($this->cats as $cat)
		{
			echo $cat->ToString();
		}
	}
}

class Articles
{
	public $name;
	public $totalArticleSize;
	public $articleCount;
	public $uniqueWords;
	public $totalLetters;


	public function __construct($name)
	{
		$this->name				= $name;
		$this->totalArticleSize = 0;
		$this->articleCount 	= 0;
		$this->totalLetters		= 0;
		$this->uniqueWords 		= array();
	}

	public function AverageArticleLength()
	{
		return $this->totalArticleSize / $this->articleCount;
	}

	public function AddWord($word)
	{
		$word = trim(strtolower($word));
		if (!in_array($word, $this->uniqueWords) && $word != '_MUSIC_')
		{
			$this->uniqueWords[] = $word;
		}

		$this->totalLetters += strlen($word);

		if ($word != '_MUSIC_')
		{
			$this->totalArticleSize++;
		}
	}

	public function ToString()
	{
		$uniques = count($this->uniqueWords);
		$x  = "\nCat Name: " . $this->name;
		$x .= "\nArticle Count: " . $this->articleCount;
		$x .= "\nAverage Article Length: " . $this->AverageArticleLength();
		$x .= "\nTotal Words: " . $this->totalArticleSize;
		$x .= "\nUnique Words: " .  $uniques;
		$x .= "\nRatio of Uniques to Total: " .  $uniques / $this->totalArticleSize;
		$x .= "\nTotal Letters: " .  $this->totalLetters;
		$x .= "\nAverage Word Size: " .  $this->totalLetters / $this->totalArticleSize;
		$x .= "\n";

		return $x;
	}
}

$mine = new Mine();
$mine->Run();

?>