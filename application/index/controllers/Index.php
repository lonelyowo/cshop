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


	/*
	 * Author      : Chenjp
	 * DateTime    : 2016-11-25
	 * Description : 我的钱包页面
	 */
	public function user_wallet()
	{
		$row = $this->db->where('id', $_SESSION['user']['id'])->get('user')->row_array();

		$data['avail_cash'] = $row['cash_money'];
		$data['freeze_money'] = $row['freeze_money'];

		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet.html', $data);
	}

	public function user_cash()
	{
		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';

		$data['cash'] = 100;
		$this->load->view('club/user_cash.html', $data);
	}

	// 余额明细
	public function cash_detail()
	{
		$data['data'] = $this->db->where('user_id', $_SESSION['user']['id'])->get('cash')->result_array();
		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet_record.html', $data);
	}

	// 预估收入明细
	public function freeze_detail()
	{
		$data['data'] = $this->db->where('user_id', $_SESSION['user']['id'])->get('freeze')->result_array();

		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet_record1.html', $data);
	}


}