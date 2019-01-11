<?php
/*admin
 * 2016-11-28
 * 外部应用加解密demo
 * */
class webApp{
    #密码必须经过sha1 加密
    #金额不会用小数点表示 以分为单位
    #参数名称及加密后字符，默认使用小写，除非有特别约定的地方会有特殊说明
    #所有接口请求数据必须要传open_id,timestamp,sign,data 四个参数，data 参数是aes 加密后的json 数据(为用户要提交的请求数据)。
    const DEBUG				=true;

    const open_url ='https://api.orangebank.com.cn/mct1/';//外网测试地址

    const open_id ='4f1d0e4a1908b203646c2a6a102b6e36'; //测试open_id 测试商户5
    const open_key ='d812d73487a3f9f94b932b48aa0f704c';//测试open_key 测试商户5
    const private_key = 'file/880000108_private_key.key'; //测试商户5 私钥地址


    #签名
    public function signs($array){
        $signature = array();
        foreach($array as $key=>$value){
            $signature[$key]=$key.'='.$value;
        }
        $signature['open_key']='open_key'.'='.self::open_key;
        ksort($signature);



        #先sha1加密 在md5加密
        $sign_str = md5(sha1(implode('&', $signature)));
        return $sign_str;
    }

    public function signs_ras($array,$signauture=OPENSSL_ALGO_SHA1){
        $signature = array();
        foreach($array as $key=>$value){
            $signature[$key]=$key.'='.$value;
        }
        $signature['open_key']='open_key'.'='.self::open_key;
        ksort($signature);
        $sign_str = implode('&', $signature);
        $privatekey = openssl_get_privatekey(file_get_contents(self::private_key));
        openssl_sign($sign_str,$sign_str,$privatekey,$signauture);
        return bin2hex($sign_str);
    }

    #使用post的传输
    public function api($url,$post){
        #必填参数

		$data['open_id']		=self::open_id;
		$data['timestamp']		=time();


      
        $data['data']= $post;
        unset($data['sign_type']);
        if(self::DEBUG){if($data){$this->debug('接口调用：'.$url,http_build_query($data));}else{$this->debug('接口调用：'.$url,'');}}
        if(is_array($data)){
            if(self::DEBUG){$this->debug('加密前字符串',json_encode($data));}
            $data['data'] = $this->encrypt(json_encode($post),self::open_key);
            if ($url == 'payrefund' || $url == 'paycancel'){
                $data['sign_type'] = $post['sign_type'];
                if ($post['sign_type']=='RSA'){
                    $data['sign'] = $this->signs_ras($data);
                }elseif ($post['sign_type']=='RSA2'){
                    $data['sign'] = $this->signs_ras($data,OPENSSL_ALGO_SHA256);
                }

            }else{
                $data['sign'] = $this->signs($data);

            }
            if(self::DEBUG){$this->debug('加密后字符串',json_encode($data));}
        }else{
            $data=null;
        }
        $result = $this->CURL($url,$data);
        if(isset($result['data'])){
            if(self::DEBUG){$this->debug('解密前字符串',$result['data']);}
            $result['data']=$this->decrypt($result['data'], self::open_key);
            if(self::DEBUG){$this->debug('解密后字符串',$result['data']);}
            $result['data']=json_decode($result['data'],true);
            if(self::DEBUG){if(is_array($result['data'])){$this->debug('JSON转数组成功','成功');}else{$this->debug('JSON转数组成功','失败');}}
        }
        unset($result['sign']);
        return $result;

    }

    #使用post的传输
    public function CURL($url,$data){
        //启动一个CURL会话
        $ch = curl_init();
        // 设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        //忽略证书
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        // 获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL,self::open_url.$url);
        //发送一个常规的POST请求。
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_HEADER,0);//是否需要头部信息（否）
        // 执行操作
        $result = curl_exec($ch);

        if(self::DEBUG){
            $this->debug('接口返回数据',$result);
        }
        if($result){
            curl_close($ch);
            #将返回json转换为数组
            $arr_result=json_decode($result,true);
            if(!is_array($arr_result)){
                $arr_result['errcode']=1;
                $arr_result['msg']='服务器繁忙，请稍候重试';
                if(self::DEBUG){
                    $this->debug('服务器返回数据格式错误',$result);
                }

            }
        }else{
            $err_str=curl_error($ch);
            curl_close($ch);
            $arr_result['errcode']=1;
            $arr_result['msg']='服务器繁忙，请稍候重试';
            if(self::DEBUG){
                $this->debug('服务器无响应',$err_str);
            }
        }
        #返回数据
        return $arr_result;

    }

    #@todo AES加解密
    #加密
    public static function encrypt($input, $key) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = self::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = strtoupper(bin2hex($data));
        return $data;
    }
    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    //解密
    public static function decrypt($sStr, $sKey) {
        $sStr=hex2bin($sStr);
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $sKey,
            $sStr,
            MCRYPT_MODE_ECB
        );

        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }

    #日志记录
    protected function debug($tempType,$tempStr){
        $log_name = 'file/log.txt';
        $tempStr=date('Y-m-d H:i:s').' '.$tempType."\r\n".$tempStr."\r\n\r\n";
        $myfile = fopen($log_name, "a");
      //  fwrite($myfile, $tempStr);
       // fclose($myfile);
    }
}

