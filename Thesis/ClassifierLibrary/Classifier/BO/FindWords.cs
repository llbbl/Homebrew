// MyClass.cs created with MonoDevelop
// User: cmack at 7:49 PMÂ 1/25/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;
using System.Data.SqlClient;
using System.Configuration;
using System.Collections.Generic;
using System.Collections;
using System.IO;
using NLPLibrary;

namespace cfm.NaiveBayes
{
    public abstract class FindWords
    {
        private static int minCount = -1;
        private static int maxCount = -1;
        private static int minWordSize = -1;
        private static string savedFile = "";
        private static bool removeSuffix = false;
        private static string nlpModelPath = "";
        private static string limitWordType = "";
        private Dictionary<string, WordObject> wordHash;
        private ArrayList blacklist;
        private bool useBlacklist;

        public FindWords()
        {
            FindWords.minCount = -1;
            FindWords.maxCount = -1;
            FindWords.minWordSize = -1;
            FindWords.savedFile = "";

            FindWords.removeSuffix = false;
            FindWords.removeSuffix = Convert.ToBoolean(ConfigurationManager.AppSettings["removeSuffix"]);

            this.wordHash = new Dictionary<string, WordObject>();
        }

        /// <summary>
        /// Main entry point
        /// </summary>
        public void Run()
        {
            Console.WriteLine("Setting Blacklist");
            this.SetBlacklist();
            Console.WriteLine("Getting Words");
            this.GetWords();
            Console.WriteLine("Saving Words");
            this.SaveCandidates();
        }

        /// <summary>
        /// Get the boundary number of instances of a word to be considered a classifier
        /// Lazy load the classifier
        /// </summary>
        /// <param name="str">Min or Max</param>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        public static int GetCount(string str)
        {
            if (str.Equals("min"))
            {
                if (FindWords.minCount.Equals(-1))
                {
                    FindWords.minCount = Convert.ToInt32(ConfigurationManager.AppSettings["minCount"]);
                }
                return FindWords.minCount;

            }
            else
            {
                if (FindWords.maxCount.Equals(-1))
                {
                    FindWords.maxCount = Convert.ToInt32(ConfigurationManager.AppSettings["maxCount"]);
                }
                return FindWords.maxCount;
            }
        }

        /// <summary>
        /// Get the path of the NLP model
        /// </summary>
        /// <returns></returns>
        public static string GetNLPModelPath()
        {
            if (FindWords.nlpModelPath != null)
            {
                try
                {
                    FindWords.nlpModelPath = ConfigurationManager.AppSettings["MaximumEntropyModelDirectory"];
                    return FindWords.nlpModelPath;
                }
                catch { }
            }
            FindWords.nlpModelPath = "";
            return FindWords.nlpModelPath;
        }

        /// <summary>
        /// Get the path of the NLP model
        /// </summary>
        /// <returns></returns>
        public static string GetLimitWordType()
        {
            if (FindWords.limitWordType != null)
            {
                try
                {
                    FindWords.limitWordType = ConfigurationManager.AppSettings["limitWordType"];
                    return FindWords.limitWordType;
                }
                catch { }
            }
            FindWords.limitWordType = "";
            return FindWords.limitWordType;
        }

        /// <summary>
        /// This is the file that the words will be saved to as a csv
        /// </summary>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        public static string GetFileName()
        {
            if (FindWords.savedFile.Equals(""))
            {
                FindWords.savedFile = ConfigurationManager.AppSettings["savedWordFile"];
            }
            return FindWords.savedFile;
        }

        /// <summary>
        /// This is the file that the words will be saved to as a csv
        /// </summary>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        public static int GetMinWordSize()
        {
            if (FindWords.minWordSize.Equals(-1))
            {
                FindWords.minWordSize = Convert.ToInt32(ConfigurationManager.AppSettings["minWordSize"]);
            }
            return FindWords.minWordSize;
        }

        /// <summary>
        /// Does checks and populates the blacklist
        /// </summary>
        public void SetBlacklist()
        {
            this.blacklist = new ArrayList();
            this.useBlacklist = FindWords.UseBlacklist();
            if (this.useBlacklist == true)
            {
                StreamReader reader = File.OpenText(FindWords.GetBlacklistFileString());
                string input = null;
                while ((input = reader.ReadLine()) != null)
                {
                    blacklist.Add(FindWords.CleanArticle(input));
                }
            }
        }

        /// <summary>
        /// Returns false if the word is not in the black list or if we are not using the blacklist
        /// </summary>
        /// <param name="word">
        /// A <see cref="System.String"/>
        /// </param>
        /// <returns>
        /// A <see cref="System.Boolean"/>
        /// </returns>
        public bool CheckBlacklist(string word)
        {
            bool check = false;
            if (this.useBlacklist == true && this.blacklist.Contains(word) == true)
            {
                check = true;
            }
            return check;
        }

