<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->helper('url');
		$data = array();
		
		$data['add_meal']  	= $this->load->view('add_meal', array(), true);
		$data['add_event'] 	= $this->load->view('add_event', array(), true);
		$data['meal_list'] 	= $this->load->view('meal_view', array(), true);
		$data['event_list'] = $this->load->view('event_view', array(), true);
		
		$this->load->view('nav_view', $data);
	}
}

