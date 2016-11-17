<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

	public function index()
	{
		$_SESSION['name'] = 'chenjp';
		var_dump($_SESSION);
	}
}
