// NaiveTester.cs created with MonoDevelop
// User: cmack at 8:49 PM 5/1/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data;
using System.Data.SqlClient;
using System.Collections;
using System.Collections.Generic;
using System.Xml;
using System.IO;
using System.Configuration;

namespace cfm.NaiveBayes
{
	public abstract class NaiveTester
	{

		protected ClassifierObject[] classifiers;
		
		public NaiveTester()
		{
		}
		
		public void Test()
		{
			//populate the classifiers from XML
			Console.WriteLine("Buiding Training Objects");
			this.BuildTrainingObjects();

			Console.WriteLine("Getting Test Articles ");
			DataTable articles = this.GetTestArticles();
			ArrayList results = new ArrayList();
			
			Console.WriteLine("Classifying Test Articles");
			foreach (DataRow row in articles.Rows)
			{
				
				int articleId = (int)row["ArticleId"];

				string article = (string)row["Body"];
				article = FindWords.CleanArticle(article);
				
				string knownType = (string)row["CategoryName"];
				knownType = knownType.ToLower();

				string testType = "unknown";
				double highestLog = Math.Log(ClassifierObject.GetMinValue());

				bool allowMinValue = Convert.ToBoolean(NaiveTester.GetAllowMinValueConfig());
				bool usePrior = NaiveClassifier.UsePrior();
				bool useLaplace = NaiveClassifier.UseLaplace();
				bool useMestimate = NaiveClassifier.UseMestimate();
				
				// Track that this article hit on at lease a single word.  
				// This is an attempt to understand why we get so many "unknown"
				bool hitOnWord = false;

				foreach( ClassifierObject classy in this.classifiers )
				{
					double testLog = double.MinValue;
					Dictionary<string, WordObject> words = classy.GetWords();
					foreach(WordObject word in words.Values)
					{
						double wordProb = word.GetProb();
						double wordLog = Math.Log(wordProb);
						
						int position = article.IndexOf(word.GetWord());
						
						/**
						 * If the word is found in this article, add this log value
						 * There are 3 configuration options:
						 * 1. allowMinValue = configuration option that allows the words that are never found in the training set
						 * 					  to be used in the calculation of the class of this article.0
						 * 2. usePrior = by using the prior, the wordProb will never be zero.  It uses the Dirichlet Prior.
						 * 
						 * 3. If both of these configuration options are off but the word is still found in the classifier training AND this article
						 * make sure to include the word in the the calculation of this classification.
						 * 
						 * 4. useLaplace = by using this smoothing, we add a virtual document for each word
						 * 
						 * 5. useMestimate = use laplace but adjust the p value to match the probablitiy of the seeing this value
						 */
						if (position >= 0 && (wordProb > ClassifierObject.GetMinValue() || allowMinValue == true || usePrior == true || useLaplace == true || useMestimate == true))
						{
							// First time through, the log just needs to use the value of the first log 
							// Should testLog default to a small double value?
							if (testLog == double.MinValue)
							{
								testLog = wordLog;
							}
							else
							{
								
								testLog = testLog + wordLog;
							}
							
							hitOnWord = true;
						}
					}
					
					// if we have a higher probablity, this is our new class type
					if (testLog > highestLog)
					{
						//Console.WriteLine(String.Format("Reassigning Class: {0}\nTest: {1}\nHighest:{2}\n", classy.Name, testLog, highestLog));
						highestLog = testLog;
						testType = classy.Name.ToLower();
					}
				}

		
				double highestProb = Math.Exp(highestLog);
				results.Add(new TestObject(articleId, knownType, testType, highestProb));

				if (hitOnWord == false)
				{
					Console.WriteLine(String.Format("No words hit on Article: {0}\nTest Type: {1}\nKnown Type: {2}\nHighestProb: {3}\n\n",articleId, testType, knownType, highestProb));
				}
				 
			}
			
			Console.WriteLine("Saving Raw Results");
			this.WriteResultFile(results);

			Console.WriteLine("Saving Summary Information");
			this.WriteSummaryXml(results);
		}

