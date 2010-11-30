// ReutersFindWords.cs created with MonoDevelop
// User: cmack at 8:21 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;

namespace cfm.NaiveBayes
{
	public class ReutersFindWords : FindWords
	{
		
		public ReutersFindWords()
		{
		}
		
		public override string GetArticleQuery()
		{
			return  @"SELECT    Body 
                        FROM    Article
                        WHERE   Body IS NOT NULL
                        AND     Body != ''
                        AND     Body NOT LIKE '%Shr%vs%'
                        AND     Body NOT LIKE 'Qtly%vs%'
				        ";
		}

        protected override string[] SplitWords(string article)
        {
            return article.Split(' ');
        }
	}
}
