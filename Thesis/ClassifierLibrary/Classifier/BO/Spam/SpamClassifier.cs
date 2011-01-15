using System;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	public class SpamClassifier : NaiveClassifier
	{
		public SpamClassifier()
		{
		}
		
		 /// <summary>
		/// Query the reuter data set to find the count of each classification
		/// </summary>
		/// <param name="cat">
		/// A <see cref="ClassifierObject"/>
		/// </param>
        protected override void SetTrainingCount(ClassifierObject cat)
        {
			using (SqlConnection conn = new SqlConnection(SqlConn.GetConn()))
            {
				string sql = @"
					SELECT	count(1) as cnt
					FROM	Spam
					WHERE	ArticleId % 3 > 0
					AND		Category = @catName;
				";
				
				SqlCommand cmd = new SqlCommand(sql);
                SqlParameter param = new SqlParameter();
                param.ParameterName = "@catName";
                param.Value = cat.Name;
                cmd.Parameters.Add(param);

                cmd.Connection = conn;
                conn.Open();

                SqlDataReader reader = cmd.ExecuteReader();

                while (reader.Read())
                {
                    cat.TimesInTraining = (int)reader[0];
                }

                conn.Close();
            }
        }
		
		protected override DataTable GetTrainingArticles(ClassifierObject cat)
        {
            DataSet dSet = new DataSet();
            DataTable dTable = new DataTable();

            using (SqlConnection conn = new SqlConnection(SqlConn.GetConn()))
            {
                string sql = @"
					SELECT	Body
					FROM	Spam
					WHERE	ArticleId % 3 > 0
					AND		Category = @catName;
				";
				
				SqlCommand cmd = new SqlCommand(sql);
                SqlParameter param = new SqlParameter();
                param.ParameterName = "@catName";
                param.Value = cat.Name;
                cmd.Parameters.Add(param);

                cmd.Connection = conn;
                conn.Open();

                // Execute command and fill dataset
                SqlDataAdapter sAdapter = new SqlDataAdapter(cmd);
                sAdapter.Fill(dSet);

                // Populate Data Table
                dTable = dSet.Tables[0];
                conn.Close();
            }

            return dTable;
        }
	}
}
