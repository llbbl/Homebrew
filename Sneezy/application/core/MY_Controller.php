<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Abstract base controller class
 */
class MY_Controller extends CI_Controller {
	
	public function __construct()
	{
		// Call the Controller constructor
		parent::__construct();
		
		$this->define();
	}
	
	
	/**
	 * Went back and forth here but decided to force an override rather than have a generic DAO
	 *
	 */
	function define() 
	{
		
	}
	
	/**
	 * Default view for this type of data
	 * 
	 */
	public function index()
	{
		$this->add();
	}
	
	/**
	 * Add view for this type of data
	 * 
	 */
	public function add()
	{
		$data = array();
		$data['header'] = ucfirst($this->name);
		$data['name'] 	= $this->name;
		
		$this->load->view('add_view', $data);
	}
	
	
	/**
	 * Insert data
	 * 
	 */
	public function insert()
	{
		$data = array();
		$data['name'] = $this->name;
		
		$selection = $_POST[$this->name];
		$date = new DateTime($_POST[$this->name . '-date']);
		$note = $_POST[$this->name . '-note'];
		
		$model = ucfirst($this->name) . '_model';
		$this->load->model($model);
		
		$data['result'] = $this->$model->insert($selection, $date, $note);
		$data['alert'] = 'alert-success';
		if (!$data['result'])
		{
			$data['alert'] = 'alert-error';
		}
		
		$this->load->view('insert_view', $data);
	}
	
	public function get_types()
	{
		$data = array();
	
		$term = '';
	
		if(isset($_GET['term']))
		{
			$term = $_GET['term'];
		}
	
		$model = ucfirst($this->name) . '_model';
		$this->load->model($model);
		
		$data['json'] = $this->$model->get_types($term);
		
		$this->load->view('json_encode', $data);
	}
	
	
	
	public function retrieve_inventory()
	{
		$model = ucfirst($this->name) . '_model';
		$this->load->model($model);
		
		$index = intval($_GET['jtStartIndex']);
		$page_size = intval($_GET['jtPageSize']);
		
		$sort = ' ' . ucfirst($this->name) . 'Date DESC ';
		if (isset($_GET['jtSorting']))
		{	     
			$sort = html_entity_decode($_GET['jtSorting']);
		}
		
		$result = $this->$model->inventory($index, $page_size, trim($sort));
		$data['json'] = array("Result" => "OK", "Records" => $result );
		$this->load->view('json_encode', $data);
	}
	
	public function delete()
	{
		$model = ucfirst($this->name) . '_model';
		$this->load->model($model);
		
		$this->$model->delete(intval($_POST[ ucfirst($this->name) . 'Id']));
	
		$data = array();
		$data['json'] = array("Result" => "OK");
		$this->load->view('json_encode', $data);
	}
	
	public function update()
	{
		$this->load->helper('url');
		
		$model = ucfirst($this->name) . '_model';
		$this->load->model($model);
		
		$date = new DateTime($_POST[ucfirst($this->name) .'Date']);
		$this->$model->update(intval($_POST[ ucfirst($this->name) . 'Id']), $_POST[ucfirst($this->name) .'Note'], $date);
		
		$data = array();
		$data['json'] = array("Result" => "OK");
		$this->load->view('json_encode', $data);
	}
	
	/**
	 * Simplify the view of the type into one div
	 */
	public function category() 
	{
		$category_data = array();
			
		$category_data['name'] = $this->name;
		$category_data['hide'] = false;
		
		$category_data['section'] = array();
		$category_data['section']['add'] = $this->load->view('add_view', array('header'=>ucfirst($this->name), 'name'=>$this->name), true);
		
		$json = $this->load->view('inventory_json', array('type'=>ucfirst($this->name)), true);
		$category_data['section']['inventory'] = $this->load->view('inventory_view', array('name'=>$this->name, 'json'=>$json), true);
			
		$this->load->view('category_view', $category_data);
	}
}
?>