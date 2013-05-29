<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Result extends CI_Controller {

	public function index()
	{
		$data = array();
		$this->load->helper('url');
		$this->load->view('hours_from_event');
	}

	/**
	 * @todo complete this
	 */
	public function hours_from_event()
	{
		$this->load->model('Meal_model');
		// jtStartIndex=0&jtPageSize=10&jtSorting=meal_date%20ASC
		$index = intval($_GET['jtStartIndex']);
		$pageSize = intval($_GET['jtPageSize']);
		log_message("error", print_r($_GET, true));
		$sort = ' MealDate DESC ';
		if (isset($_GET['jtSorting']))
		{
			$sort = html_entity_decode($_GET['jtSorting']);
		}
		$meals = $this->Meal_model->get_meals($index, $pageSize, $sort);
		$data['meals'] = array("Result" => "OK", "Records" => $meals );
		$this->load->view('meal_list', $data);
	}
	
	public function timeline()
	{
		$this->load->helper('url');
		
		
		$data = array();
		$this->load->view('timeline_view', $data);
	}
	
	public function get_timeline_data()
	{
		$this->load->model('Result_model');
		$timeline = $this->Result_model->timeline_data();
		$data = array();
		$data['json'] = $this->transform_timeline_data($timeline);
		$this->load->view('json_encode', $data);
	}
	
	private function transform_timeline_data($timeline)
	{
		$this->load->helper('url');
		
		$data = array();
		$data['dataTimeFormat'] = 'iso8601';
		$data['events'] = array();
		foreach($timeline as $line)
		{
			$temp = array();
			$temp['start'] = $line['Date'];
			$temp['title'] = $line['Name'];
			$temp['description'] = 'Id: ' . $line['Id'] . ' - ' . $line['Note'];

			if ($line['Type'] == 'Reaction')
			{
				$temp['icon'] = base_url() . 'js/timeline_2.3.0/timeline_js/images/dark-red-circle.png';
			}
			else if ($line['Type'] == 'Environment')
			{
				$temp['icon'] = base_url() . 'js/timeline_2.3.0/timeline_js/images/medium-gray-circle.png';
			}
			else if ($line['Type'] == 'Medicine')
			{
				$temp['icon'] = base_url() . 'js/timeline_2.3.0/timeline_js/images/dark-green-circle.png';
			}
			
			$data['events'][] = $temp; 
		}
		
		return $data;
	}
}