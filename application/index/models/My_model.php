<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* @author liqingbao
*/
class My_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	/**
	*获取个人信息
	*/
	public function get_info($userid='')
	{
		$sql = 'SELECT * FROM `giant_users` WHERE `userid` = ?';
		return $this->db->query($sql,array($userid))->row_array();
	}
	/**
	*保存个人信息
	*/
	public function save_info($data)
	{
		$this->db->trans_start();
		$this->db->where('userid', $data["userid"]);
		$this->db->update('giant_users', $data); 
		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) 
        {
            return $this->db->error();;
        } 
        else 
        {
            return 1;
        }
	}
	public function get_integral_logs_num($userid='')
	{
		$sql = 'SELECT count(*) AS num FROM `user_interlog` WHERE `userid` = ?';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取积分记录
	*/
	public function get_integral_logs($userid='',$offset,$per_page)
	{
		$sql = 'SELECT * FROM `user_interlog` WHERE `userid` = ? ORDER BY `id` DESC LIMIT ?,?';
		return $this->db->query($sql,array($userid,$offset,$per_page))->result_array();
	}
	/**
	*获取积分排名百分比
	*/
	public function get_integral_ranking($integral='')
	{
		//获取总人数
		$sql = 'SELECT COUNT(*) AS num FROM `giant_users`';
		$res = $this->db->query($sql)->row_array();
		$allnum = $res['num'];
		//获取超过的人数
		$sql = 'SELECT COUNT(*) AS num FROM `giant_users` WHERE integral < ?';
		$res = $this->db->query($sql,array($integral))->row_array();
		$num = $res['num'];
		//返回排名百分比
		$ranking = round($num/$allnum,2)*100;
		return $ranking.'%';
	}
	public function get_management_activities_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					(
						SELECT
							*
						FROM
							activity
						WHERE
							club_id IN (
								SELECT
									club_id
								FROM
									`clubs_users`
								WHERE
									`userid` = ?
								AND `type` IN (1, 2)
								AND STATUS = 1
							)
						AND isdel = 0
						UNION
							SELECT
								*
							FROM
								activity
							WHERE
								userid = ?
							AND isclub = 0
							AND isdel = 0
					) AS a';
		$res = $this->db->query($sql,array($userid,$userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我管理的活动，从activity表
	* status 1:开启 0:关闭    AND `status` = 1
	* isdel 1:已删除 0:未删除
	*/
	public function get_management_activities($userid='',$offset,$per_page)
	{
		$sql = 'SELECT
					a.*, b.nickname,
					b.avatar,
					c.`name`,
					c.logos,(SELECT count(*) as num FROM `orders` WHERE activity_id = a.id AND order_status IN (0,1,2,3)) AS orders_num
				FROM
					(
						SELECT
							*
						FROM
							activity
						WHERE
							club_id IN (
								SELECT
									club_id
								FROM
									`clubs_users`
								WHERE
									`userid` = ?
								AND `type` IN (1, 2)
								AND status = 1
							)

						AND isdel = 0
					) AS a
				JOIN giant_users AS b USING (userid)
				LEFT JOIN clubs AS c ON a.club_id = c.id
				UNION
					SELECT
						d.*, e.nickname,
						e.avatar,
						f.`name`,
						f.logos,(SELECT count(*) as num FROM `orders` WHERE activity_id = d.id AND order_status IN (0,1,2,3)) AS orders_num
					FROM
						(
							SELECT
								*
							FROM
								activity
							WHERE
								userid = ?
							AND isclub = 0
							
							AND isdel = 0
						) AS d
					JOIN giant_users AS e USING (userid)
					LEFT JOIN clubs AS f ON d.club_id = f.id
				ORDER BY `id` DESC LIMIT ?,?';
		return $this->db->query($sql,array($userid,$userid,$offset,$per_page))->result_array();
	}
	public function get_apply_activities_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					activity
				WHERE
					id IN (
						SELECT
							activity_id
						FROM
							`orders`
						WHERE
							`userid` = ?
					)
				AND status = 1
				AND isdel = 0';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我报名的活动，从order表
	*/
	public function get_apply_activities($userid='',$offset,$per_page)
	{
		$sql = 'SELECT
					a.*, b.nickname,
					b.avatar,
					c.`name`,
					c.logos,(SELECT count(*) as num FROM `orders` WHERE activity_id = a.id AND order_status IN (0,1,2,3)) AS orders_num
				FROM
					(
						SELECT
							*
						FROM
							activity
						WHERE
							id IN (
								SELECT
									activity_id
								FROM
									`orders`
								WHERE
									`userid` = ?
							)
						AND status = 1
						AND isdel = 0
					) AS a
				JOIN giant_users AS b USING (userid)
				LEFT JOIN clubs AS c ON a.club_id = c.id
				ORDER BY
					`id` DESC
				LIMIT ?,?';
		return $this->db->query($sql,array($userid,$offset,$per_page))->result_array();
	}
	public function get_management_clubs_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					clubs 
				WHERE
					id IN (
						SELECT club_id FROM `clubs_users` WHERE `userid` = ? AND `type` IN (1,2)
					)
				AND `status` = 1
				AND isdel = 0';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我管理的俱乐部，从clubs_users表
	* clubs_users  type 0：普通会员 1：管理员 2：会长
	* clubs  isdel 1-已删除 0-正常
	*/
	public function get_management_clubs($userid='',$offset,$per_page)
	{
		$sql = 'SELECT b.*,c.pic FROM (
					SELECT
						a.*, (
							SELECT
								COUNT(*)
							FROM
								`clubs_users`
							WHERE
								club_id = a.id
							AND `status` = 1
						) AS people_num
					FROM
						clubs AS a
					WHERE
						id IN (
							SELECT club_id FROM `clubs_users` WHERE `userid` = ? AND `type` IN (1,2)
						)
					AND a.`status` = 1
					AND a.isdel = 0 ORDER BY `id` DESC LIMIT ?,? 
				) AS b LEFT JOIN clubs_level AS c ON b.levels=c.id';
		return $this->db->query($sql,array($userid,$offset,$per_page))->result_array();
	}
	public function get_join_clubs_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					clubs 
				WHERE
					id IN (
						SELECT club_id FROM `clubs_users` WHERE `userid` = ? AND `type` = 0
					)
				AND `status` = 1
				AND isdel = 0';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我加入的俱乐部，从clubs_users表
	* clubs_users  type 0：普通会员 1：管理员 2：会长
	* clubs  isdel 1-已删除 0-正常
	*/
	public function get_join_clubs($userid='',$offset,$per_page)
	{
		$sql = 'SELECT b.*,c.pic FROM (
					SELECT
						a.*, (
							SELECT
								COUNT(*)
							FROM
								`clubs_users`
							WHERE
								club_id = a.id
							AND `status` = 1
						) AS people_num
					FROM
						clubs AS a
					WHERE
						id IN (
							SELECT club_id FROM `clubs_users` WHERE `userid` = ? AND `type` IN (0)
						)
					AND a.`status` = 1
					AND a.isdel = 0 ORDER BY `id` DESC LIMIT ?,? 
				) AS b LEFT JOIN clubs_level AS c ON b.levels=c.id';
		return $this->db->query($sql,array($userid,$offset,$per_page))->result_array();
	}
	public function get_travels_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					`travels`
				WHERE
					`userid` = ?
				AND `isdel` = 0';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我的游记
	*status 0：待审核  1：审核通过  2：审核不通过
	*isdel 1:删除 0:正常
	*/
	public function get_travels($userid='',$offset,$per_page)
	{
		$sql = 'SELECT
					a.*, b.avatar,
					b.nickname
				FROM
					(
						SELECT
							*
						FROM
							`travels`
						WHERE
							`userid` = ?
						AND `isdel` = 0
					) AS a
				JOIN giant_users AS b USING (userid)
				ORDER BY
					`id` DESC LIMIT ?,?';
		return $this->db->query($sql,array($userid,$offset,$per_page))->result_array();
	}
	public function get_friends_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					`user_friend` 
				WHERE
					f_userid = ?';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我的好友--
	*/
	public function get_friends($userid='',$offset,$per_page)
	{
		/*$sql = 'SELECT * FROM 
				(SELECT * FROM `user_friend` WHERE `f_userid` = ? AND `status` = 1) AS a  
				JOIN `giant_users` AS b ON a.t_userid = b.userid 
				UNION
				SELECT * FROM 
				(SELECT * FROM `user_friend` WHERE `t_userid` = ? AND `status` = 1) AS c  
				JOIN `giant_users` AS d ON c.f_userid = d.userid
				ORDER BY id DESC';
		return $this->db->query($sql,array($userid,$userid))->result_array();*/
		$sql = 'SELECT
					b.*, c.avatar,
					c.nickname,c.sex
				FROM
					(
						SELECT
							*, (
								SELECT
									id
								FROM
									`user_friend`
								WHERE
									f_userid = a.t_userid
								AND t_userid = ?
							) AS is_friend
						FROM
							`user_friend` AS a
						WHERE
							a.f_userid = ?
						LIMIT ?,?
					) AS b
				LEFT JOIN giant_users AS c ON b.t_userid = c.userid';
		return $this->db->query($sql,array($userid,$userid,$offset,$per_page))->result_array();
	}
	public function get_fans_num($userid='')
	{
		$sql = 'SELECT
					count(*) AS num
				FROM
					`user_friend`
				WHERE
					t_userid = ?';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['num'];
	}
	/**
	*获取我的粉丝--
	*/
	public function get_fans($userid='',$offset,$per_page)
	{
		$sql = 'SELECT
					b.*, c.avatar,
					c.nickname,c.sex
				FROM
					(
						SELECT
							*, (
								SELECT
									id
								FROM
									`user_friend`
								WHERE
									t_userid = a.f_userid
								AND f_userid = ?
							) AS is_friend
						FROM
							`user_friend` AS a
						WHERE
							a.t_userid = ?
						LIMIT ?,?
					) AS b
				LEFT JOIN giant_users AS c ON b.f_userid = c.userid';
		return $this->db->query($sql,array($userid,$userid,$offset,$per_page))->result_array();
	}
	/**
	*获取我管理的俱乐部，从clubs_users表
	* clubs_users  type 0：普通会员 1：管理员 2：会长
	* clubs  isdel 1-已删除 0-正常
	*/
	public function get_my_clubs($userid='')
	{
		$sql = 'SELECT
					a.type,
					b.id,
					b.name,
					b.logos,
					b.levels,
					b.experience,
					c.`name` AS levelname,
					c.pic,
					b.shopno,
					b.slogan,
					(
						SELECT
							COUNT(*)
						FROM
							clubs_users
						WHERE
							club_id = b.id
						AND `status` = 1
					) AS people_num
				FROM
					(
						SELECT
							*
						FROM
							`clubs_users`
						WHERE
							`userid` = ?
						ORDER BY
							type DESC
						LIMIT 3
					) AS a
				JOIN clubs AS b ON a.club_id = b.id
				JOIN clubs_level AS c ON b.levels = c.id
				WHERE
					b.isdel = 0
				ORDER BY
					`type` DESC';
		return $this->db->query($sql,array($userid))->result_array();
	}
	/**
	 * 获取我的积分
	 */
	public function get_my_integral($userid='')
	{
		$sql = 'SELECT integral FROM `giant_users` WHERE userid = ?';
		$res = $this->db->query($sql,array($userid))->row_array();
		return $res['integral'];
	}
	/**
	 * 判断当日是否签到
	 */
	public function is_signin($userid='')
	{
		$day = date('Y-m-d',time());
		$start_time = strtotime($day.' 00:00:00');
		$end_time = strtotime($day.' 23:59:59');
		$sql = 'SELECT * FROM `sys_signinlo` WHERE userid = ? AND cdate >= ? AND cdate <= ?';
		$res = $this->db->query($sql,array($userid,$start_time,$end_time))->result_array();
		return empty($res);
	}
	/**
	 * 是否关注
	 */
	public function is_follow($f_userid='',$t_userid='')
	{
		$sql = 'SELECT * FROM `user_friend` WHERE f_userid = ? AND t_userid = ?';
		$res = $this->db->query($sql,array($f_userid,$t_userid))->row_array();
		return !empty($res);
	}
	/**
	 * 关注
	 */
	public function follow($data='')
	{
		$this->db->trans_start();
		$this->db->insert('user_friend', $data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE) 
		{
			return 0;
		} 
		else 
		{
			return $id;
		}
	}
	/**
	 * 取消关注
	 */
	public function unfollow($f_userid='',$t_userid='')
	{
		$sql = 'DELETE FROM `user_friend` WHERE f_userid = ? AND t_userid = ?';
		$this->db->query($sql,array($f_userid,$t_userid));
		return $this->db->affected_rows();
	}
	/**
	 * 判断是否有新消息
	 */
	public function check_hasnew()
	{
		if(!isset($_SESSION['login']))
			return FALSE;
		$sql = 'SELECT COUNT(*) AS num FROM `user_msg` WHERE userid = ? AND isread = 0';
		$res = $this->db->query($sql,array($_SESSION['user']['id']))->row_array();
		return $res['num'] != 0;
	}
}