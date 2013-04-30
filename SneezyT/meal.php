<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meal extends CI_Controller {
	
	public function index()
	{
		$this->load->view('add_meal');
	}
	
	public function insert()
	{
		$data = array();
		
		$food = $_POST['food'];
		$mealDate = new DateTime($_POST['meal_date']);
		
		$this->load->model('Meal_model');
		$data['result'] = $this->Meal_model->insert_meal($food, $mealDate);
		
		$this->load->helper('url');
		$this->load->view('insert_meal', $data);
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
		
		$this->load->model('Meal_model');
		$data['food_types'] = $this->Meal_model->get_types($term);
		$this->load->helper('url');
		$this->load->view('get_food_types', $data);
	}
	
	public function clean()
	{
		$data = array();
		
		$this->load->model('Meal_model');
		$data['food_types'] = $this->Meal_model->clean_food_types();
		$this->load->helper('url');
		$this->load->view('clean_food', $data);
	}
}
?>