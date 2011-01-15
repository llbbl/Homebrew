// ReutersTester.cs created with MonoDevelop
// User: cmack at 8:52 PM 5/1/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data;
using System.Data.SqlClient;

namespace cfm.NaiveBayes
{
	
	
	public class ReutersTester : NaiveTester
	{
		public ReutersTester()
		{
		}
		
		/// <summary>
		/// This must know how to clean and parse an article
		/// </summary>
		protected override DataTable GetTestArticles()
		{
			DataSet dSet = new DataSet();
			DataTable dTable = new DataTable();

			using (SqlConnection conn = new SqlConnection(SqlConn.GetConn() ) )
			{
				string sql = @"
				SELECT	a.Body, ct.Name as CategoryName, a.ArticleId
				FROM	Article AS a
				JOIN	ArticleCategory AS ac
				ON		a.ArticleId = ac.ArticleId
				JOIN	Category AS c
				ON		c.CategoryId = ac.CategoryId
				JOIN	CategoryType AS ct
				ON		c.CategoryTypeId = ct.CategoryTypeId
				WHERE	LewisSplit = 'TEST'
                AND     a.Body IS NOT NULL
                AND     a.Body != ''
                AND     a.Body NOT LIKE '%Shr%vs%'
                AND     a.Body NOT LIKE 'Qtly%vs%'
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
