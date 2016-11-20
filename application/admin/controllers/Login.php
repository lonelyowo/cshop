<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('Public_model');
        $this->load->model('Login_model');
        $this->load->model('Index_model');
	}
 
	public function index()
	{
		$this->load->view('login/index.html');
	}

	public function login()
	{
		$name = $this->input->post('username');
		$password = $this->input->post('password');
		
		if ($name && $password) {
			$sql = "SELECT * FROM `admin_user` 
					WHERE 
					`name`='{$name}' 
					AND 
					`password`='{$password}' LIMIT 1";
			$res = $this->db->query($sql)->row_array();
			if ($res) {
				$this->login_session($res);
				redirect( site_url() );
			}else{
				$this->Public_model->history_back('账号或密码错误');
			}
		}else{
			$this->Public_model->history_back('账号密码不能为空');
		}

	}

	public function login_session($userdata='')
	{
		$_SESSION['login'] = 1; //登陆flag
		$_SESSION['user'] = $userdata;
		// $_SESSION['history_url'] 上次访问记录
	
		// 菜单列表
		if ($_SESSION['user']['identity']=='root') {
			$menu = $this->Index_model->menu_permission_by_root();
		}else{
			$menu = $this->Index_model->menu_permission_by_userid($_SESSION['user']['id']);
			foreach ($menu as $menu_id => $value) {
				$res = $this->Index_model->menu_by_id($menu_id);
				$menu[$menu_id]['url'] = $res['url'];
				$menu[$menu_id]['name'] = $res['name'];
			}
		}
		$_SESSION['menu'] = $menu;
	}


}