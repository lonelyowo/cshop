<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 系统设置、日志相关--model
* @author liqingbao
*/
class Sys_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * 获取所有关键字 关键字过滤相关
	 * status 状态  1开启  0关闭
	 */
	public function get_sys_keywords()
	{
		$sql = 'SELECT
					keywords
				FROM
					`sys_keyword`
				WHERE
					`status` = 1';
		return $this->db->query($sql)->result_array();
	}
	/**
	 * 增加关键字记录
	 */
	public function add_keyword_record($data='')
	{
		$this->db->trans_start();
		$this->db->insert('sys_keyword_record', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 获取经验设置
	 * @param type
	 * status 1-开启 0-关闭
	 */
	public function get_sys_exp($type='')
	{
		$sql = 'SELECT
					*
				FROM
					`sys_expe`
				WHERE
					type = ?
				AND `status` = 1';
		return $this->db->query($sql,array($type))->row_array();
	}
	/**
	 * 俱乐部经验增加
	 * @param $id 俱乐部id
	 * @param $exp 增加的经验值
	 */
	public function add_club_exp($id='',$exp='')
	{
		$sql = 'UPDATE clubs
				SET experience = experience + ?
				WHERE
					id = ?';
		$this->db->query($sql,array($exp,$id));
		return $this->db->affected_rows();
	}
	/**
	 * 检查获取经验周期内次数是否已满
	 * @param int $club_id 俱乐部id
	 * @param string $cycle_unit 周期单位 day,week,month,year
	 * @param int $cycle 周期内频率 
	 * @param string $type 经验类型 
	 * @return bool 未满返回TRUE 满返回FALSE
	 */
	public function check_club_exp_cycle($club_id='',$cycle_unit,$cycle,$max_times,$type)
	{
		// $end_time = time();
		$day = date('Y-m-d',time());
		$end_time = strtotime($day.' 23:59:59');
		switch ($cycle_unit) {
			case 'day':
				$start_time = $end_time - 3600*24*$cycle;
				break;
			case 'week':
				$start_time = $end_time - 3600*24*7*$cycle;
				break;
			case 'month':
				$start_time = $end_time - 3600*24*30*$cycle;
				break;
			case 'year':
				$start_time = $end_time - 3600*24*365*$cycle;
				break;
			case 'all':
				$start_time = 0;
				break;
			default:
				$start_time = $end_time - 3600*24*$cycle;
				break;
		}
		$sql = 'SELECT
					COUNT(*) AS num
				FROM
					`clubs_expelog`
				WHERE
					club_id = ?
				AND isadd = 1
				AND cdate >= ?
				AND cdate <= ?
				AND type = ?';
		$res = $this->db->query($sql,array($club_id,$start_time,$end_time,$type))->row_array();
		return $res['num'] < $max_times;
	}

	/**
	 * 记录用户日志
	 */
	public function add_user_logs($data='')
	{
		$this->db->trans_start();
		$this->db->insert('user_logs', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 记录用户消息
	 */
	public function add_user_msg($data='')
	{
		$this->db->insert('user_msg', $data);
		
	}
	/**
	 * 记录用户日志
	 */
	public function add_user_signin_logs($data='')
	{
		$this->db->trans_start();
		$this->db->insert('sys_signinlo', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 俱乐部经验日志记录
	 */
	public function add_club_exp_logs($data='')
	{
		$this->db->trans_start();
		$this->db->insert('clubs_expelog', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 俱乐部日志记录
	 */
	public function add_club_logs($data='')
	{
		$this->db->trans_start();
		$this->db->insert('clubs_logs', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 获取俱乐部等级及经验
	 */
	public function get_club_level($id='')
	{
		$sql = 'SELECT levels,experience FROM `clubs` WHERE id = ?';
		return $this->db->query($sql,array($id))->row_array();
	}
	/**
	 * 获取经验所在等级
	 */
	public function get_level($experience='')
	{
		// $sql = 'SELECT
		// 			id
		// 		FROM
		// 			`clubs_level`
		// 		WHERE
		// 			max_experience >= ?
		// 		AND min_experience <= ?
		// 		LIMIT 1';
		$sql = 'SELECT
					*
				FROM
					`clubs_level`
				WHERE
					max_experience >= ?
				ORDER BY
					max_experience ASC
				LIMIT 1';
		$res = $this->db->query($sql,array($experience))->row_array();
		return $res['id'];
	}
	/**
	 * 更新俱乐部等级
	 */
	public function update_club_level($club_id='',$levels='')
	{
		$sql = 'UPDATE `clubs`
				SET levels = ?
				WHERE
					id = ?';
		$this->db->query($sql,array($levels,$club_id));
		return $this->db->affected_rows();
	}
	/**
	 * 获取个人积分规则
	 */
	public function get_integral_rule($type='')
	{
		$sql = 'SELECT
					*
				FROM
					`sys_integral`
				WHERE
					type = ?
				AND `status` = 1';
		return $this->db->query($sql,array($type))->row_array();
	}
	/**
	 * 积分添加
	 */
	public function add_user_integral($userid='',$integral='')
	{
		$sql = "UPDATE `giant_users`
				SET integral = integral + $integral
				WHERE
					userid = $userid";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	/**
	 * 增加用户积分日志
	 */
	public function add_user_integral_logs($data='')
	{
		$this->db->trans_start();
		$this->db->insert('user_interlog', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) 
		{
			return $this->db->error();
		} 
		else 
		{
			return 1;
		}
	}
	/**
	 * 检查获取积分周期内次数是否已满
	 * @param int $userid 
	 * @param string $cycle_unit 周期单位 day,week,month,year
	 * @param int $cycle 周期内频率 
	 * @param string $type 经验类型 
	 * @return bool 未满返回TRUE 满返回FALSE
	 */
	public function check_user_integral_cycle($userid='',$cycle_unit,$cycle,$max_times,$type)
	{
		// $end_time = time();
		$day = date('Y-m-d',time());
		$end_time = strtotime($day.' 23:59:59');
		switch ($cycle_unit) {
			case 'day':
				$start_time = $end_time - 3600*24*$cycle;
				break;
			case 'week':
				$start_time = $end_time - 3600*24*7*$cycle;
				break;
			case 'month':
				$start_time = $end_time - 3600*24*30*$cycle;
				break;
			case 'year':
				$start_time = $end_time - 3600*24*365*$cycle;
				break;
			case 'all':
				$start_time = 0;
				break;
			default:
				$start_time = $end_time - 3600*24*$cycle;
				break;
		}
		$sql = 'SELECT
					COUNT(*) AS num
				FROM
					`user_interlog`
				WHERE
					userid = ?
				AND isadd = 1
				AND cdate >= ?
				AND cdate <= ?
				AND type = ?';
		$res = $this->db->query($sql,array($userid,$start_time,$end_time,$type))->row_array();
		return $res['num'] < $max_times;
	}
	public function check_user_signin_cycle($userid='',$cycle_unit,$cycle,$max_times,$type)
	{
		// $end_time = time();
		$day = date('Y-m-d',time());
		$end_time = strtotime($day.' 23:59:59');
		switch ($cycle_unit) {
			case 'day':
				$start_time = $end_time - 3600*24*$cycle;
				break;
			case 'week':
				$start_time = $end_time - 3600*24*7*$cycle;
				break;
			case 'month':
				$start_time = $end_time - 3600*24*30*$cycle;
				break;
			case 'year':
				$start_time = $end_time - 3600*24*365*$cycle;
				break;
			default:
				$start_time = $end_time - 3600*24*$cycle;
				break;
		}
		$sql = 'SELECT
					COUNT(*) AS num
				FROM
					`sys_signinlo`
				WHERE
					userid = ?
				AND cdate >= ?
				AND cdate <= ?';
		$res = $this->db->query($sql,array($userid,$start_time,$end_time))->row_array();
		return $res['num'] < $max_times;
	}
	/**
	 * 俱乐部是否获得经验
	 * @param $club_id 俱乐部id
	 * @param $type 经验类型
	 * @param $content 经验日志 内容
	 * @param $cdate 时间
	 */
	public function club_experience($club_id='',$type='',$content='',$cdate='')
	{
		$rule = $this->get_sys_exp($type);
		if(!empty($rule))//规则存在
		{
			if($rule['islimit'] == 0)//不限制
			{
				//俱乐部获得经验
				if( $this->add_club_exp($club_id,$rule['score']) != 1 )
				{
					echo "<script>console.log('经验添加失败');</script>";
				}
				else
				{
					echo "<script>console.log('俱乐部获得经验:".$rule['score']."');</script>";
				}
				//经验日志记录
				$club_exp_logs = array(
									'club_id' => $club_id,
									'type' => $type,
									'experience' => $rule['score'],
									'isadd' => '1',
									'content' => $content,
									'cdate' => $cdate,
									);
				$exp_log_res =  $this->add_club_exp_logs($club_exp_logs);
				if($exp_log_res === 1)
				{
					echo "<script>console.log('经验日志记录成功');</script>";
				}
				else
				{
					echo "<script>console.log('经验日志记录失败:".$exp_log_res."');</script>";
				}
				//检查俱乐部是否升级
				$club_level_info = $this->get_club_level($club_id);
				//获取俱乐部经验所在等级id
				$level_id = $this->get_level($club_level_info['experience']);
				//如果等级改变，更新俱乐部等级id
				if($club_level_info['levels'] != $level_id)
				{
					if($this->update_club_level($club_id,$level_id) != 1)
					{
						echo "<script>console.log('等级更新失败');</script>";
					}
					else
					{
						echo "<script>console.log('等级更新为".$level_id."');</script>";
					}
					//俱乐部日志--等级更新
					$club_logs = array(
									'club_id' => $club_id,
									'type' => 'level_up',
									'userid' => 0,
									'username' => '',
									'content' => '等级更新',
									'cdate' => $cdate,
									);
					$club_log_res =  $this->add_club_logs($club_logs);
					if($club_log_res === 1)
					{
						echo "<script>console.log('俱乐部日志--等级更新');</script>";
					}
					else
					{
						echo "<script>console.log('俱乐部日志--等级更新添加失败:".$club_log_res."');</script>";
					}
				}

			}
			if($rule['islimit'] == 1)//限制
			{
				//检查次数是否已满
				$bool = $this->check_club_exp_cycle($club_id,$rule['cycle_unit'],$rule['cycle'],$rule['max_times'],$type);
				if($bool)//次数没满
				{
					//俱乐部获得经验
					if( $this->add_club_exp($club_id,$rule['score']) != 1 )
					{
						echo "<script>console.log('经验添加失败');</script>";
					}
					else
					{
						echo "<script>console.log('俱乐部获得经验:".$rule['score']."');</script>";
					}
					//经验日志记录
					$club_exp_logs = array(
										'club_id' => $club_id,
										'type' => $type,
										'experience' => $rule['score'],
										'isadd' => '1',
										'content' => $content,
										'cdate' => $cdate,
										);
					$exp_log_res =  $this->add_club_exp_logs($club_exp_logs);
					if($exp_log_res === 1)
					{
						echo "<script>console.log('经验日志记录成功');</script>";
					}
					else
					{
						echo "<script>console.log('经验日志记录失败:".$exp_log_res."');</script>";
					}
					//检查俱乐部是否升级
					$club_level_info = $this->get_club_level($club_id);
					//获取俱乐部经验所在等级id
					$level_id = $this->get_level($club_level_info['experience']);
					//如果等级改变，更新俱乐部等级id
					if($club_level_info['levels'] != $level_id)
					{
						if($this->update_club_level($club_id,$level_id) != 1)
						{
							echo "<script>console.log('等级更新失败');</script>";
						}
						else
						{
							echo "<script>console.log('等级更新为".$level_id."');</script>";
						}
						//俱乐部日志--等级提升
						$club_logs = array(
										'club_id' => $club_id,
										'type' => 'level_up',
										'userid' => 0,
										'username' => '',
										'content' => '等级更新',
										'cdate' =>$cdate,
										);
						$club_log_res =  $this->add_club_logs($club_logs);
						if($club_log_res === 1)
						{
							echo "<script>console.log('俱乐部日志--等级更新');</script>";
						}
						else
						{
							echo "<script>console.log('俱乐部日志--等级更新添加失败:".$club_log_res."');</script>";
						}
					}
				}
				else//次数已满
				{
					echo "<script>console.log('经验获得次数已满');</script>";
				}
			}
		}
	}
	public function club_exp_achieve_num($club_id='',$type='')
	{
		$cdate = time();
		$club_member_num = $this->get_club_members_num($club_id);
		$sql = 'SELECT * FROM `sys_expe` WHERE type = ? AND `status` = 1 AND people <= ? LIMIT 1';
		$rule =  $this->db->query($sql,array($type,$club_member_num))->row_array();
		if(empty($rule))//规则不存在
		{
			return FALSE;
		}
		//检查经验记录看是否获取过
		$sql = "SELECT * FROM `clubs_expelog` WHERE club_id = ? AND content = '达到".$rule['people']."人'";
		$res = $this->db->query($sql,array($club_id))->row_array();
		if(empty($res))
		{
			//俱乐部获得经验
			if( $this->add_club_exp($club_id,$rule['score']) != 1 )
			{
				echo "<script>console.log('经验添加失败');</script>";
			}
			else
			{
				echo "<script>console.log('俱乐部获得经验:".$rule['score']."');</script>";
			}
			//经验日志记录
			$club_exp_logs = array(
								'club_id' => $club_id,
								'type' => $type,
								'experience' => $rule['score'],
								'isadd' => '1',
								'content' => '达到'.$rule['people'].'人',
								'cdate' => $cdate,
								);
			$this->add_club_exp_logs($club_exp_logs);
			//检查俱乐部是否升级
			$club_level_info = $this->get_club_level($club_id);
			//获取俱乐部经验所在等级id
			$level_id = $this->get_level($club_level_info['experience']);
			//如果等级改变，更新俱乐部等级id
			if($club_level_info['levels'] != $level_id)
			{
				if($this->update_club_level($club_id,$level_id) != 1)
				{
					echo "<script>console.log('等级更新失败');</script>";
				}
				else
				{
					echo "<script>console.log('等级更新为".$level_id."');</script>";
				}
				//俱乐部日志--等级提升
				$club_logs = array(
								'club_id' => $club_id,
								'type' => 'level_up',
								'userid' => 0,
								'username' => '',
								'content' => '等级更新',
								'cdate' =>$cdate,
								);
				$club_log_res =  $this->add_club_logs($club_logs);
				if($club_log_res === 1)
				{
					echo "<script>console.log('俱乐部日志--等级更新');</script>";
				}
				else
				{
					echo "<script>console.log('俱乐部日志--等级更新添加失败:".$club_log_res."');</script>";
				}
			}
		}
	}
	/**
	 * 获取俱乐部成员人数（已审核）
	 */
	public function get_club_members_num($id='')
	{
		$sql = 'SELECT
					COUNT(*) AS num
				FROM
					`clubs_users`
				WHERE
					club_id = ?
				AND `status` = 1';
		$res = $this->db->query($sql,array($id))->row_array();
		return $res['num'];
	}
	/**
	 * 个人是否获取积分
	 * @param $userid
	 * @param $type 积分类型
	 * @param $content 积分日志 内容
	 * @param $cdate 时间
	 */
	public function user_integral($userid='',$type='',$content='',$cdate='',$username='')
	{
		//检查个人是否获得积分--待定
		$integral_rule = $this->get_integral_rule($type);
		if(!empty($integral_rule))//规则存在
		{
			if($integral_rule['islimit'] == 0)//不限制
			{
				//添加积分 userid
				$this->add_user_integral($userid,$integral_rule['score']);
				/*if( $this->add_user_integral($userid,$integral_rule['score']) != 1)
				{
					echo "<script>console.log('积分添加失败');</script>";
				}
				else
				{
					echo "<script>console.log('获得积分:".$integral_rule['score']."');</script>";
				}*/
				//个人积分日志
				$user_integral_logs = array(
										'userid' => $userid,
										'username' => $username,
										'type' => $type,
										'score' => $integral_rule['score'],
										'isadd' => 1,
										'content' => $content,
										'cdate' => $cdate,
										);
				$this->add_user_integral_logs($user_integral_logs);
				return $integral_rule['score'];
			}
			else//限制
			{
				//检查次数是否已满
				// if($type == 'sign_in')
				// {
				// 	$bool = $this->check_user_signin_cycle($userid,$integral_rule['cycle_unit'],$integral_rule['cycle'],$integral_rule['max_times'],$type);
				// }
				// else
				// {
				// 	$bool = $this->check_user_integral_cycle($userid,$integral_rule['cycle_unit'],$integral_rule['cycle'],$integral_rule['max_times'],$type);
				// }
				$bool = $this->check_user_integral_cycle($userid,$integral_rule['cycle_unit'],$integral_rule['cycle'],$integral_rule['max_times'],$type);
				if($bool)//次数没满
				{
					//个人获得积分
					$this->add_user_integral($userid,$integral_rule['score']);
					/*if($this->add_user_integral($userid,$integral_rule['score']) != 1 )
					{
						echo "<script>console.log('积分添加失败');</script>";
					}
					else
					{
						echo "<script>console.log('获得积分:".$integral_rule['score']."');</script>";
					}*/
					//个人积分日志
					$user_integral_logs = array(
											'userid' => $userid,
											'username' => $username,
											'type' => $type,
											'score' => $integral_rule['score'],
											'isadd' => 1,
											'content' => $content,
											'cdate' => $cdate,
											);
					$this->add_user_integral_logs($user_integral_logs);
					return $integral_rule['score'];
					
				}
				else//次数已满
				{
					// echo "<script>console.log('积分获得次数已满');</script>";
					return FALSE;
				}
			}
		}
		else
		{
			return FALSE;
		}
	}
	/**
	 * 检查关键字
	 * @param $str 要检查的字符串
	 * @return 如果包含关键字 返回关键字 否则 返回TRUE
	 */
	public function check_keyword($str='')
	{
		$keyword_arr = $this->get_sys_keywords();
		foreach ($keyword_arr as $obj) 
		{
			if( stripos($str,$obj['keywords']) !== FALSE)
			{
				return $obj['keywords'];
			}
		}
		return TRUE;
	}
	/**
	 * 调用sina接口--根据ip获取城市名称
	 */
	public function get_city_name()
	{
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$_SERVER['REMOTE_ADDR'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		$res = curl_exec($curl);
		curl_close($curl);
		$info = json_decode($res,TRUE);
		if($info['ret'] == 1)
		{
			if($info['district'] != '')
				$area = $info['district'];
			elseif($info['city'] != '')
				$area = $info['city'];
			elseif($info['province'] != '')
				$area = $info['province'];
			else
				$area = '';
		}
		else
		{
			$area = '';
		}
		return $area;
	}
	/**
	 * 上传图片默认方法
	 */
	public function upload_img($img='',$route='uploads/index/')
	{
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
			// 生成的文件名--规定要写入数据的文件。如果文件不存在，则创建一个新文件。 
			$photo = $route.time().mt_rand(1,10000000).$ext; 
			// 生成文件,上传 ,返回写入到文件内数据的字节数
			if(file_put_contents($photo, base64_decode($dataimg), true) > 0)
			{
				return base_url().$photo;
			}
			else
			{
				return '';
			}

		}
		else
		{
			return '';
		}
	}

	public function cjp_upload_img($img='',$route='uploads/index/')
	{
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
			// 生成的文件名--规定要写入数据的文件。如果文件不存在，则创建一个新文件。 
			$photo = $route.time().mt_rand(1,10000000).$ext; 
			// 生成文件,上传 ,返回写入到文件内数据的字节数
			if(file_put_contents($photo, base64_decode($dataimg), true) > 0)
			{
				return $photo;
			}
			else
			{
				return '';
			}

		}
		else
		{
			return '';
		}
	}


	/**
	 * 富文本框上传图片--AJAX方法
	 */
	public function upload_ajax($img = '',$route = 'uploads/index/')
	{
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
			$photo = $route.time().mt_rand(1,10000000).$ext; 
			// 生成文件,上传 ,返回写入到文件内数据的字节数
			if(file_put_contents($photo, base64_decode($dataimg), true) > 0)
			{
				$json['state'] = 1;
				$json['msg'] = base_url().$photo;
				echo json_encode($json);
			}
			else
			{
				$json['state'] = 0;
				$json['msg'] = '上传失败';
				echo json_encode($json);
			}

		}
		else
		{
			$json['state'] = 0;
			$json['msg'] = '上传失败';
			echo json_encode($json);
		}
	}
	/**
	 * 判断用户是否是俱乐部管理层
	 * 是 返回TRUE 
	 */
	public function is_club_manage($club_id='',$userid)
	{
		$sql = 'SELECT
					*
				FROM
					`clubs_users`
				WHERE
					club_id = ?
				AND userid = ?
				AND type IN (1, 2)';
		$res = $this->db->query($sql,array($club_id,$userid))->row_array();
		return !empty($res);
	}
	public function is_first_login($userid='')
	{
		$day = date('Y-m-d',time());
		$start_time = strtotime($day.' 00:00:00');
		$end_time = strtotime($day.' 23:59:59');
		$sql = 'SELECT * FROM `user_logs` WHERE userid = ? AND type = "login" AND cdate >= ? AND cdate <= ?';
		$res = $this->db->query($sql,array($userid,$start_time,$end_time))->result_array();
		return empty($res);
	}
	/**
	 * 获取我的粉丝列表
	 */
	public function get_my_fans($userid='')
	{
		$sql = 'SELECT * FROM `user_friend` WHERE t_userid = ?';
		return $this->db->query($sql,array($userid))->result_array();
	}
}	