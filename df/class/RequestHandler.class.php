<?php
class RequestHandler {
	/** 密钥 */
	var $key;

	/** 应答的参数 */
	var $parameters;
	
	function __construct() {
		$this->ClientResponseHandler();
	}
	
	function ClientResponseHandler() {
		$this->key = "";
		$this->parameters = array();
	}

	/**
	*设置参数值
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
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
		return isset($this->parameters[$parameter])?$this->parameters[$parameter] : '';
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
	*POST请求
	*/
	function doPost($url, $post_data,$type = 'json'){
		if ($type == 'json') {
	    	$jsonData = json_encode($post_data);
		}elseif($type == 'Array'){
			$jsonData = $post_data;
		}else{
			echo '传值类型异常';exit;
		}
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $ch = curl_exec($ch);
	    $return = json_decode($ch,true);
	    foreach ($return as $k => $v) {
	    	$this->setParameter($k, $v);
	    }
	}

	/*封装数据*/
	function setJson($str){
		$return = json_decode($str,true);
		foreach ($return as $k => $v) {
	    	$this->setParameter($k, $v);
	    }
	}

	/*封装数据*/
	function setContent($str){
		$strarr = array();
        foreach (explode('&', $str) as $t){
            list($a,$b) = explode('=', $t);
            $this->setParameter($a, $b);
        }
	}

	/*验签*/
	function isTenpaySign(){
		$checksign = $this->szSign($this->getAllParameters(),$this->getKey());
		return $checksign == $this->getParameter('sign');
	}
}