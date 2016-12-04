<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 个人中心模块控制器
* @author Chenjp
*/
class My extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('my_model');
		$this->load->model('sys_model');
	}
	
	public function index()
	{
		$data['index_bottombar_active']['index_me'] = 'active';
		//判断是否有新消息
		// $data['hasnew'] = $this->my_model->check_hasnew();
		$data['title'] = $_SESSION['site_info']['name'];
		$data['header_title'] = $_SESSION['site_info']['name'];
		$this->load->view('club/index_me.html', $data);
	}
	/**
	 *个人信息
	 */
	public function info($userid='')
	{
		$data['title'] = '个人资料';
		$data['header_title'] = '个人资料';

		$this->load->view('club/user_profile.html', $data);
	}
	/**
	 *保存个人信息
	 */
	public function save_info()
	{
		$data = array(
					'userid' => $_SESSION['user']['id'],
					'nickname' => $_POST['user_profile_nickname'], 
					'sex' => $_POST['user_profile_sex'], 
					'birthday' => $_POST['user_profile_birthdate'], 
					'realname' => $_POST['user_profile_name'], 
					'cert_type' => $_POST['user_profile_idcard_type'], 
					'cert_num' => $_POST['user_profile_idcard'], 
					'phone' => $_POST['user_profile_phone'], 
					'province' => $_POST['user_profile_province'], 
					'city' => $_POST['user_profile_city'], 
					'district' => $_POST['user_profile_area'], 
					'address' => $_POST['user_profile_address'], 
					'job' => $_POST['user_profile_job'], 
					'weight' => $_POST['user_profile_weight'], 
					'height' => $_POST['user_profile_height'], 
					'projects' => $_POST['sports_items'], 
					'signs' => $_POST['user_profile_sign'], 
					);
		$cdate = time();
		//-------------------------------------关键字过滤 START ---------------------------------
		$res = $this->sys_model->check_keyword($data['nickname']);
		if($res !== TRUE)//存在敏感字
		{
			//记录sys_keyword_record 
			$keyword_record = array(
								'keywords' => $res,
								'userid' => $_SESSION['user']['id'],
								'username' => $_SESSION['user']['username'],
								'type' => 1,
								'cdate' => $cdate,
								'ip' => $_SERVER['REMOTE_ADDR'],
								);
			$this->sys_model->add_keyword_record($keyword_record);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('您填写的内容含敏感字：$res 。请重新填写！！');history.back();</script>";
			exit();
		}
		unset($res);
		$res = $this->sys_model->check_keyword($data['realname']);
		if($res !== TRUE)//存在敏感字
		{
			//记录sys_keyword_record 
			$keyword_record = array(
								'keywords' => $res,
								'userid' => $_SESSION['user']['id'],
								'username' => $_SESSION['user']['username'],
								'type' => 1,
								'cdate' => $cdate,
								'ip' => $_SERVER['REMOTE_ADDR'],
								);
			$this->sys_model->add_keyword_record($keyword_record);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('您填写的内容含敏感字：$res 。请重新填写！！');history.back();</script>";
			exit();
		}
		unset($res);
		$res = $this->sys_model->check_keyword($data['address']);
		if($res !== TRUE)//存在敏感字
		{
			//记录sys_keyword_record 
			$keyword_record = array(
								'keywords' => $res,
								'userid' => $_SESSION['user']['id'],
								'username' => $_SESSION['user']['username'],
								'type' => 1,
								'cdate' => $cdate,
								'ip' => $_SERVER['REMOTE_ADDR'],
								);
			$this->sys_model->add_keyword_record($keyword_record);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('您填写的内容含敏感字：$res 。请重新填写！！');history.back();</script>";
			exit();
		}
		unset($res);
		$res = $this->sys_model->check_keyword($data['job']);
		if($res !== TRUE)//存在敏感字
		{
			//记录sys_keyword_record 
			$keyword_record = array(
								'keywords' => $res,
								'userid' => $_SESSION['user']['id'],
								'username' => $_SESSION['user']['username'],
								'type' => 1,
								'cdate' => $cdate,
								'ip' => $_SERVER['REMOTE_ADDR'],
								);
			$this->sys_model->add_keyword_record($keyword_record);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('您填写的内容含敏感字：$res 。请重新填写！！');history.back();</script>";
			exit();
		}
		unset($res);
		$res = $this->sys_model->check_keyword($data['signs']);
		if($res !== TRUE)//存在敏感字
		{
			//记录sys_keyword_record 
			$keyword_record = array(
								'keywords' => $res,
								'userid' => $_SESSION['user']['id'],
								'username' => $_SESSION['user']['username'],
								'type' => 1,
								'cdate' => $cdate,
								'ip' => $_SERVER['REMOTE_ADDR'],
								);
			$this->sys_model->add_keyword_record($keyword_record);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('您填写的内容含敏感字：$res 。请重新填写！！');history.back();</script>";
			exit();
		}
		unset($res);
		//-------------------------------------关键字过滤 END ---------------------------------
		$data['province'] = $data['province'] == '省份' ? '' : $data['province'];
		$data['city'] = $data['city'] == '地级市' ? '' : $data['city'];
		$data['district'] = $data['district'] == '市、县级市、县' ? '' : $data['district'];
		//base64处理图片 
		$img = isset($_POST['user_profile_avatar'])? $_POST['user_profile_avatar'] : ''; 
		if($img != '') 
		{
			// 获取图片  
			list($type, $dataimg) = explode(',', $img);  
			// 判断类型  
			if(strstr($type,'image/jpeg')!==''){  
			  $ext = '.jpg';
			}elseif(strstr($type,'image/gif')!==''){  
			  $ext = '.gif';
			}elseif(strstr($type,'image/png')!==''){  
			  $ext = '.png';
			}
			// 生成的文件名  
			$photo = 'uploads/index/avatar/'.time().mt_rand(1,10000000).$ext; 
			//$photo = $_POST['match_photo_upload_description'].$ext; 
			// echo $photo;
			// 生成文件,上传 ,返回写入到文件内数据的字节数
			if(file_put_contents($photo, base64_decode($dataimg), true) > 0)
			{
				$data['avatar'] = base_url().$photo;
			}

		}
		$res = $this->my_model->save_info($data);
		if( $res == 1)
		{
			//用户日志记录
			$user_logs = array(
							'userid' => $_SESSION['user']['id'],
							'username' => $_SESSION['user']['username'],
							'type' => 'm_info',
							'content' => $_SESSION['user']['nickname'].'修改了个人资料',
							'remark' => '',
							'ip' => $_SERVER['REMOTE_ADDR'],
							'cdate' => $cdate,
						);
			$this->sys_model->add_user_logs($user_logs);
			if(isset($data['avatar']))
			{
				$old_avatar = explode('avatar/', $_SESSION['user']['avatar']);
				@unlink('./././uploads/index/avatar/'.$old_avatar[1]);
			}
			$userid = $_SESSION['user']['id'];
			unset($_SESSION['user']);
			$userdata = $this->Login_model->get_user($userid);
			$userdata['id'] = $userdata['userid'];
			unset($userdata['userid']);
			$_SESSION['user'] = $userdata; 
			// $this->Login_model->login_session($userdata);
			redirect('my');
		}
		else
		{
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('修改失败！！');history.back();</script>";
			exit();
		}
	}
	/**
	 *我的积分
	 */
	public function integral()
	{
		$data['title'] = '我的积分';
		$data['header_title'] = '我的积分';
		$data['integral'] = $this->my_model->get_my_integral($_SESSION['user']['id']);
		//积分排名百分比
		$data['integral_ranking'] = $this->my_model->get_integral_ranking($data['integral']);
		// echo "排名：".$data['integral_ranking'];

		$this->load->view('club/user_point.html', $data);
	}
	public function integral_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 30;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_integral_logs_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		//积分记录
		$integral_logs = $this->my_model->get_integral_logs($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($integral_logs))
		{
			$json['user_point'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($integral_logs);
		for ($i=0; $i < $len; $i++) { 
			$json['user_point'][$i]['text'] = $integral_logs[$i]['content'];
			$json['user_point'][$i]['time'] = date('Y-m-d H:i:s',$integral_logs[$i]['cdate']);
			$json['user_point'][$i]['point'] = $integral_logs[$i]['score'];
			$json['user_point'][$i]['isplus'] = $integral_logs[$i]['isadd'];
		}
		echo json_encode($json);
	}
	public function management_activity()
	{
		$data['title'] = '我的活动';
		$data['header_title'] = '我的活动';
		$data['ctrl'] = 'management_activity_list';

		$this->load->view('club/user_event.html', $data);
	}
	/**
	 *我的活动--我管理的
	 */
	public function management_activity_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 6;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_management_activities_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}

		$activity_lists = $this->my_model->get_management_activities($_SESSION['user']['id'],$offset,$per_page);
		// echo "<script>console.log('".$this->db->last_query()."')</script>";
		$current_time = time();
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($activity_lists))
		{
			$json['event'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($activity_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['event'][$i]['id'] = $activity_lists[$i]['id'];
			//判断活动状态 
			if($current_time <= $activity_lists[$i]['signup_date'])
			{
				$json['event'][$i]['state'] = 1;//报名中
			}
			elseif($current_time > $activity_lists[$i]['signup_date'] and $current_time < $activity_lists[$i]['start_date'])
			{
				$json['event'][$i]['state'] = 2;//报名结束
			}
			elseif($current_time >= $activity_lists[$i]['start_date'] and $current_time <= $activity_lists[$i]['end_date'] )
			{
				$json['event'][$i]['state'] = 3;//活动进行中
			}
			elseif($current_time > $activity_lists[$i]['end_date'])
			{
				$json['event'][$i]['state'] = 4;//活动结束
			}
			//封面
			$json['event'][$i]['cover'] = $activity_lists[$i]['img'];
			//标题
			$json['event'][$i]['title'] = $activity_lists[$i]['title'];
			if($activity_lists[$i]['status'] == 0)
				$json['event'][$i]['title'] .= '（已关闭）';
			//今年的不显示年份
			if(date('Y',$current_time) == date('Y',$activity_lists[$i]['start_date']))
			{
				$start_time = date('m月d日',$activity_lists[$i]['start_date']);
			}
			else
			{
				$start_time = date('Y年m月d日',$activity_lists[$i]['start_date']);
			}
			if(empty($activity_lists[$i]['city']) && empty($activity_lists[$i]['district']))
			{
				$json['event'][$i]['subinfo'] = $start_time;
			}
			else
			{
				$json['event'][$i]['subinfo'] = $start_time.' - '.$activity_lists[$i]['city'].$activity_lists[$i]['district'];
			}
			
			//费用
			if($activity_lists[$i]['fee'] == 0)
				$json['event'][$i]['price'] = '';
			else
				$json['event'][$i]['price'] = '￥'.$activity_lists[$i]['fee'];
			//已报名人数
			// $json['event'][$i]['actornum'] = $activity_lists[$i]['signup_num'];
			$json['event'][$i]['actornum'] = $activity_lists[$i]['orders_num'];
			//author_avatar头像
			$json['event'][$i]['author_avatar'] = $activity_lists[$i]['avatar'];
			//author_name 昵称
			$json['event'][$i]['author_name'] = $activity_lists[$i]['nickname'];
			//club_id 
			if($activity_lists[$i]['isclub'] == 1)
			{
				$json['event'][$i]['club_id'] = $activity_lists[$i]['club_id'];
				$json['event'][$i]['club_logo'] = $activity_lists[$i]['logos'];
				$json['event'][$i]['club_name'] = $activity_lists[$i]['name'];
			}
			else
			{
				$json['event'][$i]['club_id'] = '';
				$json['event'][$i]['club_logo'] = '';
				$json['event'][$i]['club_name'] = '';
			}
			//点赞数
			$json['event'][$i]['zan_num'] = $activity_lists[$i]['zan_num'];
			//浏览数
			$json['event'][$i]['view_num'] = $activity_lists[$i]['view_num'];
		}
		echo json_encode($json);
	}
	public function apply_activity()
	{
		$data['title'] = '我的活动';
		$data['header_title'] = '我的活动';
		$data['ctrl'] = 'apply_activity_list';

		$this->load->view('club/user_event.html', $data);
	}
	/**
	 *我的活动--我报名的
	 */
	public function apply_activity_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 4;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_apply_activities_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		$activity_lists = $this->my_model->get_apply_activities($_SESSION['user']['id'],$offset,$per_page);
		// echo "<script>console.log('".$this->db->last_query()."')</script>";
		$current_time = time();
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($activity_lists))
		{
			$json['event'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($activity_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['event'][$i]['id'] = $activity_lists[$i]['id'];
			//判断活动状态 
			if($current_time <= $activity_lists[$i]['signup_date'])
			{
				$json['event'][$i]['state'] = 1;//报名中
			}
			elseif($current_time > $activity_lists[$i]['signup_date'] and $current_time < $activity_lists[$i]['start_date'])
			{
				$json['event'][$i]['state'] = 2;//报名结束
			}
			elseif($current_time >= $activity_lists[$i]['start_date'] and $current_time <= $activity_lists[$i]['end_date'] )
			{
				$json['event'][$i]['state'] = 3;//活动进行中
			}
			elseif($current_time > $activity_lists[$i]['end_date'])
			{
				$json['event'][$i]['state'] = 4;//活动结束
			}
			//封面
			$json['event'][$i]['cover'] = $activity_lists[$i]['img'];
			//标题
			$json['event'][$i]['title'] = $activity_lists[$i]['title'];
			//今年的不显示年份
			if(date('Y',$current_time) == date('Y',$activity_lists[$i]['start_date']))
			{
				$start_time = date('m月d日',$activity_lists[$i]['start_date']);
			}
			else
			{
				$start_time = date('Y年m月d日',$activity_lists[$i]['start_date']);
			}
			if(empty($activity_lists[$i]['city']) && empty($activity_lists[$i]['district']))
			{
				$json['event'][$i]['subinfo'] = $start_time;
			}
			else
			{
				$json['event'][$i]['subinfo'] = $start_time.' - '.$activity_lists[$i]['city'].$activity_lists[$i]['district'];
			}
			
			//费用
			if($activity_lists[$i]['fee'] == 0)
				$json['event'][$i]['price'] = '';
			else
				$json['event'][$i]['price'] = '￥'.$activity_lists[$i]['fee'];
			//已报名人数
			// $json['event'][$i]['actornum'] = $activity_lists[$i]['signup_num'];
			$json['event'][$i]['actornum'] = $activity_lists[$i]['orders_num'];
			//author_avatar头像
			$json['event'][$i]['author_avatar'] = $activity_lists[$i]['avatar'];
			//author_name 昵称
			$json['event'][$i]['author_name'] = $activity_lists[$i]['nickname'];
			//club_id 
			if($activity_lists[$i]['isclub'] == 1)
			{
				$json['event'][$i]['club_id'] = $activity_lists[$i]['club_id'];
				$json['event'][$i]['club_logo'] = $activity_lists[$i]['logos'];
				$json['event'][$i]['club_name'] = $activity_lists[$i]['name'];
			}
			else
			{
				$json['event'][$i]['club_id'] = '';
				$json['event'][$i]['club_logo'] = '';
				$json['event'][$i]['club_name'] = '';
			}
			//点赞数
			$json['event'][$i]['zan_num'] = $activity_lists[$i]['zan_num'];
			//浏览数
			$json['event'][$i]['view_num'] = $activity_lists[$i]['view_num'];
		}
		echo json_encode($json);
	}
	/**
	 *我的俱乐部--我管理的
	 */
	public function management_club()
	{
		$data['title'] = '我的俱乐部';
		$data['header_title'] = '我的俱乐部';
		$data['ctrl'] = 'management_club_list';

		$this->load->view('club/user_club.html', $data);
	}
	/**
	 *我的俱乐部--我加入的
	 */
	public function join_club()
	{
		$data['title'] = '我的俱乐部';
		$data['header_title'] = '我的俱乐部';
		$data['ctrl'] = 'join_club_list';

		$this->load->view('club/user_club.html', $data);
	}
	/**
	 *我的俱乐部--我管理的-AJAX
	 */
	public function management_club_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 6;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_management_clubs_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		$club_lists = $this->my_model->get_management_clubs($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($club_lists))
		{
			$json['club'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($club_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['club'][$i]['id'] = $club_lists[$i]['id'];
			$json['club'][$i]['logo'] = $club_lists[$i]['logos'];
			//是否是官方 1 是 0 否
			if($club_lists[$i]['shopno'] == '')
				$json['club'][$i]['qual'] = 0;
			else
				$json['club'][$i]['qual'] = 1;
			$json['club'][$i]['name'] = $club_lists[$i]['name'];
			$json['club'][$i]['name'] = $club_lists[$i]['name'];
			//星级，待定
			$json['club'][$i]['star'] = 1;
			$json['club'][$i]['slogan'] = $club_lists[$i]['slogan'];
			$json['club'][$i]['people'] = $club_lists[$i]['people_num'];
			//----------等级图片----------
			$json['club'][$i]['pic'] = base_url().$club_lists[$i]['pic'];
		}
		echo json_encode($json);
	}
	/**
	 *我的俱乐部--我加入的-AJAX
	 */
	public function join_club_list($userid='')
	{
		//分页
		$page = $_POST['page'];
		$per_page = 6;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_join_clubs_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		$club_lists = $this->my_model->get_join_clubs($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($club_lists))
		{
			$json['club'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($club_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['club'][$i]['id'] = $club_lists[$i]['id'];
			$json['club'][$i]['logo'] = $club_lists[$i]['logos'];
			//是否是官方 1 是 0 否
			if($club_lists[$i]['shopno'] == '')
				$json['club'][$i]['qual'] = 0;
			else
				$json['club'][$i]['qual'] = 1;
			$json['club'][$i]['name'] = $club_lists[$i]['name'];
			$json['club'][$i]['name'] = $club_lists[$i]['name'];
			//星级，待定
			$json['club'][$i]['star'] = 1;
			$json['club'][$i]['slogan'] = $club_lists[$i]['slogan'];
			$json['club'][$i]['people'] = $club_lists[$i]['people_num'];
			//----------等级图片----------
			$json['club'][$i]['pic'] = base_url().$club_lists[$i]['pic'];
		}
		echo json_encode($json);
	}
	/**
	 *我的游记
	 */
	public function travels()
	{
		
		$data['title'] = '我的游记';
		$data['header_title'] = '我的游记';

		$this->load->view('club/user_travelogue.html', $data);
	}
	public function travels_list()
	{
		$page = $_POST['page'];
		$per_page = 8;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_travels_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		//游记列表
		$activity_travels = $this->my_model->get_travels($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($activity_travels))
		{
			$json['travelogue'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($activity_travels);
		for ($i=0; $i < $len; $i++) { 
			$json['travelogue'][$i]['id'] = $activity_travels[$i]['id'];
			$json['travelogue'][$i]['author_id'] = $activity_travels[$i]['userid'];
			$json['travelogue'][$i]['author_avatar'] = $activity_travels[$i]['avatar'];
			$json['travelogue'][$i]['author_name'] = $activity_travels[$i]['nickname'];
			$json['travelogue'][$i]['time'] = date('Y年m月d日 H:i',$activity_travels[$i]['cdate']);
			$json['travelogue'][$i]['cover'] = $activity_travels[$i]['img'];
			$json['travelogue'][$i]['title'] = $activity_travels[$i]['title'];
			$json['travelogue'][$i]['event'] = $activity_travels[$i]['activity_title'];
			$json['travelogue'][$i]['event_id'] = $activity_travels[$i]['activity_id'];
			$json['travelogue'][$i]['stati_view'] = $activity_travels[$i]['view_num'];
			$json['travelogue'][$i]['stati_comment'] = $activity_travels[$i]['comment_num'];
			$json['travelogue'][$i]['stati_zan'] = $activity_travels[$i]['zan_num'];
		}
		echo json_encode($json);
	}
	public function signin()
	{
		//判断用户当日是否签到
		if(!$this->my_model->is_signin($_SESSION['user']['id']))
		{
			$json['state'] = 0;
			$json['msg'] = '您今天已签到，不需要重复签到！';
			echo json_encode($json);
			exit();
		}
		$cdate = time();
		
		//签到积分
		$score = $this->sys_model->user_integral($_SESSION['user']['id'],'sign_in','签到',$cdate,$_SESSION['user']['username']);
		if($score !== FALSE)
		{
			//签到表插入数据
			$sign_data = array(
							'userid' => $_SESSION['user']['id'],
							'username' => $_SESSION['user']['username'],
							'remark' => '',
							'ip' => $_SERVER['REMOTE_ADDR'],
							'cdate' => $cdate,
							);
			$this->sys_model->add_user_signin_logs($sign_data);
			//用户日志
			$user_logs = array(
							'userid' => $_SESSION['user']['id'],
							'username' => $_SESSION['user']['username'],
							'type' => 'sign_in',
							'content' => '签到',
							'remark' => '',
							'ip' => $_SERVER['REMOTE_ADDR'],
							'cdate' => $cdate,
						);
			$this->sys_model->add_user_logs($user_logs);
			$json['state'] = 1;
			$json['msg'] = '签到成功<br>积分+'.$score;
			echo json_encode($json);
		}
		else
		{
			$json['state'] = 0;
			$json['msg'] = '您今天已签到或暂时无法签到！';
			echo json_encode($json);
		}
		
	}
	/**
	 * 设置
	 */
	public function setting()
	{
		$data['title'] = '设置';
		$data['header_title'] = '设置';

		$this->load->view('club/user_setting.html', $data);
	}
	public function follow($userid='')
	{
		//判断是否关注
		if($this->my_model->is_follow($_SESSION['user']['id'],$userid))
		{
			$json['state'] = 0;
			$json['msg'] = '您已关注该用户，无需重复关注！';
			echo json_encode($json);
			exit();
		}
		$user_info = $this->my_model->get_info($userid);
		$cdate = time();
		//数据库插入
		$data = array(
					'f_userid' => $_SESSION['user']['id'],
					'f_username' => $_SESSION['user']['username'],
					't_userid' => $user_info['userid'],
					't_username' => $user_info['username'],
					'status' => 0,
					);
		$this->my_model->follow($data);
		//用户日志记录
		$user_logs = array(
						'userid' => $_SESSION['user']['id'],
						'username' => $_SESSION['user']['username'],
						'type' => 'follow',
						'content' => '关注好友',
						'remark' => '',
						'ip' => $_SERVER['REMOTE_ADDR'],
						'cdate' => $cdate,
					);
		$this->sys_model->add_user_logs($user_logs);
		$json['state'] = 1;
		$json['msg'] = '关注成功!';
		echo json_encode($json);
		exit();
	}
	public function unfollow($userid='')
	{
		//判断是否关注
		if(!$this->my_model->is_follow($_SESSION['user']['id'],$userid))
		{
			$json['state'] = 0;
			$json['msg'] = '您没有关注该用户哦！';
			echo json_encode($json);
			exit();
		}
		$cdate = time();
		//数据库删除
		$this->my_model->unfollow($_SESSION['user']['id'],$userid);
		//用户日志记录
		$user_logs = array(
						'userid' => $_SESSION['user']['id'],
						'username' => $_SESSION['user']['username'],
						'type' => 'unfollow',
						'content' => '取消关注',
						'remark' => '',
						'ip' => $_SERVER['REMOTE_ADDR'],
						'cdate' => $cdate,
					);
		$this->sys_model->add_user_logs($user_logs);
		$json['state'] = 1;
		$json['msg'] = '已取消关注!';
		echo json_encode($json);
		exit();
	}
	/**
	 *我的好友--已添加
	 */
	public function friends($userid='')
	{
		$data['title'] = '我的好友';
		$data['header_title'] = '我的好友';
		$data['ctrl'] = 'friends_list';
		$this->load->view('club/user_fans.html', $data);
	}
	public function friends_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 25;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_friends_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		$friend_lists = $this->my_model->get_friends($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($friend_lists))
		{
			$json['user_fans'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($friend_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['user_fans'][$i]['userid'] = $friend_lists[$i]['t_userid'];
			$json['user_fans'][$i]['avatar'] = $friend_lists[$i]['avatar'];
			$json['user_fans'][$i]['name'] = $friend_lists[$i]['nickname'];
			$json['user_fans'][$i]['sex'] = $friend_lists[$i]['sex'];
			if($friend_lists[$i]['is_friend'] == NULL)
			{
				$json['user_fans'][$i]['state'] = 1;
			}
			else
			{
				$json['user_fans'][$i]['state'] = 2;
			}
		}
		echo json_encode($json);
	}
	/**
	 *我的好友--粉丝
	 */
	public function fans($userid='')
	{
		$data['title'] = '我的好友';
		$data['header_title'] = '我的好友';
		$data['ctrl'] = 'fans_list';
		$this->load->view('club/user_fans.html', $data);
	}
	public function fans_list()
	{
		//分页
		$page = $_POST['page'];
		$per_page = 10;
		$offset = ($page)*$per_page;
		$all_num = $this->my_model->get_fans_num($_SESSION['user']['id']);
		$all_page = (int)ceil( $all_num/$per_page );
		if($page == $all_page -1)
		{
			$json['end'] = 1;
		}
		else
		{
			$json['end'] = 0;
		}
		$friend_lists = $this->my_model->get_fans($_SESSION['user']['id'],$offset,$per_page);
		$json['state'] = 1;
		$json['msg'] = 'ok';
		if(empty($friend_lists))
		{
			$json['user_fans'] = '';
			echo json_encode($json);
			exit();
		}
		$len = count($friend_lists);
		for ($i=0; $i < $len; $i++) { 
			$json['user_fans'][$i]['userid'] = $friend_lists[$i]['f_userid'];
			$json['user_fans'][$i]['avatar'] = $friend_lists[$i]['avatar'];
			$json['user_fans'][$i]['name'] = $friend_lists[$i]['nickname'];
			$json['user_fans'][$i]['sex'] = $friend_lists[$i]['sex'];
			if($friend_lists[$i]['is_friend'] == NULL)
			{
				$json['user_fans'][$i]['state'] = 0;
			}
			else
			{
				$json['user_fans'][$i]['state'] = 2;
			}
		}
		echo json_encode($json);
	}
}