<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Chenjp
 * Date: 2016/9/20
 * Time: 8:38
 */
class Index_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_site_count()
    {
        $sql = "SELECT * FROM site_count WHERE id=1 LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_site_info()
    {
        $sql = "SELECT * FROM admin_site_info WHERE id=1 LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_account(){
        $sql = "SELECT * FROM admin_account";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_cate(){
        $sql = "SELECT * FROM cate ORDER BY sort";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_cate_name_by_id($id){
        $sql = "SELECT name FROM cate WHERE id={$id} LIMIT 1";
        $query = $this->db->query($sql);
        $res = $query->row_array();
        return $res['name'];
    }

    public function get_goods_by_cid($cid='')
    {
        $sql = "SELECT * FROM goods WHERE cate_id={$cid} ORDER BY sort";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_goods(){
        $sql = "SELECT * FROM goods ORDER BY sort";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_goods_detail($id){
        $sql = "SELECT * FROM goods WHERE id={$id} LIMIT 1";
        return $this->db->query($sql)->row_array();
    }


    public function user(){
        $sql = "SELECT * FROM admin_user ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function menu(){
        $sql = "SELECT * FROM admin_menu";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function menu_by_id($id){
        $sql = "SELECT * FROM admin_menu WHERE id={$id} LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function menu_permission()
    {
        $sql = "SELECT * FROM admin_menu_permission ORDER BY id DESC";
        return $this->db->query($sql)->result_array();
    }

    public function menu_permission_by_userid($userid)
    {
        $sql = "SELECT * FROM admin_menu_permission WHERE user_id={$userid} LIMIT 1";
        $row = $this->db->query($sql)->row_array();
        if ($row) {
            return json_decode($row['json'],true);
        }
    }

    public function menu_permission_by_root()
    {
        $res = $this->menu();
        $menu = array();
        foreach ($res as $key => $row) {
            $menu[ $row['id'] ]['url'] = $row['url'];
            $menu[ $row['id'] ]['name'] = $row['name'];
        }
        return $menu;
    }


    public function get_api(){
        $sql = "SELECT * FROM admin_api ORDER BY id DESC";
        return $this->db->query($sql)->result_array();
    }

    public function get_api_detail($id){
        $sql = "SELECT * FROM admin_api WHERE id={$id} LIMIT 1";
        return $this->db->query($sql)->row_array();
    }

    public function get_article(){
        $sql = "SELECT * FROM admin_article ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_detail_article($id){
        $sql = "SELECT * FROM admin_article WHERE id={$id} LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_user()
    {
        return $this->db->get('user')->result_array();
    }

    public function get_order()
    {
        $sql = "SELECT
                    `order`.*, `user`.`name`
                FROM
                    `order`
                LEFT JOIN `user` ON `order`.user_id = USER .id";
        return $this->db->query($sql)->result_array();
    }

    public function get_cash_apply()
    {
        $sql = "SELECT
                    `cash_apply`.*, `user`.`name`
                FROM
                    `cash_apply`
                LEFT JOIN `user` ON `cash_apply`.user_id = USER .id";
        return $this->db->query($sql)->result_array();
    }

}