		private void BuildTrainingObjects()
		{
			try
			{ 
				// Read the categories.xml file and determine how many categories are defined
				Dictionary<string, double> definedCategories = NaiveClassifier.ReadXml();
				int numOfCats = definedCategories.Count;
				this.classifiers = new ClassifierObject[numOfCats];
				
				XmlTextReader reader = new XmlTextReader(NaiveClassifier.GetTrainingFilestring());
				
				// Initialize the values need to keep track of the XML document
				int classifierCount = -1;
				string testString = "";
				string tempWord = "";				
				ClassifierObject classy = new ClassifierObject("");
				
				while (reader.Read()) 
				{
					if (reader.NodeType == XmlNodeType.Element)
					{
				       	testString=reader.Name;
						if (reader.Name == "classifier")
						{
							classifierCount++;
							classy = new ClassifierObject("");
							this.classifiers[classifierCount] = classy;
						}
						else if (reader.Name == "word")
						{
							tempWord = "";
						}
					}
					else if (reader.NodeType == XmlNodeType.Text)
					{
						if (testString == "name")
			        	{
							this.classifiers[classifierCount].Name = reader.Value;
				        }
						else if (testString == "training_count")
						{
							this.classifiers[classifierCount].TimesInTraining = Convert.ToInt32(reader.Value);
						}
						else if (testString == "text")
			        	{
							tempWord = reader.Value;
							this.classifiers[classifierCount].AddWord(tempWord, "");
						}
						else if (testString == "probability")
						{
							WordObject wordObj = this.classifiers[classifierCount].GetWord(tempWord);
							wordObj.SetProb(Convert.ToDouble(reader.Value));
						}
						else if (testString == "times_seen")
						{
							WordObject wordObj = this.classifiers[classifierCount].GetWord(tempWord);
							wordObj.TimesInTraining = Convert.ToInt32(reader.Value);
						}
					}
				}
			}
			catch(Exception ex)
			{
				Console.WriteLine(ex.ToString());
			}
			
		}

		public void WriteResultFile(ArrayList results)
		{
			// Write result file
			TextWriter writer = new StreamWriter(NaiveTester.GetResultFilestring());
			foreach(TestObject result in results)
			{
				writer.WriteLine(result.ToString());
			}
			writer.Close();
		}

		public void WriteSummaryFile(ArrayList results)
		{
			// Write Summary
			// Each category 
			//	- summary the total stored correctly
			//	- summary of the total incorrectly grouped by category
			// Total Summary
			int total = 0;			
			int totalCorrect = 0;
			int totalIncorrect = 0;

			int catTotal = 0;
			int catCorrect = 0;
			int catIncorrect = 0;

			// Write result file
			TextWriter writer = new StreamWriter(NaiveTester.GetSummaryFilestring());
			
			foreach( ClassifierObject classy in this.classifiers)
			{
				catTotal = 0;
				catCorrect = 0;
				catIncorrect = 0;
				Dictionary<string, int> incorrectResults = new Dictionary<string,int>();
				foreach(TestObject result in results)
				{
					if (result.KnownType == classy.Name.ToLower())
					{
						catTotal++;
						total++;
						if (result.Correct == 1)
						{
							catCorrect++;
							totalCorrect++;
						}
						else 
						{
							catIncorrect++;
							totalIncorrect++;
							if (!incorrectResults.ContainsKey(result.ResultType))
							{
								incorrectResults.Add(result.ResultType, 1);
							}
							else
							{
								incorrectResults[result.ResultType]++;
							}	
						}
					}
				}
				
				writer.WriteLine("**********************************************");
				writer.WriteLine("Category: " + classy.Name);
				writer.WriteLine("Total: " + catTotal);
				writer.WriteLine("Correct: " + catCorrect);
				writer.WriteLine("Incorrect: " + catIncorrect);
				
				foreach(string cat in incorrectResults.Keys)
				{
					writer.WriteLine("Incorrect Category (" + cat + "): " + incorrectResults[cat]);
				}
				
				writer.WriteLine();
				writer.WriteLine();
			}
			
			writer.WriteLine("**********************************************");
			writer.WriteLine("Total: " + total);
			writer.WriteLine("Total Correct: " + totalCorrect);
			writer.WriteLine("Total Incorrect: " + totalIncorrect);
			
			writer.WriteLine();
			
			double percentCorrect = (double)totalCorrect / (double)total;
			percentCorrect = Math.Round(percentCorrect,2) * 100;
			writer.WriteLine(String.Format("Percent Correct: {0}%",percentCorrect));

			double percentIncorrect = (double)totalIncorrect / (double)total;
			percentIncorrect = Math.Round(percentIncorrect,2) * 100;
			writer.WriteLine(String.Format("Percent Incorrect: {0}%",percentIncorrect));			
			
			writer.WriteLine();
			
			writer.Close();
		}

