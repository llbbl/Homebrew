// ResultClassifierObject.cs created with MonoDevelop
// User: cmack at 11:16 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Collections;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	
	
	public class ResultClassifierObject : ResultsObject
	{
		
		public Dictionary<string,int> incorrectClassifications;
		public int timesInTraining;
		
		public ResultClassifierObject()
		{
			this.incorrectClassifications = new Dictionary<string,int>(); 
		}
		
		public void AddIncorrect(string name, int count)
		{
			if (!this.incorrectClassifications.ContainsKey(name))
			{
				this.incorrectClassifications.Add(name, count);
			}
		}
		
		public void SetTimesInTraining(int times)
		{
			this.timesInTraining = times;
		}

		public override void Save()
		{
			using (SqlConnection conn = new SqlConnection(SqlConn.GetResultsConn() ) )
			{
				conn.Open();
				
				// Save Run
				string sql = @"INSERT INTO CategorySummary 
								( RunId, TimesInTraining, CategoryName, CategoryTotal, CategoryCorrect, CategoryPercentCorrect, CategoryIncorrect, CategoryPercentIncorrect ) 
								VALUES
								( @RunId, @TimesInTraining, @Name, @Total, @Correct, @PercentCorrect, @Incorrect, @PercentIncorrect ); ";

				SqlCommand cmd = new SqlCommand(sql);				
				cmd.Connection = conn;
				
				SqlParameter param = new SqlParameter();
				param.ParameterName = "@RunId";
				param.Value = this.runId;
				cmd.Parameters.Add(param);

				param = new SqlParameter();
				param.ParameterName = "@TimesInTraining";
				param.Value = this.timesInTraining;
				cmd.Parameters.Add(param);

				param = new SqlParameter();
				param.ParameterName = "@Name";
				param.Value = this.name;
				cmd.Parameters.Add(param);
				
				param = new SqlParameter();
				param.ParameterName = "@Total";
				param.Value = this.total;
				cmd.Parameters.Add(param);

				param = new SqlParameter();
				param.ParameterName = "@Correct";
				param.Value = this.correct;
				cmd.Parameters.Add(param);
				
				param = new SqlParameter();
				param.ParameterName = "@PercentCorrect";
				param.Value = this.percentCorrect;
				cmd.Parameters.Add(param);

				param = new SqlParameter();
				param.ParameterName = "@Incorrect";
				param.Value = this.incorrect;
				cmd.Parameters.Add(param);
				
				param = new SqlParameter();
				param.ParameterName = "@PercentIncorrect";
				param.Value = this.percentIncorrect;
				cmd.Parameters.Add(param);
				
				cmd.ExecuteNonQuery();
				
				//Retrieve new category summary id
				cmd.Parameters.Clear();
				sql = "SELECT @@IDENTITY";
				cmd.CommandText = sql;
				
				SqlDataReader reader = cmd.ExecuteReader();
				
				int categorySummaryId = 0;
				while(reader.Read())
				{
					string temp = String.Format("{0}", reader[0]);
					categorySummaryId = Convert.ToInt32(temp);
				}
				reader.Close();
				
				foreach(string key in this.incorrectClassifications.Keys)
				{
					cmd.Parameters.Clear();
					sql = @"INSERT INTO CategoryIncorrect 
								( RunId, CategorySummaryId, KnownCategory, ResultCategory, ResultIncorrect ) 
								VALUES
								( @RunId, @CategorySummaryId, @KnownCategory, @ResultCategory, @ResultIncorrect ); ";

					cmd.CommandText = sql;
					
					param = new SqlParameter();
					param.ParameterName = "@RunId";
					param.Value = this.runId;
					cmd.Parameters.Add(param);

					param = new SqlParameter();
					param.ParameterName = "@CategorySummaryId";
					param.Value = categorySummaryId;
					cmd.Parameters.Add(param);
					
					param = new SqlParameter();
					param.ParameterName = "@KnownCategory";
					param.Value = this.name;
					cmd.Parameters.Add(param);
					
					param = new SqlParameter();
					param.ParameterName = "@ResultCategory";
					param.Value = key;
					cmd.Parameters.Add(param);

					param = new SqlParameter();
					param.ParameterName = "@ResultIncorrect";
					param.Value = this.incorrectClassifications[key];
					cmd.Parameters.Add(param);
					
					cmd.ExecuteNonQuery();
				}
				conn.Close();
			}
		}
	}
}
