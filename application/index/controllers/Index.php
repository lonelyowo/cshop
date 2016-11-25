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
		// $this->Login_model->login();

		// $data['sys_set']['withdraw_start_day'] = $this->Wallet_model->get_sys_set('withdraw_start_day');
		// $data['sys_set']['withdraw_end_day'] = $this->Wallet_model->get_sys_set('withdraw_end_day');
		// // 每月
		// $data['day'] = date('d');
		// // var_dump($data);exit();

		// $data['avail_cash'] = $this->Wallet_model->avail_get_cash($_SESSION['user']['id']);
		// $data['freeze_money'] = $this->Wallet_model->count_freeze_money($_SESSION['user']['id']);
		// $data['identity_verify_status'] = $this->Wallet_model->get_identity_verify_status();

		// var_dump($data);exit();
		$data['title'] = '我的钱包';
		$data['header_title'] = '我的钱包';
		$this->load->view('club/user_wallet.html', $data);
	}


}