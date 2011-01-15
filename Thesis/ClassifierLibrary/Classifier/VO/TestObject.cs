// TestObject.cs created with MonoDevelop
// User: cmack at 5:56 PM 5/2/2009
//
// To change standard headers go to Edit->Preferences->Coding->Standard Headers
//

using System;

namespace cfm.NaiveBayes
{	
	
	public class TestObject
	{
		private int id;
		private string knownType;
		private string resultType;
		private double probability;
		private int correct; 
		
		public TestObject( int id, string knownType, string resultType, double probability )
		{
			this.id = id;
			this.knownType = knownType;
			this.resultType = resultType;
			this.probability = probability;
			
			if (this.knownType == this.resultType)
			{
				this.correct = 1;
			}
			else
			{
				this.correct = 0;
			}
		}
		
		public int Id
		{
			get
			{
				return this.id;
			}
		}
		
		public string KnownType
		{
			get
			{
				return this.knownType;
			}
		}
		
		public string ResultType
		{
			get
			{
				return this.resultType;
			}
		}
		
		public double Prob
		{
			get
			{
				return this.probability;
			}
		}

		public int Correct
		{
			get
			{
				return this.correct;
			}
		}
		
		/// <summary>
		/// Return the ToString as a CSV format
		/// </summary>
		/// <returns>
		/// A <see cref="System.String"/>
		/// </returns>
		public override string ToString ()
		{
			string str = "";
			str += this.Id + ",";
			str += this.KnownType + ",";
			str += this.ResultType + ",";
			str += this.correct + ",";
			str += this.Prob;
			return str;
		}

	}
}
