// NaiveClassifier.cs created with MonoDevelop
// User: cmack at 11:00 PM 2/20/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
/// <summary>
/// P( Y | X ) = probablity of Y, given X
/// P( X | Y ) = probablity of X, given Y
/// P ( Y ) = probablity of Y
/// P ( X ) = probablity of X 
/// 
///  Bayes Theorum
/// P( Y | X ) =( P ( X | Y ) * P ( Y ) ) / P ( X )
/// 
/// Naive Bayes Classifier with i traits
/// P( X | Y = y ) = ( P( Y ) * PRODUCT( P (Xi | Y ) ) ) / P(X)   
/// 
/// EX: 
/// Probablity this is a Animal Artical
/// X = (Word1= lion, Word2=bird, Word3=ant)
/// P( X | Animal = yes ) = P( Word1 = lion | Animal=yes) * P( Word2 = bird | Animal=yes) * P(Word3=ant | Animal = yes )  
/// 
/// </summary>

using System;
using System.Xml;
using System.IO;
using System.Collections;
using System.Collections.Generic;
using System.Data;
using System.Configuration;

namespace cfm.NaiveBayes
{
	public abstract class NaiveClassifier
	{
		protected ClassifierObject[] classifiers;
		protected ArrayList wordList;
		
		
		public NaiveClassifier()
		{
			this.wordList = new ArrayList();
		}
		
		public void Train()
		{
			// Read external file into the word list
			this.PopulateWordList();

			// Find the probablity of each word showing up in each type
			this.DefineCategories();
			
			int totalSampleSize = 0;
			int totalClassifiers = this.classifiers.Length;
			int totalWords = this.wordList.Count;
			bool usingPrior = NaiveClassifier.UsePrior();
			bool usingLaplse = NaiveClassifier.UseLaplace();
			bool usingMestimate = NaiveClassifier.UseMestimate();
			
			// Main loop - Cycle through each classifier type
			foreach(ClassifierObject classy in this.classifiers)
			{
				// Add attributes to save file reads
				classy.SetTotalClassifiers(totalClassifiers);				
				classy.SetTotalWords(totalWords);
				classy.SetUsingPrior(usingPrior);
				classy.SetLaplace(usingLaplse);
				classy.UseMestimate(usingMestimate);
				
				// Set counts of each type
				this.SetTrainingCount(classy);
				totalSampleSize += classy.TimesInTraining;
			}

			Console.WriteLine("Total Sample Size: " + totalSampleSize);
			                  
			// Main loop - Cycle through eacy classifier type
			foreach(ClassifierObject classy in this.classifiers)
			{
				Console.WriteLine("Defining Classifier: " + classy.Name);
				
				// Get and cycle through each article of this type
				DataTable articles = this.GetTrainingArticles(classy);
				foreach (DataRow row in articles.Rows)
				{
					string article = (string)row["Body"];
					article = FindWords.CleanArticle(article);
					                              
					// Cycle through each candidate word
					foreach( string word in this.wordList )
					{
						classy.AddWord(word, article);
					}
				}
				Console.WriteLine(classy.ToString(false));
			}
			
			this.WriteToXml();
		}
		
		/// <summary>
		/// Read the xml file and build the category objects
		/// </summary>
		public void DefineCategories()
		{
			Dictionary<string, double> catList = NaiveClassifier.ReadXml();
			this.classifiers = new ClassifierObject[catList.Count];

			// Build the classifier objects into a common array
			int count = 0;
			foreach( string cat in catList.Keys )
			{
				this.classifiers[count] = new ClassifierObject(cat);
				
				// Add pValue even if we aren't using it in this configuration
				double pValue = catList[cat];
				this.classifiers[count].SetPValue(pValue);
				
				count++;
			}
		}
		
		

