// ResultRecorder.cs created with MonoDevelop
// User: cmack at 9:34 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Collections;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Xml;

namespace cfm.NaiveBayes
{
	public class ResultRecorder
	{
		
		private Dictionary<string, DateTime> times;
		private int RunId;
		
		public ResultRecorder()
		{
			this.times = new Dictionary<string,DateTime>();
		}

		public void AddTime(string id, DateTime time)
		{
			if (!this.times.ContainsKey(id))
			{
				this.times.Add(id, time);
			}
		}
		
		public void Save()
		{
			this.RunId = this.SaveRun();
			this.SaveConfiguration();
            
            // Summary files are only created if testSet has been run once
            if (Convert.ToBoolean(ConfigurationManager.AppSettings["testSet"]) == true)
            {
                this.SaveSummary();
                this.SaveCategorySummaries();
            }
		}
		
		private int SaveRun()
		{
			int runId = 0;
			using (SqlConnection conn = new SqlConnection(SqlConn.GetResultsConn() ) )
			{
				// Save Run
				string sql = "INSERT INTO Run ( ";
				int count = this.times.Count;
				int i = 1;
				foreach(string key in this.times.Keys)
				{
					sql += key;
					if (i != count)
					{
						sql += ",";
					}
					i++;
				}
				sql += ") VALUES (";
				i = 1;
				foreach(string key in this.times.Keys)
				{
					sql += "@" + key;
					if (i != count)
					{
						sql += ",";
					}
					i++;
				}				
				sql += "); ";
				
				SqlCommand cmd = new SqlCommand(sql);
				foreach(string key in this.times.Keys)
				{
					SqlParameter param = new SqlParameter();
					param.ParameterName = "@" + key;
					param.Value = this.times[key];
					cmd.Parameters.Add(param);
				}
				
				cmd.Connection = conn;
				conn.Open();

				cmd.ExecuteNonQuery();

				//Retrieve new run id
				cmd.Parameters.Clear();
				sql = "SELECT @@IDENTITY";
				cmd.CommandText = sql;
				
				SqlDataReader reader = cmd.ExecuteReader();
				
				while(reader.Read())
				{
					string temp = String.Format("{0}", reader[0]);
					runId = Convert.ToInt32(temp);
				}
								
				conn.Close();
			}
			
			return runId;
		}
		
		private void SaveConfiguration()
		{
			using (SqlConnection conn = new SqlConnection(SqlConn.GetResultsConn() ) )
			{
				conn.Open();
				
				string sql = "INSERT INTO Configuration ( RunId, Name, Value ) VALUES ( @RunId, @Name, @Value )";
				SqlCommand cmd = new SqlCommand(sql);				
				cmd.Connection = conn;
				
				SqlParameter runParam = new SqlParameter();
				runParam.ParameterName = "@RunId";
				runParam.Value = this.RunId;
				cmd.Parameters.Add(runParam);

				SqlParameter nameParam = new SqlParameter();
				nameParam.ParameterName = "@Name";
				nameParam.Value = "minCount";
				cmd.Parameters.Add(nameParam);

				SqlParameter valueParam = new SqlParameter();
				valueParam.ParameterName = "@Value";
				valueParam.Value = FindWords.GetCount("min");
				cmd.Parameters.Add(valueParam);

				// Add Min Count
				cmd.ExecuteNonQuery();

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "maxCount";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = FindWords.GetCount("max");
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Add Max Count

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "minWordSize";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = FindWords.GetMinWordSize();
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Add Min Word Size

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "removePrefix";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = ConfigurationManager.AppSettings["removeSuffix"];
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Add Min Word Size

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "runFindWordsPrefix";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = ConfigurationManager.AppSettings["findWords"];
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Did we find words?

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "runTrainSet";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = ConfigurationManager.AppSettings["trainSet"];
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Did we run create the training set?

				cmd.Parameters.Clear();
				cmd.Parameters.Add(runParam);
				nameParam.Value = "runTestSet";
				cmd.Parameters.Add(nameParam);
				valueParam.Value = ConfigurationManager.AppSettings["testSet"];
				cmd.Parameters.Add(valueParam);
				cmd.ExecuteNonQuery(); // Did we run create the test set?

                cmd.Parameters.Clear();
                cmd.Parameters.Add(runParam);
                nameParam.Value = "allowMinValue";
                cmd.Parameters.Add(nameParam);
                valueParam.Value = ConfigurationManager.AppSettings["allowMinvalue"];
                cmd.Parameters.Add(valueParam);
                cmd.ExecuteNonQuery(); // Did we run do we allow the minimum value?

                try
                {
                    cmd.Parameters.Clear();
                    cmd.Parameters.Add(runParam);
                    nameParam.Value = "operatingSystem";
                    cmd.Parameters.Add(nameParam);
                    valueParam.Value = "Linux_Mono";
                    cmd.Parameters.Add(valueParam);
                    cmd.ExecuteNonQuery(); // What system do we run this under?

                    cmd.Parameters.Clear();
                    cmd.Parameters.Add(runParam);
                    nameParam.Value = "dataSet";
                    cmd.Parameters.Add(nameParam);
                    valueParam.Value = ConfigurationManager.AppSettings["dataSet"];
                    cmd.Parameters.Add(valueParam);
                    cmd.ExecuteNonQuery(); // What system do we run this under?

                    cmd.Parameters.Clear();
                    cmd.Parameters.Add(runParam);
                    nameParam.Value = "usePrior";
                    cmd.Parameters.Add(nameParam);
                    valueParam.Value = ConfigurationManager.AppSettings["usePrior"];
                    cmd.Parameters.Add(valueParam);
                    cmd.ExecuteNonQuery(); // Are we using the Dirichlet prior?
					
                    cmd.Parameters.Clear();
                    cmd.Parameters.Add(runParam);
                    nameParam.Value = "useLaplace";
                    cmd.Parameters.Add(nameParam);
                    valueParam.Value = ConfigurationManager.AppSettings["useLaplace"];
                    cmd.Parameters.Add(valueParam);
                    cmd.ExecuteNonQuery(); // Are we using the Laplace smoothing?
					
					cmd.Parameters.Clear();
                    cmd.Parameters.Add(runParam);
                    nameParam.Value = "useMestimate";
                    cmd.Parameters.Add(nameParam);
                    valueParam.Value = ConfigurationManager.AppSettings["useMestimate"];
                    cmd.Parameters.Add(valueParam);
                    cmd.ExecuteNonQuery(); // Are we using the Laplace smoothing?
				}
                catch (Exception e)
                {
                    Console.WriteLine(e.ToString());
                }

				conn.Close();
			}
		}
		
