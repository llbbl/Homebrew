using System;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	public class SpamTester : NaiveTester
	{
		public SpamTester()
		{
		}
		
		protected override DataTable GetTestArticles()
        {
            DataSet dSet = new DataSet();
            DataTable dTable = new DataTable();
            using (SqlConnection conn = new SqlConnection(SqlConn.GetConn()))
            {
                string sql = @"
					SELECT	Body, Category as CategoryName, ArticleId
					FROM	Spam
					WHERE	ArticleId % 3 = 0
				";
				SqlCommand cmd = new SqlCommand(sql);
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
