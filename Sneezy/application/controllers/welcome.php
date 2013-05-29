<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$types = array('food', 'reaction','environment','medicine');
		
		$data = array();
		$hide = false;
		foreach($types as $type)
		{
			$category_data = array();
			
			$category_data['name'] = $type;
			$category_data['hide'] = $hide;
			
			$category_data['section'] = array();
			$category_data['section']['add'] = $this->load->view('add_view', array('header'=>ucfirst($type), 'name'=>$type), true);
			
			$json = $this->load->view('inventory_json', array('type'=>ucfirst($type)), true);
			$category_data['section']['inventory'] = $this->load->view('inventory_view', array('name'=>$type, 'json'=>$json), true);
			
			$data[$type] = $this->load->view('category_view', $category_data, true);
			
			$hide = true;
		}	
		
		$this->load->view('nav_view', $data);
	}
}