		private void SaveSummary()
		{
			try
			{ 
				XmlTextReader reader = new XmlTextReader(NaiveTester.GetSummaryFilestring());
				string testString = "";
				ResultsObject resultObj = new ResultsObject();
				int total = 0;
				int totalCorrect = 0;
				int totalIncorrect = 0;
				double totalPercentCorrect = 0d;
				double totalPercentIncorrect = 0d;
				
				while (reader.Read()) 
				{
					if (reader.NodeType == XmlNodeType.Element)
					{
						testString=reader.Name;
					}
					else if (reader.NodeType == XmlNodeType.EndElement)
					{
						if (reader.Name == "final_summary")
						{
							// Save Final Summary
							resultObj.Set(this.RunId, "total", total, totalCorrect, totalIncorrect, totalPercentCorrect, totalPercentIncorrect);
							resultObj.Save();
						}
						else if (reader.Name == "classifier")
						{
							// Save classifier
						}
					}
					else if (reader.NodeType == XmlNodeType.Text)
					{
						if (testString == "total")
			        	{
							total = Convert.ToInt32(reader.Value);
				        }
						else if (testString == "total_correct")
						{
							totalCorrect = Convert.ToInt32(reader.Value);
						}
						else if (testString == "total_incorrect")
						{
							totalIncorrect = Convert.ToInt32(reader.Value);
						}
						else if (testString == "total_percent_correct")
						{
							totalPercentCorrect = Convert.ToDouble(reader.Value);
						}
						else if (testString == "total_percent_incorrect")
						{
							totalPercentIncorrect = Convert.ToDouble(reader.Value);
						}
					}
				}
			}
			catch(Exception ex)
			{
				Console.WriteLine(ex.ToString());
			}
		}
		
		private void SaveCategorySummaries()
		{
			try
			{ 
				XmlTextReader reader = new XmlTextReader(NaiveTester.GetSummaryFilestring());
				string testString = "";
				ResultClassifierObject resultObj = new ResultClassifierObject();

				string name = "";
				int timesInTraining = 0;
				int total = 0;
				int correct = 0;
				double percentCorrect = 0d;
				int incorrect = 0;
				double percentIncorrect = 0d;
				string incorrectName = "";
				int incorrectCount = 0;
				
				while (reader.Read()) 
				{
					if (reader.NodeType == XmlNodeType.Element)
					{
						testString=reader.Name;
						if (reader.Name == "classifier")
						{
							//reset flags
							name = "";
							timesInTraining = 0;
							total = 0;
							correct = 0;
							incorrect = 0;
							percentCorrect = 0d;
							percentIncorrect = 0d;
							incorrectName = "";
							incorrectCount = 0;
							
							resultObj = new ResultClassifierObject();
						}
					}
					else if (reader.NodeType == XmlNodeType.EndElement)
					{
						if (reader.Name == "classifier")
						{
							// Save Classifier
							//ResultsObject resultObj = new ResultsObject();
							resultObj.Set(this.RunId, name, total, correct, incorrect, percentCorrect, percentIncorrect);
							resultObj.SetTimesInTraining(timesInTraining);
							resultObj.Save();
						}
						else if (reader.Name == "incorrect_result_type")
						{
							// Build and reset incorrect_result_type
							resultObj.AddIncorrect(incorrectName, incorrectCount);
							incorrectName = "";
							incorrectCount = 0;
						}
					}
					else if (reader.NodeType == XmlNodeType.Text)
					{
						if (testString == "name")
			        	{
							name = reader.Value;
				        }
						else if (testString == "training_cat_count")
						{
							timesInTraining = Convert.ToInt32(reader.Value);
						}
						else if (testString == "category_total")
						{
							total = Convert.ToInt32(reader.Value);
						}
						else if (testString == "category_correct")
						{
							correct = Convert.ToInt32(reader.Value);
						}
						else if (testString == "category_percent_correct")
						{
							percentCorrect = Convert.ToDouble(reader.Value);
						}
						else if (testString == "category_incorrect")
						{
							incorrect = Convert.ToInt32(reader.Value);
						}
						else if (testString == "category_percent_incorrect")
						{
							percentIncorrect = Convert.ToDouble(reader.Value);
						}
						else if (testString == "incorrect_result_type_name")
						{
							incorrectName = reader.Value;
						}
						else if (testString == "incorrect_result_type_count")
						{
							incorrectCount = Convert.ToInt32(reader.Value);
						}
					}
				}
			}
			catch(Exception ex)
			{
				Console.WriteLine(ex.ToString());
			}
		}
	}
}
