// Mono's Weak Configuration options make you manuall add the configuration file to the bin
// Hence MainRunner.exe.config must be the location of all the configurations

using System;
using System.Configuration;

namespace cfm.NaiveBayes
{
	class MainClass
	{
		public static void Main(string[] args)
		{
			Console.WriteLine("Started.");			
			try
			{
				ResultRecorder recorder = new ResultRecorder();
				recorder.AddTime("MasterStart", DateTime.Now);
				
				string toRun = ConfigurationManager.AppSettings["dataSet"].ToString();
				
				recorder.AddTime("FindWordsStart", DateTime.Now);				
				if (Convert.ToBoolean(ConfigurationManager.AppSettings["findWords"]) == true)
				{
					MainClass.FindKnobs(toRun);
				}
				recorder.AddTime("FindWordsEnd", DateTime.Now);
				
				recorder.AddTime("TrainStart", DateTime.Now);				
				if (Convert.ToBoolean(ConfigurationManager.AppSettings["trainSet"]) == true)
				{				
					MainClass.RunNaiveBayes(toRun);
				}
				recorder.AddTime("TrainEnd", DateTime.Now);
				
				recorder.AddTime("TestStart", DateTime.Now);
				if (Convert.ToBoolean(ConfigurationManager.AppSettings["testSet"]) == true)
				{
					MainClass.TestNaiveBayes(toRun);
				}
				recorder.AddTime("TestEnd", DateTime.Now);
				
				recorder.AddTime("MasterEnd", DateTime.Now);
				if (Convert.ToBoolean(ConfigurationManager.AppSettings["saveToDb"]) == true)
				{
					recorder.Save();
				}
			}
			catch (Exception e)
			{
				Console.WriteLine("Sql Fail:\n" + e.ToString());
			}
			Console.WriteLine("Completed.");
		}

		public static void TestConnection()
		{
			SqlConn conn = new SqlConn();
			conn.TestQuery();
		}
		
		public static void FindKnobs(string toRun)
		{
			FindWords words = new ReutersFindWords();;
			if (toRun == "Spam")
			{
				words = new SpamFindWords();;
			}
			
			words.Run();
		}
		
		public static void RunNaiveBayes(string toRun)
		{
			NaiveClassifier train = new ReutersClassifier();
			if (toRun == "Spam")
			{
				train = new SpamClassifier();
			}
			
			train.Train();
		}
		
		public static void TestNaiveBayes(string toRun)
		{
			NaiveTester test = new ReutersTester();
			if (toRun == "Spam")
			{
				test = new SpamTester();
			}
			
			test.Test();
		}
	}
}
