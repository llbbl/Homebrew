package mongoJava;

import java.util.*;

public class MongoRunner 
{

	/**
	 * @param args
	 */
	public static void main(String[] args) 
	{
		try
		{
			MongoConnector m = new MongoConnector();
			m.Connect("test");
			m.PrintCollectionName();
			
			Hashtable<String, Object> hash = new Hashtable<String, Object>();
			hash.put("Name", "Marley");
			hash.put("Name", "Maple");
			m.Insert("MackCollection", hash);
			
			Hashtable<String, Object> select = new Hashtable<String, Object>();
			select.put("Name", "Marley");
			
			Hashtable<String, Object> results = m.SelectOne("MackCollection", select);
			
			Enumeration<String> keys = results.keys();
			while( keys.hasMoreElements() ) {
			  String key = keys.nextElement();
			  String value = results.get(key).toString(); // this may fail but don't want to mess with it now  
			  
			  System.out.println("key: " + key + " value: " + value);
			}
			
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
	}
}
