<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author      : Chenjp
 * DateTime    : 2016-11-17
 * Description : Login Controller
 */
class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('Login_model');
	}

	public function index()
	{
		$this->load->view('login/login.html');
	}

	public function reg()
	{
		$this->load->view('login/reg.html');
	}

	/*public function reset()
	{
		$this->load->view('login/reset.html');
	}*/

	/**
	 * [reg_bll 注册ajax提交处理]
	 * @param  array  $data [用户注册数据]
	 * @return [type]       [json]
	 */
	public function reg_bll()
	{
		
		$data = $_POST;
		$res = $this->Api_model->reg($data);
		echo json_encode($res);
	}


	/**
	 * [get_captcha get短信验证码]
	 * @return [type] [json]
	 */
	public function get_captcha()
	{
		if (!isset($_POST['phone'])) {
            exit('非法操作');
        }
        
		$res = $this->Api_model->get_captcha($_POST['phone']);
		if ($res['status'] === 1) {
			$json_arr = array(
				'status'=>1,
				'msg'=>'短信验证码发送成功',
				);
		}else{
			// 验证码发送失败
			$json_arr = array(
				'status'=>0,
				'msg'=>$res['message'],
				);
		}
		echo json_encode($json_arr);
	}

	public function get_reset_captcha()
	{
		if (!isset($_POST['phone'])) {
            exit('非法操作');
        }
        
		$res = $this->Api_model->get_reset_captcha($_POST['phone']);
		if ($res['status'] === 1) {
			$json_arr = array(
				'status'=>1,
				'msg'=>'短信验证码发送成功',
				);
		}else{
			// 验证码发送失败
			$json_arr = array(
				'status'=>0,
				'msg'=>$res['message'],
				);
		}
		echo json_encode($json_arr);
	}

	/**
	 * [reset_bll 重置密码ajax提交处理]
	 * @param  array  $data [提交信息]
	 * @return [type]       [description]
	 */
	public function reset_bll( $data=array() )
	{
		
		$data = $_POST;
		$res = $this->Api_model->repassword($data);
		echo json_encode($res);
	}
	
	/**
 	 * 登陆
 	 * @param  string $username 账号 手机或邮箱
 	 * @param  string $password 密码
 	 * @return [type]           json
 	 */

	public function login()
	{
		if ($this->input->post()) {
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		
			if ($username && $password) {
				
				
				$res = $this->Api_model->login($username, $password);
				if (!$res['status']) {
					// 'msg'=>'接口登陆失败'
					$json_arr = array(
						'status'=>0,
						'msg'=>'用户名或密码错误'
						);
					echo json_encode($json_arr);
					exit();
				}
				
				$userid = $this->check_user($res['Data']['loginName']);
				if (!$userid) {
					$userid = $this->add_user($res['Data']);
				}
				$userdata = $this->Init_model->get_user($userid);
				$this->Init_model->login_session($userdata);
				
				$json_arr = array(
						'status'=>1,
						'msg'=>'登陆成功'
						);
				echo json_encode($json_arr);
			}
		
		}

	}



	/**
 	 * [bind 微信和捷安特账号绑定]
 	 * @param  [type] $openid             [微信openid]
 	 * @param  [type] $giant_users_userid [捷安特userid]
 	 * @return [boolean]                  [true or false]
 	 */
	public function bind()
	{
		$openid = $_SESSION['openid'];
		$giant_users_userid = isset($_SESSION['user']['id'])?$_SESSION['user']['id']:false;

		if ($openid && $giant_users_userid) {
			
			if ( $this->check_openid_bind($openid) && $this->check_giant_userid_bind($giant_users_userid) ) {
				$this->sql_bind($openid, $giant_users_userid);
			}
			
			$url = isset($_SESSION['history_url'])?$_SESSION['history_url']:base_url();
			redirect($url);
		}

	}


	/**
	 * [sql_bind openid和userid绑定]
	 * @param  [type] $openid             [description]
	 * @param  [type] $giant_users_userid [description]
	 * @return [type]                     [description]
	 */
	public function sql_bind($openid, $giant_users_userid)
	{
		$sql = "UPDATE `weixin_users`
				SET giant_users_userid = {$giant_users_userid}
				WHERE
					openid = '{$openid}'";
		$this->db->query($sql);
	}

	/**
	 * [check_giant_userid_bind 检查weixin_user表有没有session['user']['id'] ]
	 * @param  string $giant_users_userid [giant_users_userid]
	 * @return [boolean]                     [没有绑定返回true]
	 */
	public function check_giant_userid_bind($giant_users_userid='')
	{
		$sql = "SELECT * FROM `weixin_users` WHERE `giant_users_userid`={$giant_users_userid} LIMIT 1";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		if ($row) {
			return FALSE;
		}else{
			return TRUE;
		}
	}

	// openid没绑定过返回true
	public function check_openid_bind($openid='')
	{
		$sql = "SELECT * FROM `weixin_users` WHERE `openid`='{$openid}' LIMIT 1";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		if ($row && empty($row['giant_users_userid'])) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
	

	/**
	 * [check_user 检查本地有没有这个账号]
	 * @param  string $username [登陆账号]
	 * @return [type]           [用户自增id or false]
	 */
	public function check_user($username='')
	{
		$sql = "SELECT * FROM `giant_users` WHERE `username`='{$username}' LIMIT 1";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		if ($row) {
			return $row['userid'];
		}else{
			return false;
		}
	}

	
	

	/**
	 * [add_user description]
	 * @param array $user [用户信息]
	 */
	public function add_user( $user=array() )
	{		
		$now = time();
		$province = '';
		$city = '';

		/*$tmp = explode(',',$user['address']);

		if (isset($tmp[0])) {
			$province = $tmp[0];
		}
		if (isset($tmp[1])) {
			$city = $tmp[1];
		}*/

		
		$sql = "INSERT INTO `giant_users` (
					`userid`,
					`username`,
					`nickname`,
					`realname`,
					`usertype`,
					`shopno`,
					`phone`,
					`avatar`,
					`province`,
					`city`,
					`status`,
					`cdate`
				)
				VALUES
					(
						'{$user['userId']}',
						'{$user['loginName']}',
						'{$user['nickName']}',
						'{$user['realName']}',
						'{$user['userType']}',
						'{$user['Code']}',
						'{$user['phone']}',
						'{$user['avatar']}',
						'{$province}',
						'{$city}',
						'1',
						'{$now}'
					)";
		$this->db->query($sql);
		/*$userid = $this->db->insert_id();
		if ($userid) {
			return $userid;
		}else{
			return false;
		}*/

		if ( $this->db->affected_rows() == 1 ) {
			return $user['userId'];
		}else{
			return false;
		}

	}
	


}	
