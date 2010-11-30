// ResultsObject.cs created with MonoDevelop
// User: cmack at 10:38 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	
	public class ResultsObject
	{
		// TODO: Make these properties but I've been coding for 10 hours straight
		public int runId;
		public string name;
		public int total;
		public int correct;
		public int incorrect;
		public double percentCorrect;
		public double percentIncorrect;
		
		public ResultsObject()
		{
		}
		
		public void Set(int runId, string name, int total, int correct, int incorrect, double percentCorrect, double percentIncorrect)
		{
			this.runId = runId;
			this.name = name;
			this.total = total;
			this.correct = correct;
			this.percentCorrect = percentCorrect;
			this.incorrect = incorrect;
			this.percentIncorrect = percentIncorrect;
		}


		
		public virtual void Save()
		{
			using (SqlConnection conn = new SqlConnection(SqlConn.GetResultsConn() ) )
			{
				conn.Open();
				
				// Save Run
				string sql = @"INSERT INTO Summary 
								( RunId, Total, TotalCorrect, PercentCorrect, TotalIncorrect, PercentIncorrect ) VALUES
								( @RunId, @Total, @Correct, @PercentCorrect, @Incorrect, @PercentIncorrect ); ";

				SqlCommand cmd = new SqlCommand(sql);				
				cmd.Connection = conn;
				
				SqlParameter param = new SqlParameter();
				param.ParameterName = "@RunId";
				param.Value = this.runId;
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

				conn.Close();
			}
		}
		
		public override string ToString()
		{
			string str = "Total: " + this.total + "\n";
			str += "Correct: " + this.correct + "\n";
			str += "Incorrect: " + this.incorrect + "\n";
			str += "Percent Correct: " + this.percentCorrect + "\n";
			str += "Percent Incorrect: " + this.percentIncorrect + "\n";
			return str;
		}	
	}
}