		// Write the results to an xml file
		public void  WriteSummaryXml(ArrayList results)
		{
			int total = 0;			
			int totalCorrect = 0;
			int totalIncorrect = 0;

			int catTotal = 0;
			int catCorrect = 0;
			int catIncorrect = 0;

			// Write result file
			XmlTextWriter writer = new XmlTextWriter(NaiveTester.GetSummaryFilestring(),null);
			writer.Formatting = Formatting.Indented;
			writer.WriteStartDocument();
			writer.WriteStartElement("summary");
			
			writer.WriteStartElement("classifiers");
			
			foreach( ClassifierObject classy in this.classifiers)
			{
				
				catTotal = 0;
				catCorrect = 0;
				catIncorrect = 0;
				Dictionary<string, int> incorrectResults = new Dictionary<string,int>();
				foreach(TestObject result in results)
				{
					if (result.KnownType == classy.Name.ToLower())
					{
						catTotal++;
						total++;
						if (result.Correct == 1)
						{
							catCorrect++;
							totalCorrect++;
						}
						else 
						{
							catIncorrect++;
							totalIncorrect++;
							if (!incorrectResults.ContainsKey(result.ResultType))
							{
								incorrectResults.Add(result.ResultType, 1);
							}
							else
							{
								incorrectResults[result.ResultType]++;
							}	
						}
					}
				}
				
				writer.WriteStartElement("classifier");
				
				// Write the classifiers name
				writer.WriteStartElement("name");
				writer.WriteString(classy.Name);
				writer.WriteEndElement();
				
				// Write times in training
				writer.WriteStartElement("training_cat_count");
				writer.WriteString(String.Format("{0}", classy.TimesInTraining));
				writer.WriteEndElement();
				
				// Write total in category
				writer.WriteStartElement("category_total");
				writer.WriteString(String.Format("{0}", catTotal));
				writer.WriteEndElement();

				// Write total correct in category
				writer.WriteStartElement("category_correct");
				writer.WriteString(String.Format("{0}", catCorrect));
				writer.WriteEndElement();

				// Write percent correct in category
				writer.WriteStartElement("category_percent_correct");
				
				double catPercentCorrect = 0;
				if (catTotal > 0)
				{ 
					catPercentCorrect = (double)catCorrect / (double)catTotal;
				}	
				catPercentCorrect = Math.Round(catPercentCorrect,6) * 100;
				writer.WriteString(String.Format("{0}", catPercentCorrect));
				writer.WriteEndElement();

				// Write total incorrect in category
				writer.WriteStartElement("category_incorrect");
				writer.WriteString(String.Format("{0}", catIncorrect));
				writer.WriteEndElement();

				// Write percent incorrect in category
				writer.WriteStartElement("category_percent_incorrect");
				double catPercentIncorrect = 0;
				if (catTotal > 0)
				{ 				
					catPercentIncorrect = (double)catIncorrect / (double)catTotal;
				}
				catPercentIncorrect = Math.Round(catPercentIncorrect,6) * 100;
				writer.WriteString(String.Format("{0}", catPercentIncorrect));
				writer.WriteEndElement();

				
				writer.WriteStartElement("incorrect_result_types");
				
				foreach(string cat in incorrectResults.Keys)
				{
					writer.WriteStartElement("incorrect_result_type");
					
					writer.WriteStartElement("incorrect_result_type_name");
					writer.WriteString(String.Format("{0}", cat));
					writer.WriteEndElement();

					
					writer.WriteStartElement("incorrect_result_type_count");
					writer.WriteString(String.Format("{0}", incorrectResults[cat]));
					writer.WriteEndElement();

					// close incorrect_result_type
					writer.WriteEndElement();
				}
				
				// close incorrect result types
				writer.WriteEndElement();
				
				// close classifier
				writer.WriteEndElement();
			}

			// Close classifiers
			writer.WriteEndElement();
			
			writer.WriteStartElement("final_summary");
			
			// Write total
			writer.WriteStartElement("total");
			writer.WriteString(String.Format("{0}", total));
			writer.WriteEndElement();

			// Write total correct
			writer.WriteStartElement("total_correct");
			writer.WriteString(String.Format("{0}", totalCorrect));
			writer.WriteEndElement();

			// Write percent correct 
			writer.WriteStartElement("total_percent_correct");
			double percentCorrect = 0;
			if (total > 0 )
			{
				percentCorrect = (double)totalCorrect / (double)total;
			}
			percentCorrect = Math.Round(percentCorrect,6) * 100;
			writer.WriteString(String.Format("{0}", percentCorrect));
			writer.WriteEndElement();
			
			// Write total incorrect 
			writer.WriteStartElement("total_incorrect");
			writer.WriteString(String.Format("{0}", totalIncorrect));
			writer.WriteEndElement();

			// Write percent incorrect 
			writer.WriteStartElement("total_percent_incorrect");
			double percentIncorrect = 0;
			if (total > 0 )
			{
				percentIncorrect = (double)totalIncorrect / (double)total;
			}
			percentIncorrect = Math.Round(percentIncorrect,6) * 100;
			writer.WriteString(String.Format("{0}", percentIncorrect));
			writer.WriteEndElement();
			
			// Close final summary
			writer.WriteEndElement();
			
			
			// Close summary
			writer.WriteEndElement();
			
			// Close the xml doc
			writer.WriteEndDocument();
			writer.Flush();
			writer.Close();

		}
		
		/// <summary>
		/// Encapsulate the file string that contains the resulting information
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public string GetResultFilestring()
		{
			return ConfigurationManager.AppSettings["resultsFile"];
		}

		/// <summary>
		/// Encapsulate the file string that contains the summary information
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		static public string GetSummaryFilestring()
		{
			return ConfigurationManager.AppSettings["summaryFile"];
		}

		
		static public string GetAllowMinValueConfig()
		{
			return ConfigurationManager.AppSettings["allowMinValue"];
		}
		
		protected abstract DataTable GetTestArticles();

	}
}
