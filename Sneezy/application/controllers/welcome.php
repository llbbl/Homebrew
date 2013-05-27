<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data = array();
		
		$data['add_meal']  	= $this->load->view('add_view', array('header'=>'Food', 'name'=>'food'), true);

		$type = 'food';
		$json = $this->load->view('inventory_json', array('type'=>ucfirst($type)), true);
		$data['food_inventory'] = $this->load->view('inventory_view', array('name'=>$type, 'json'=>$json), true);
		
		$type = 'event';
		$json = $this->load->view('inventory_json', array('type'=>ucfirst($type)), true);
		$data['event_inventory'] = $this->load->view('inventory_view', array('name'=>$type, 'json'=>$json), true);
		
		$this->load->view('nav_view', $data);
	}
}
