// ReutersClassifier.cs created with MonoDevelop
// User: cmack at 10:25 PM 4/14/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	public class ReutersClassifier : NaiveClassifier
	{
		public ReutersClassifier()
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
			using (SqlConnection conn = new SqlConnection(SqlConn.GetConn() ) )
			{
				string sql = @"
				SELECT	COUNT(1) AS cnt
				FROM	Article AS a
				JOIN	ArticleCategory AS ac
				ON		a.ArticleId = ac.ArticleId
				JOIN	Category AS c
				ON		c.CategoryId = ac.CategoryId
				JOIN	CategoryType AS ct
				ON		c.CategoryTypeId = ct.CategoryTypeId
				WHERE	LewisSplit = 'TRAIN'
				AND		ct.Name = @catName
                AND     a.Body IS NOT NULL
                AND     a.Body != ''
                AND     a.Body NOT LIKE '%Shr%vs%'
                AND     a.Body NOT LIKE 'Qtly%vs%'
				";
				
				SqlCommand cmd = new SqlCommand(sql);
				SqlParameter param = new SqlParameter();
				param.ParameterName = "@catName";
				param.Value = cat.Name;
				cmd.Parameters.Add(param);
				
				cmd.Connection = conn;
				conn.Open();

				SqlDataReader reader = cmd.ExecuteReader();
				
				while(reader.Read())
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
			
			using (SqlConnection conn = new SqlConnection(SqlConn.GetConn() ) )
			{
				string sql = @"
				SELECT	Body
				FROM	Article AS a
				JOIN	ArticleCategory AS ac
				ON		a.ArticleId = ac.ArticleId
				JOIN	Category AS c
				ON		c.CategoryId = ac.CategoryId
				JOIN	CategoryType AS ct
				ON		c.CategoryTypeId = ct.CategoryTypeId
				WHERE	LewisSplit = 'TRAIN'
				AND		ct.Name = @catName
                AND     a.Body IS NOT NULL
                AND     a.Body != ''
                AND     a.Body NOT LIKE '%Shr%vs%'
                AND     a.Body NOT LIKE 'Qtly%vs%'
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
