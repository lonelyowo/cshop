<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Init_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->get_site_info();
        $this->add_pv();
    }

    public function get_site_info()
    {
    	if (!isset($_SESSION['site_info'])) {
    		$_SESSION['site_info'] = $this->db->where('id',1)->get('admin_site_info')->row_array();
    	}
    }

    // page view
    public function add_pv()
    {
        $sql = "UPDATE site_count
                SET pv = pv + 1
                WHERE
                    id = 1
                LIMIT 1";
        $this->db->query($sql);
    }

}