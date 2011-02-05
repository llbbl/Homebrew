package mongoJava;

import java.net.UnknownHostException;
import java.util.*;

import com.mongodb.Mongo;
import com.mongodb.DB;
import com.mongodb.DBCollection;
import com.mongodb.BasicDBObject;
import com.mongodb.DBObject;
import com.mongodb.DBCursor;
import com.mongodb.MongoException;

/**
 * Silly DAO class around connecting to a Mongo Database
 * @author cmack
 *
 */
public class MongoConnector 
{
	private DB db;
	
	public MongoConnector()
	{
		
	}
	
	/**
	 * This should probably be made a singleton
	 * 
	 * @param name
	 * @throws UnknownHostException
	 * @throws MongoException
	 */
	public void Connect(String name) throws UnknownHostException, MongoException
	{
		this.db = null;
			
		try {
			Mongo m = new Mongo();
			this.db = m.getDB( name );
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (MongoException e) {
			e.printStackTrace();
		}
	}
	
	/**
	 * Get a list of collections in the DB
	 */
	public void PrintCollectionName()
	{
		Set<String> colls = this.db.getCollectionNames();

		for (String s : colls) 
		{
		    System.out.println(s);
		}
	}
	
	/**
	 * Wrapper for inserting only if it did not exist
	 * @param colName
	 * @param parameters
	 */
	public void Insert(String colName, Hashtable<String, Object> parameters)
	{
		this.Insert(colName, parameters, true);
	}
	
	/**
	 * Insert into a given collection
	 * 
	 * @param colName
	 * @param parameters
	 * @param ifNotExist
	 */
	public void Insert(String colName, Hashtable<String, Object> parameters, boolean ifNotExist)
	{
		if (ifNotExist == false || (ifNotExist == true && this.Exists(colName, parameters) == false))
		{
			DBCollection col = this.db.getCollection(colName);
			BasicDBObject doc = this.BuildDbObject(parameters);
			col.insert(doc);
		}
	}
	
	
	/**
	 * Check for existence.  There is probably a much more graceful way of doing this but I'm not sure how to encapsulate that request with a Hashtable
	 * 
	 * @param colName
	 * @param parameters
	 * @return
	 */
	public boolean Exists(String colName, Hashtable<String, Object> parameters)
	{
		DBCollection col = this.db.getCollection(colName);
		BasicDBObject query = this.BuildDbObject(parameters);
		
		DBCursor cur = col.find(query); // probably a poor way of doing this
		
		while(cur.hasNext()) {
            return true;
        }
		
		return false;
	}
	
	/**
	 * Select the first result from a given set of parameters
	 * 
	 * @param colName
	 * @param parameters
	 * @return
	 */
	public Hashtable<String, Object> SelectOne(String colName, Hashtable<String, Object> parameters)
	{
		Hashtable<String, Object> results = new Hashtable<String, Object>();
		
		DBCollection col = this.db.getCollection(colName);
		BasicDBObject query = this.BuildDbObject(parameters);
		
		DBObject myDoc = col.findOne(query);
		if (myDoc != null)
		{
			Set<String> keySet = myDoc.keySet();
			for(String key : keySet)
			{
				Object value = myDoc.get(key);
				results.put(key, value);
			}
		}
		
		return results;
	}
	
	/**
	 * Private method of building the BasicDBOBject from a hash table
	 * 
	 * @param parameters
	 * @return
	 */
	private BasicDBObject BuildDbObject(Hashtable<String, Object> parameters)
	{
		BasicDBObject doc = new BasicDBObject();
		
		Enumeration<String> keys = parameters.keys();
		while( keys.hasMoreElements() ) {
		  String key = keys.nextElement();
		  Object value = parameters.get(key);
		  
		  doc.put(key, value);
		}
		
		return doc;
	}
}
