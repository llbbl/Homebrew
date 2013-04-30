<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->helper('url');
		$data = array();
		
		$data['meal']  = $this->load->view('add_meal', array(), true);
		$data['event'] = $this->load->view('add_event', array(), true);
		
		$this->load->view('welcome_message', $data);
	}
}

