<?php
class Sneezy_model extends CI_Model {

	protected $table;
	
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->define();
	}

	/**
	 * @todo find a way to abstract this
	 * 
	 * Not sure if I like this approach.  Ideally, this would be abstract but I can't get codeignitor to go along with that idea.  
	 * 
	 */
	function define() {}
	
	
	/**
	 * Model for the combo box for this model
	 * @param string $term - search term
	 */
	public function get_types($term)
	{
		$this->db->select( $this->table  . 'TypeId as id, ' . $this->table . 'Name as value');
		$this->db->order_by( $this->table . 'Name', 'asc');
		$this->db->like($this->table . 'Name', $term);
		$this->db->where('IsDeleted', 0);
		$query = $this->db->get($this->table . 'Type', 10);
		return $query->result();
	}

	/**
	 * Insert into this table
	 * 
	 * @param string $selection - selected term
	 * @param DateTime $date
	 * @param string $note - optional note
	 */
	public function insert($selection, $date, $note)
	{
		$type_id = $this->get_type_id($selection);
		if (!$type_id)
		{
			log_message('error', "Error generating new: $selection");
			return false;
		}
		
		
		$data = array(
				$this->table  . 'TypeId' => $type_id ,
				$this->table  . 'Date' => $date->format("Y-m-d H:i:s"),
				$this->table  . 'Note' => $note
		);
		
		$this->db->insert($this->table , $data);
		return $this->db->insert_id();
	}
	
	/**
	 * Looks up the name based user input.  If an entry is not found, it is added (probably not the best idea but cheap)
	 * 
	 * @param string $term
	 * @param boolean $insert - only insert if this is true
	 * @return int|false
	 */
	public function get_type_id($term, $insert=true)
	{
		$this->db->select($this->table . 'TypeId, IsDeleted');
		$query = $this->db->from($this->table . 'Type')->where($this->table . 'Name', $term)->get();
		$row = $query->first_row();
		
		$column_name = $this->table . 'TypeId';
		
		$is_deleted = false;
		
		if (isset($row->IsDeleted))
		{
			$is_deleted = $row->IsDeleted;
		}
		
		if ($is_deleted == true)
		{
			log_message('error', $this->table . ' already soft deleted this term: ' . $term);
			return false;
		}
		
		if (!isset($row->$column_name) || $row->$column_name == null)
		{
			if ($insert)
			{
				$data = array(
						$this->table . 'Name' => $term
				);
				
				if($this->db->insert($this->table . 'Type', $data))
				{
					return $this->db->insert_id();
				}
			}
			
			return false;
		}	

		return $row->$column_name;
	}
	
	/**
	 * Persist this change of this type table to be from and to
	 * 
	 * @return int affected_rows() 
	 */
	public function log_merge_request($from_id, $to_id)
	{
	
		$date = new DateTime();
		$today = $date->format("Y-m-d H:i:s");
		
		$table = $this->table;
		$table_id = $table . 'Id';
		$table_type_id = $table . 'TypeId';
		
		$update = <<<SQL
INSERT INTO TypeMergeHistory (MergeTable, TableId, ToTypeId, FromTypeId, MergeDate) 
 SELECT '$table', $table_id, $to_id, $from_id, '$today'
 FROM `$table`
 WHERE $table_type_id = $from_id;
SQL;
		
		$this->db->query($update);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Update all the entries in this->table with a new type
	 * 
	 * @return int affected_rows()
	 */
	public function merge($from_id, $to_id)
	{
		$table_type_id = $this->table . 'TypeId';
		$data = array(
				$table_type_id => $to_id
		);
	
		$this->db->where($table_type_id, intval($from_id));
		$this->db->update($this->table, $data);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Get all inventory in a list
	 * @param unknown_type $index
	 * @param unknown_type $page_size
	 * @param unknown_type $sort
	 */
	public function inventory($index, $page_size, $sort_str)
	{
		$sort = explode(' ', $sort_str);
		
		$this->db->select( $this->table  . 'Id, ' . $this->table . 'Date, ' . $this->table . 'Name, ' . $this->table . 'Note')
					->from($this->table . ' i')
					->join($this->table . 'Type t', 'i.'.$this->table.'TypeId = t.'. $this->table .'TypeId')
					->where('i.IsDeleted', 0)
					->order_by(trim($sort[0]), trim($sort[1]))
					->limit($page_size, $index);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Soft delete an entry from the list
	 */
	public function delete($id)
	{
		$data = array(
        	'IsDeleted' => 1
	    );

        $this->db->where($this->table . 'Id', intval($id));
	    $this->db->update($this->table, $data); 
	}

	/**
	 * Soft delete an type entry
	 */
	public function delete_type($id)
	{
		$data = array(
				'IsDeleted' => 1
		);
		
		$this->db->where($this->table . 'TypeId', intval($id));
		$this->db->update($this->table . 'Type', $data);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Update just the note and date
	 */
	public function update($id, $note, $date)
	{
		$data = array(
           		$this->table . 'Note' => $note,
				$this->table  . 'Date' => $date->format("Y-m-d H:i:s"),
	        );

        $this->db->where($this->table . 'Id', intval($id));
	    $this->db->update($this->table, $data); 
	}
}
?>