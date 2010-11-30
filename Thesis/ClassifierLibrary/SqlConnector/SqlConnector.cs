// MyClass.cs created with MonoDevelop
// User: cmack at 7:26 PM 1/25/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data.SqlClient;
using System.Configuration;

namespace cfm.NaiveBayes
{
	public class SqlConn
	{
		// To connect to test database
		// Moved to MainRunner.config
		
		public static string connString = "";
		
		public SqlConn()
		{
		}

		/// <summary>
		/// Mono's Weak Configuration options make you manuall add the configuration file to the bin
		/// Hence MainRunner.exe.config must be the location of all the configurations.
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		public static string GetConn()
		{
			if (SqlConn.connString.Equals(""))
			{
				SqlConn.connString = ConfigurationManager.AppSettings["connectionString"]; 
			}
			return SqlConn.connString;
		}

		/// <summary>
		/// 
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		public static string GetResultsConn()
		{
			return ConfigurationManager.AppSettings["resultsConnectionString"]; 
		}
		
		public void TestQuery()
		{
			try
			{
				Console.WriteLine("Connecting to DB");
				SqlConnection conn = new SqlConnection(SqlConn.connString);
				
				string sql = "SELECT * FROM cmack_dogs";
				SqlCommand cmd = new SqlCommand(sql);
				
				cmd.Connection = conn;
				conn.Open();

				SqlDataReader reader = cmd.ExecuteReader();
				
				while(reader.Read())
				{
					//Console.WriteLine("Reading DB");
					Console.WriteLine(String.Format("{0}, {1}, {2}", reader[0], reader[1], reader[2]));
				}
				
				conn.Close();
				Console.WriteLine("Close DB");
				
			}
			catch (Exception e)
			{
				Console.WriteLine("Sql Fail:\n" + e.ToString());
			}

		}
	}
}
