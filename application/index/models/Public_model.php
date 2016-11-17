<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author      : Chenjp
 * DateTime    : 2016-11-17
 * Description : Public_model
 */
class Public_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
    }

	/**
     * [curl get post curl通用]
     * @param  string $url  [接口地址]
     * @param  string $post [$_POST]
     * @return [type]       [json]
     */
 	public function curl($url = '', $post = array())
 	{
 		$ch = curl_init();

 		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );

 		if (!empty($post)) {
			curl_setopt ( $ch, CURLOPT_POST, TRUE );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
 		}
				
		$output = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $output;
 	}

}