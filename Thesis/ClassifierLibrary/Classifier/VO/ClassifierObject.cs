// ClassifierObject.cs created with MonoDevelop
// User: cmack at 11:13 PM 2/20/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Collections.Generic;
using System.Xml;
using System.Xml.Schema; 

namespace cfm.NaiveBayes
{
	
	public class ClassifierObject
	{
		private string name;
		private int timesInTraining;
		private Dictionary<string, WordObject> words;
		
		private bool usingPrior;		
		private int totalClassifiers; // kind of a hack but only way this fits into the framework		
		
		private bool usingLaplace;
		private int totalWords;
		
		private bool useMesitmate;
		private double pValue;
		
		public ClassifierObject(string n)
		{
			this.name = n;
			this.timesInTraining = 0;
			this.totalClassifiers = 1;
			this.totalWords = 1;
			this.words = new Dictionary<string,WordObject>();
			this.usingPrior = false;
			this.usingLaplace = false;
			this.useMesitmate = false;
			this.pValue = 1d;
		}

		/// <summary>
		/// Set the total number of classifiers used in this system.
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void SetTotalClassifiers(int total)
		{
			this.totalClassifiers = total; 
		}

		/// <summary>
		/// Set an in memory attribute rather than read from the file over and over again
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void SetUsingPrior(bool use)
		{
			this.usingPrior = use; 
		}
		
		/// <summary>
		/// Set an in memory attribute rather than read from the file over and over again
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void SetLaplace(bool use)
		{
			this.usingLaplace = use; 
		}

		/// <summary>
		/// Set the total number of words used in this system.
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void SetTotalWords(int total)
		{
			this.totalWords = total; 
		}

		/// <summary>
		/// Set an in memory attribute rather than read from the file over and over again
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void UseMestimate(bool use)
		{
			this.useMesitmate = use; 
		}
		
		/// <summary>
		/// Set the total number of words used in this system.
		/// This will be used to set the m-estimate
		/// </summary>
		/// <param name="total">
		/// A <see cref="System.Int32"/>
		/// </param>
		public void SetPValue(double pValue)
		{
			this.pValue = pValue; 
		}
		
		/// <summary>
		/// Get the words used by this classifier
		/// </summary>
		/// <returns>
		/// A <see cref="Dictionary`2"/>
		/// </returns>
		public Dictionary<string, WordObject> GetWords()
		{
			return this.words;
		}

		public WordObject GetWord(string ret)
		{
			if (!this.words.ContainsKey(ret))
			{
				throw new Exception(String.Format("Word, {0}, is not in this category, {1}.", ret, this.name));
			}
			return this.words[ret];
		}
		
		/// <value>
		/// Count number of times this category is used in the training set
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

		/// <value>
		/// Name of Classifier 
		/// </value>
		public string Name
		{
			get
			{
				return this.name;
			}
			set
			{
				this.name = value;
			}
		}
		
		/// <summary>
		/// Add a word to the internal hash table
		/// </summary>
		/// <param name="word">
		/// A <see cref="System.String"/>
		/// </param>
		/// <returns>
		/// A <see cref="System.Boolean"/>
		/// </returns>
		public bool AddWord(string word, string article)
		{
			WordObject wordObj;
			//article = FindWords.CleanArticle(article);
			int position = article.IndexOf(word);
			
			
			// Add the word to the hash
			if (this.words.ContainsKey(word).Equals(false))
			{
				wordObj = new WordObject(word);
				this.words.Add(word, wordObj);
			}

			// If the word is found in this article, calculate the statistics
			wordObj = this.words[word];
			
			// Prior based on total classifiers
			if (this.usingPrior == true)
			{
				if (position >= 0)
				{
					wordObj.Seen();
				}
				
				// Using Dirichlet Prior
				double numerator = (double)wordObj.TimesInTraining + 1d;
				double denominator = (double)this.timesInTraining + (double)this.totalClassifiers;
				double prob = numerator / denominator;
				wordObj.SetProb(prob);
			}
			else if (this.usingLaplace == true)
			{
				if (position >= 0)
				{
					wordObj.Seen();
				}
				
				// Using Laplace Smoothing
				// Add a virtual document for each word
				double numerator = (double)wordObj.TimesInTraining + 1d;
				double denominator = (double)this.timesInTraining + (double)this.totalWords;
				double prob = numerator / denominator;
				wordObj.SetProb(prob);
			}
			else if (this.useMesitmate == true)
			{
				if (position >= 0)
				{
					wordObj.Seen();
				}
				
				
				double numerator = (double)wordObj.TimesInTraining + (1d * this.pValue);
				double denominator = (double)this.timesInTraining + (double)this.totalWords;
				double prob = numerator / denominator;
				wordObj.SetProb(prob);
			}
			// we are not using any "special" techniques
			// if the word in this article is found, adjust probabilites
			else if (position >= 0)
			{
				
				wordObj.Seen();
				double prob = ((double)wordObj.TimesInTraining / (double)this.timesInTraining);
				wordObj.SetProb(prob);
			}
			// We have not seen this word before in this classifier
			// Change the default to Min Value
			else if (wordObj.GetProb() == 0)
			{
				// We can't let the prob go to zero
				// Calculate the M-estimate
				double prob = ClassifierObject.GetMinValue(); 
				wordObj.SetProb(prob);
			}
			
			return true;
		}
		
		/// <summary>
		/// Encapsulate in case Windows.Net is different than Linux.Mono
		/// </summary>
		/// <returns>
		/// A <see cref="System.Double"/>
		/// </returns>
		static public double GetMinValue()
		{
			return Math.Pow(10d, -323d);
		}
		
		public string ToString(bool showWords)
		{
			string str = "Name: " + this.name + "\n";
			str += "Count in training: " + this.timesInTraining + "\n";
			str += "Total Number of classifiers: " + this.totalClassifiers + "\n"; 
			str += "Total Number of words: " + this.totalWords + "\n";
			str += "P Value: " + this.pValue + "\n";
			if (this.words.Count > 0 && showWords == true)
			{
				foreach(WordObject wordObj in this.words.Values)
				{
					str += wordObj.ToString();
				}
			}
			return str;
		}
	}
	
	
}
