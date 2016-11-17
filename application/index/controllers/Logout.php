<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author      : Chenjp
 * DateTime    : 2016-11-17
 * Description : Logout Controller
 */
class Logout extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	// 退出
	public function index()
	{				
		session_unset();
		session_destroy();
		redirect(base_url());
	}

}
