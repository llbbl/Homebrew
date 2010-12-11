using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace TestNLP
{
    class Program
    {
        static void Main(string[] args)
        {
            string str = "This is a test.";

            FindPOS finder = new FindPOS();
            finder.GetPOS(str);
            Console.WriteLine("Exit");
        }
    }
}
