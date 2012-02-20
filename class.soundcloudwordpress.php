<?php
/**
 * @author Richard Caceres <rchrd.net>
 */ 


require_once dirname(__FILE__) . '/Services/Soundcloud.php';


class SoundcloudWordpress {
	
	public $arg1              = '52fda0e16fbd5f5cf55dbcdb67dc8336';
	public $arg2              = '10f6181fc4bb1f97cb5e819bb2ededb2';
	public $redirect_url      = null;
	public $soundcloud        = null;
	
	public $access_token      = null;
	
	private $_redirect_server = 'http://dev.rchrd.net/scwp/scwp-redirect.php';
	private $_logged_in       = false;


	public function __construct() {

		/*
		 * generate url to scwp-content.php 
		 */ 
		$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		
		$file_path_parts = parse_url($_SERVER['REQUEST_URI']);
		$file_path       = $file_path_parts['path'];
		$url_to_dir      = $protocol . $_SERVER['HTTP_HOST'] . dirname($file_path);
		$url_to_file     = rtrim($url_to_dir, '/') . '/scwp-content.php';
		
		$this->redirect_url = $url_to_file;
		$this->soundcloud = new Services_Soundcloud(
			$this->arg1,
			$this->arg2,
			$this->_redirect_server
		);
		
		/*
		 * Check for logged in
		 */ 
		if(isset($_SESSION['accessToken'])) {
			
			$this->access_token = $_SESSION['accessToken'];
			$this->soundcloud->setAccessToken($this->access_token);		
			$this->_logged_in = true;
			
		}
	}
	
	
	public function login($code) {
		
	    $access_token_result = $this->soundcloud->accessToken($code);
		/*
		 * access token inside an access token
		 */
		$this->access_token = $access_token_result['access_token'];
	
		// if( ! isset($_SESSION['accessToken'])) {
		// 	session_register('accessToken');
		// }
		$_SESSION['accessToken'] = $this->access_token;
		
		$this->_logged_in = true;
		
	}
	
	public function isLoggedIn() {
		
		return $this->_logged_in;
		
	}
	
	
	
	function getHumanTime($s) {
		$m = $s / 60;
		$h = $s / 3600;
		$d = $s / 86400;
		if ($m > 1) {
			if ($h > 1) {
				if ($d > 1) {
					return (int)$d.' d';
				} else {
					return (int)$h.' h';
				}
			} else {
				return (int)$m.' m';
			}
		} else {
			return (int)$s.' s';
		}
	}
}
