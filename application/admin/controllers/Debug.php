<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$a = '{
                "status": 1,
                "msg": "ok",
                "data": [
                            {
                                "id": 1,
                                "logo": "./demo/img/club_01.jpg",
                                "name": "昆山勇者无畏",
                                "star": 0,
                                "people": 1542
                            },
                            {
                                "id": 2,
                                "logo": "./demo/img/club_01.jpg",
                                "name": "昆山勇者无畏",
                                "star": 1,
                                "people": 1542
                            }
                        ]
            }';
    $code = urldecode(json_encode(urlencode("厦门")));
    var_dump($code);
	}

	public function sess()
	{
		var_dump($_SESSION);
	}

	public function cls()
	{
		session_unset();
		session_destroy();
	}


}