		/// <summary>
		/// Read the categories.xml file and put all the category names in an array list
		/// </summary>
		/// <returns>
		/// A <see cref="ArrayList"/>
		/// </returns>
		public static Dictionary<string, double> ReadXml()
		{
			Dictionary<string, double> cats = new Dictionary<string, double>();
			try
			{ 

				XmlTextReader reader = new XmlTextReader(NaiveClassifier.GetCategoryFilestring());
				string testString="";
				
				string tempName = "";
				string tempP = "";
				
				while (reader.Read()) 
				{
					if (reader.NodeType == XmlNodeType.Element)
					{
						if (reader.Name == "name")
						{
							testString=reader.Name;
						}
						else if (reader.Name == "p")
						{
							testString=reader.Name;
						}
					}
					else if (reader.NodeType == XmlNodeType.Text)
					{
						if (testString == "name")
			        	{
							tempName = reader.Value;
							//cats.Add(reader.Value);				 
				        }
						else if ( testString == "p" )
						{
							tempP = reader.Value;
							if ( tempName != "" )
							{
								// Add to dictionary
								if (!cats.ContainsKey(tempName))
								{
									cats.Add(tempName, Convert.ToDouble(tempP));
								}
							}
							
							// Reset
							tempName = "";
							tempP = "";
						}
					}
				}
			}
			catch(Exception ex)
			{
				Console.WriteLine(ex.ToString());
			}
			
			return cats;
		}

		/// <summary>
		/// Write the classifiers to an XML document to be read by the training set
		/// </summary>
		private void WriteToXml()
		{
			try
			{
				XmlTextWriter writer = new XmlTextWriter(NaiveClassifier.GetTrainingFilestring(),null);
				writer.Formatting = Formatting.Indented;
				writer.WriteStartDocument();
				writer.WriteStartElement("classifiers");
				foreach(ClassifierObject classy in this.classifiers)
				{
					writer.WriteStartElement("classifier");
					
					// Write the classifiers name
					writer.WriteStartElement("name");
					writer.WriteString(classy.Name);
					writer.WriteEndElement();
					
					// Write times in training
					writer.WriteStartElement("training_count");
					writer.WriteString(String.Format("{0}", classy.TimesInTraining));
					writer.WriteEndElement();
					writer.WriteStartElement("words");
					
					Dictionary<string, WordObject> words = classy.GetWords();
					foreach(WordObject wordObj in words.Values)
					{
						writer.WriteStartElement("word");
						
						// write the text of the word
						writer.WriteStartElement("text");
						writer.WriteString(wordObj.GetWord());
						writer.WriteEndElement();
						
						// write the probability of seeing this word
						writer.WriteStartElement("probability");
						writer.WriteString(String.Format("{0}", wordObj.GetProb()));
						writer.WriteEndElement();
						
						writer.WriteStartElement("times_seen");
						writer.WriteString(String.Format("{0}", wordObj.TimesInTraining));
						writer.WriteEndElement();
						
						// close word
						writer.WriteEndElement();
					}
					
					// close words
					writer.WriteEndElement();
					
					// Close this classifer
					writer.WriteEndElement();
				}
				
				// Close classifiers
				writer.WriteEndElement();
				
				// Close the xml doc
				writer.WriteEndDocument();
				writer.Flush();
				writer.Close();
			}
			catch(Exception ex)
			{
				Console.WriteLine(ex.ToString());
			}
			
		}
		
		/// <summary>
		/// Read the word file built through FindWords and populate the internal data structure
		/// </summary>
		private void PopulateWordList()
		{
			StreamReader reader = File.OpenText(FindWords.GetFileName());
			string input = null;
			while ((input = reader.ReadLine()) != null)
			{
				string[] candidate = input.Split(',');
				this.wordList.Add(candidate[0]);
			}
		}

		/// <summary>
		/// Encapsulate the file string that contains the training information
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public string GetTrainingFilestring()
		{
			return ConfigurationManager.AppSettings["trainingFile"];
		}

		/// <summary>
		/// Encapsulate the file string that contains the category information
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public string GetCategoryFilestring()
		{
			return ConfigurationManager.AppSettings["categoryFile"];
		}
		
		/// <summary>
		/// Encapsulate whether we are allowing m-estimates based on categories
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public bool UsePrior()
		{
			return Convert.ToBoolean(ConfigurationManager.AppSettings["usePrior"]);
		}		

		/// <summary>
		/// Encapsulate whether we are doing Laplase smoothing
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public bool UseLaplace()
		{
			return Convert.ToBoolean(ConfigurationManager.AppSettings["useLaplace"]);
		}

		/// <summary>
		/// Encapsulate whether we are using M estimates
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public bool UseMestimate()
		{
			return Convert.ToBoolean(ConfigurationManager.AppSettings["useMestimate"]);
		}
		
		protected abstract void SetTrainingCount(ClassifierObject cat);
		
		protected abstract DataTable GetTrainingArticles(ClassifierObject cat);
	}
}
