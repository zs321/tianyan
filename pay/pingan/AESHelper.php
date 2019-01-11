<?php
     

        /**
         * 通过AES加密请求数据
         * 
         * @param array $query
         * @return string
         */
        function AESEncryptRequest($encryptKey, $query){
            return $this->encrypt_pass($query,$encryptKey);
            
        }
        // 加密
        function encrypt_pass($input, $key) {
			 
            $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
			 
            $input = pkcs5_pad($input, $size);
			 
            $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
            $iv = '0102030405060708';
            mcrypt_generic_init($td, $key, $iv);
            $data = mcrypt_generic($td, $input);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
			 
            $data = bin2hex($data);
            return $data;
        }
        //填充
        function pkcs5_pad ($text, $blocksize) {
            $pad = $blocksize - (strlen($text) % $blocksize);
            return $text . str_repeat(chr($pad), $pad);
        }
    
        /**
         * 通过AES解密请求数据
         * 
         * @param array $query
         * @return string
         */
        function AESDecryptResponse($encryptKey,$data){
            return $this->decrypt_pass($data,$encryptKey);
            
        }
        // 解密
        function decrypt_pass($sStr, $sKey) {
            $iv = '0102030405060708';
            $decrypted= mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                $sKey,
                hex2bin($sStr),
                MCRYPT_MODE_ECB,
                $iv
            );
            $dec_s = strlen($decrypted);
            $padding = ord($decrypted[$dec_s-1]);
            $decrypted = substr($decrypted, 0, -$padding);
            return $decrypted;
        }   
   
