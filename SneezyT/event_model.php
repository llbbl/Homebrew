<?php
class Event_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function get_types($term)
	{
		log_message('error', $term);
		
		$this->db->select('EventTypeId as id, EventName as value');
		$this->db->order_by('EventName', 'asc');
		$this->db->like('EventName', $term);
		$query = $this->db->get('EventType', 10);
		return $query->result();
	}

	function insert_event($event, $event_date)
	{
		$typeId = $this->get_event_type_id($event);
		
		log_message('error', $event_date->format("Y-m-d H:i:s"));
		
		$data = array(
				'EventTypeId' => $typeId ,
				'EventDate' => $event_date->format("Y-m-d H:i:s")
		);
		
		$this->db->insert('Event', $data);
		return $this->db->insert_id();
	}
	
	/**
	 * Looks up the event name based user input.  If a event is not found, it is added (probably not the best idea but cheap)
	 * 
	 * @param string $event
	 */
	function get_event_type_id($event)
	{
		$this->db->select('EventTypeId');
		$query = $this->db->from('EventType')->where('EventName', $event)->get();
		$row = $query->first_row();
		
		if (!isset($row->EventTypeId) || $row->EventTypeId == null)
		{
			$data = array(
					'EventName' => $event
			);
			
			$this->db->insert('EventType', $data);
			return $this->db->insert_id();
		}	
		
		return $row->EventTypeId;
	}
	
	// 
	/**
	 * Get all the events in a list
	 */
	function get_events($index, $pageSize, $sort)
	{
		$sql = "select EventId, EventDate, EventName from Event as e join EventType et on e.EventTypeId = et.EventTypeId order by " . $sort . " LIMIT " . $index . ', ' . $pageSize;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
?>