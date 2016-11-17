<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

	public function index()
	{
		var_dump($_SESSION);
	}
}
