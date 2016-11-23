<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author      : Chenjp
 * DateTime    : 2016-11-21
 * Description : Index Controller
 */
class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Index_model');
		$this->load->model('Public_model');
		// $this->Public_model->check_login();
	}

	public function index()
	{
		$this->theme();
	}

	public function theme()
	{
		$data['cate_list'] = $this->Index_model->get_cate();
		$this->load->view('theme.html', $data);
	}

	public function goods_list($cid=1)
	{
		$data['goods_list'] = $this->Index_model->get_goods_by_cate($cid);
		$this->load->view('goods_list.html', $data);
	}


}