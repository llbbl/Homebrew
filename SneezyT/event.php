<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
	public function index()
	{
		$this->load->model('Event_model');
		$this->load->helper('url');
		$this->load->view('add_event');
	}
	
	public function insert()
	{
		$data = array();
	
		$event = $_POST['event'];
		$event_date = new DateTime($_POST['event_date']);
	
		$this->load->model('Event_model');
		$data['result'] = $this->Event_model->insert_event($event, $event_date);
	
		$this->load->helper('url');
		$this->load->view('insert_event', $data);
	}
	
	public function get_types()
	{
		$data = array();
	
		$term = '';
	
		if(isset($_GET['term']))
		{
			$term = $_GET['term'];
		}
	
		log_message('error', $term);
	
		$this->load->model('Event_model');
		$data['event_types'] = $this->Event_model->get_types($term);
		$this->load->helper('url');
		$this->load->view('get_event_types', $data);
	}
}
?>	