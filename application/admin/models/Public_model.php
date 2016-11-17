<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function history_back($msg='')
    {
    	exit("
    		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    		<script>
    			alert('".$msg."');
    			history.back();
    		</script>
    		");
    }

    // 检查登陆状态
    public function check_login()
    {
        if ( !isset($_SESSION['login']) ) {
            redirect('Login/index');
        }
    }


}