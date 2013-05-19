<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Result extends CI_Controller {

	public function index()
	{
		$data = array();
		$this->load->helper('url');
		$this->load->view('hours_from_event');
	}

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
}