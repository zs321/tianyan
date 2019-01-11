<?php
class ClientResponseHandler {
	/** 密钥 */
	var $key;
	
	/** 请求的参数 */
	var $parameters;
	
	function __construct() {
		$this->ClientResponseHandler();
	}
	
	function ClientResponseHandler($expire = 600) {
		ini_set('session.gc_maxlifetime', $expire);
		session_set_cookie_params($expire);
 		session_start();

		$this->key = "";
		$this->parameters = array();
	}

	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/
	function setKey($key) {
		$this->key = $key;
	}

	/**
	*获取参数值
	*/
	function getParameter($parameter) {
		return isset($this->parameters[$parameter])?$this->parameters[$parameter]:'';
	}
	
	/**
	*设置参数值
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}

	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}

	/**
	*接口签名方法
	*/
	function szSign()
	{
		$data = $this->getAllParameters();
	    foreach ($data as $k => $v) {
	        if (($v === "") || ($v === null) || $k === "sign") {
	            unset($data[$k]);
	        }
	    }
	    //参数按键值排序
	    ksort($data);
	    $str = "";
	    foreach ($data as $key => $val) {
	        $str .= $key . "=" . $val . "&";
	    }
	    $str = trim($str, "&") . "&key=" . $this->getKey();
	    $str = md5($str);
	    return strtoupper($str);
	}

	/**
	*设置缓存
	*/
	function setSession($parameter,$parameterValue){
 		$_SESSION[$parameter] = $parameterValue;
	}

	/**
	*取出缓存
	*/
	function getSession($parameter){
		if(!isset($_SESSION[$parameter])){
			echo '该缓存不存在或未设置缓存：'.$parameter;exit;
		}
		return $_SESSION[$parameter];
	}
}