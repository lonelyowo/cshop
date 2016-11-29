<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		var_dump($this->db->where('id', 1)->get('order')->row_array());
	}

	public function sess()
	{
		var_dump($_SESSION);
	}

	public function cls()
	{
		session_unset();
		session_destroy();
	}


}