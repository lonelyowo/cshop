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
		$data['avail_cash'] = 100;
		$data['freeze_money'] = 999;

		// var_dump($data);exit();
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
	public function cash_detail($value='')
	{
		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet_record.html', $data);
	}

	// 预估收入明细
	public function freeze_detail($value='')
	{
		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet_record1.html', $data);
	}


}