        /// <summary>
        /// If you want to blacklist certain words from being considered, add them to this text file
        /// </summary>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        public static string GetBlacklistFileString()
        {
            return ConfigurationManager.AppSettings["blacklistFile"];
        }


        /// <summary>
        /// Flag that we are using the blacklist
        /// </summary>
        /// <returns>
        /// A <see cref="System.Boolean"/>
        /// </returns>
        public static bool UseBlacklist()
        {
            return Convert.ToBoolean(ConfigurationManager.AppSettings["useBlacklist"]);
        }

        /// <summary>
        /// Get the articles and separate the words out
        /// </summary>
        public void GetWords()
        {
            using (SqlConnection conn = new SqlConnection(SqlConn.GetConn()))
            {
                string sql = this.GetArticleQuery();
                SqlCommand cmd = new SqlCommand(sql);

                cmd.Connection = conn;
                conn.Open();
                cmd.CommandTimeout = 250;
                SqlDataReader reader = cmd.ExecuteReader();

                //Console.WriteLine("Find Words - reading");
                while (reader.Read())
                {
                    this.ExamineArticle(String.Format("{0}", reader[0]));
                }

                conn.Close();
            }
        }

        public abstract string GetArticleQuery();

        /// <summary>
        /// Examine each article, derive the words, and add to hash
        /// </summary>
        /// <param name="article">
        /// A <see cref="System.String"/>
        /// </param>
        public void ExamineArticle(string article)
        {
            //Console.WriteLine("Examining Article");

            article = FindWords.CleanArticle(article);
            WordObject[] words = this.SplitWords(article);
            foreach (WordObject word in words)
            {
                string trim = word.GetWord().Trim();
                if (
                        FindWords.GetLimitWordType() == "" ||
                        (FindWords.GetLimitWordType() == "noun" && word.PartOfSpeech == POSLib.NOUN)
                    )
                {
                    trim = this.HandleSuffix(trim);
                    if (this.CheckBlacklist(trim) == false)
                    {
                        if (this.wordHash.ContainsKey(trim).Equals(false))
                        {
                            this.wordHash.Add(trim, word);
                        }
                        this.wordHash[trim].IncrementTimesInFindWords();
                    }
                }
            }
        }

        /// <summary>
        /// Different datasets need to be broken apart in different ways
        /// </summary>
        /// <param name="article"></param>
        /// <returns></returns>
        protected abstract WordObject[] SplitWords(string article);

        /// <summary>
        /// Inspired by http://footheory.com/blogs/aendenne/archive/2007/09/14/useful-string-functions-in-c.aspx
        /// </summary>
        /// <param name="article">
        /// A <see cref="System.String"/>
        /// </param>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        static public string CleanArticle(string article)
        {
            string character = string.Empty;
            string cleanArticle = string.Empty;
            string cleanChars = "abcdefghijklmnopqrstuvwxyz|_ ";

            for (int i = 0; i < article.Length; i++)
            {
                character = article.Substring(i, 1);
                character = character.ToLower();
                if (cleanChars.Contains(character))
                {
                    cleanArticle += character;
                }
            }
            return cleanArticle;
        }

        /// <summary>
        /// If a word is in the range of required instances,  write it to a file
        /// </summary>
        public void SaveCandidates()
        {
            int minCount = FindWords.GetCount("min");
            int maxCount = FindWords.GetCount("max");
            int minSize = FindWords.GetMinWordSize();
            TextWriter tw = new StreamWriter(FindWords.GetFileName());
            foreach (string word in this.wordHash.Keys)
            {
                int count = this.wordHash[word].TimesInFindWords;
                if (count >= minCount && count <= maxCount && word.Length >= minSize)
                {
                    tw.WriteLine(String.Format("{0},{1}", word, count));
                }
            }

            tw.Close();
        }

        /// <summary>
        /// If required remove the suffixes of words
        /// </summary>
        /// <param name="word">
        /// A <see cref="System.String"/>
        /// </param>
        /// <returns>
        /// A <see cref="System.String"/>
        /// </returns>
        public string HandleSuffix(string word)
        {
            if (FindWords.removeSuffix == true)
            {
                string[] suffixes = new string[3];
                suffixes[0] = "ing";
                suffixes[1] = "ed";
                suffixes[2] = "ly";

                foreach (string suffix in suffixes)
                {
                    if (word.EndsWith(suffix))
                    {
                        //Console.WriteLine("Started With: {0}" , word);

                        int length = suffix.Length;
                        int end = word.Length - length;
                        if (end > 1)
                        {
                            word = word.Substring(0, end);
                        }

                        //Console.WriteLine("Removed {0} and ended with {1}" , suffix, word);
                    }
                }
            }

            return word;
        }
    }
}
