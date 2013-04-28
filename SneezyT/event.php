<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
	public function index()
	{
		$this->load->model('Event_model');
		$this->load->helper('url');
		$this->load->view('add_event');
	}
}
?>	