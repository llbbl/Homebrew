<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meal extends CI_Controller {
	public function index()
	{
		$this->load->model('Meal_model');
		$this->load->helper('url');
		$this->load->view('add_meal');
	}
	
	public function insert()
	{
		$data = array();
		
		$food = $_POST['food'];
		$mealDate = new DateTime($_POST['mealDate']);
		
		$this->load->model('Meal_model');
		$data['result'] = $this->Meal_model->insert_meal($food, $mealDate);
		
		$this->load->helper('url');
		$this->load->view('insert_meal', $data);
	}
	
	public function get_food_types()
	{
		$data = array();
		
		$term = '';
		if(isset($_GET['term']))
		{
			$term = $_GET['term'];
		}
		
		$this->load->model('Meal_model');
		$data['food_types'] = $this->Meal_model->get_food_types($term);
		$this->load->helper('url');
		$this->load->view('get_food_types', $data);
	}
	
	public function clean()
	{
		$data = array();
		
		$this->load->model('Meal_model');
		$data['food_types'] = $this->Meal_model->cleanFoodTypes();
		$this->load->helper('url');
		$this->load->view('clean_food', $data);
	}
}
?>