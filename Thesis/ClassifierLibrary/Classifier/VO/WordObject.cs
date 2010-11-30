// WordObject.cs created with MonoDevelop
// User: cmack at 5:55 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;

namespace cfm.NaiveBayes
{
	
	public class WordObject
	{
		private string word; // word
		private double prob;  // probability of word being seen 
		private int timesInTraining; // number of times seen in training
		
		public WordObject( string w )
		{
			this.word = w;
			this.prob = 0;
			this.timesInTraining = 0;
		}
		
		public string GetWord()
		{
			return this.word;
		}
		
		public double GetProb()
		{
			return this.prob;
		}

		// Do heavy lifting here, calculate probablity
		public void Seen()
		{
			this.timesInTraining++;
		}
		
		public void SetProb(double p)
		{
			this.prob = p;
		}
		
		/// <value>
		/// Count number of times this word is used in the category
		/// </value>
		public int TimesInTraining
		{
			get
			{
				return this.timesInTraining;
			}
			set
			{
				this.timesInTraining = value;
			}
		}
		
		public override string ToString()
		{
			string str = String.Format("Word: {0}\nProbablity: {1}\nTimes: {2}\n", this.word, this.prob ,this.TimesInTraining);
			return str;
		}
	}
}
