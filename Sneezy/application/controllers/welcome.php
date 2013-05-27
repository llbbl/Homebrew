<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data = array();
		
		$data['add_meal']  	= $this->load->view('add_view', array('header'=>'Food', 'name'=>'food'), true);
		$data['add_event'] 	= $this->load->view('add_view', array('header'=>'Event', 'name'=>'event'), true);
		$data['meal_list'] 	= '';//$this->load->view('meal_view', array(), true);
		$data['event_list'] = '';//$this->load->view('event_view', array(), true);
		
		$this->load->view('nav_view', $data);
	}
}
