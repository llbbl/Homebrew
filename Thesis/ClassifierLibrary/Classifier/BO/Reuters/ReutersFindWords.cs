// ReutersFindWords.cs created with MonoDevelop
// User: cmack at 8:21 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Collections.Generic;
using NLPLibrary;

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

        protected override WordObject[] SplitWords(string article)
        {
            List<WordObject> words = new List<WordObject>();
            
            POSLib pos = new POSLib(FindWords.GetNLPModelPath());
            
            string[] sentences = pos.SplitSentences(article);
            foreach (string sentence in sentences)
            {
                string[] tokens = pos.TokenizeSentence(sentence);
                string[] tags = pos.PosTagTokens(tokens);

                for (int currentTag = 0; currentTag < tags.Length; currentTag++)
                {
                    words.Add(this.AddWordObj(tokens[currentTag], tags[currentTag]));
                }
            }

            return words.ToArray();
        }

        private WordObject AddWordObj(string word, string POS)
        {
            WordObject w = new WordObject(word);

            if (POSLib.IsNoun(POS) == true)
            {
                w.PartOfSpeech = POSLib.NOUN;
            }

            return w;
        }
	}
}
