<?php
/**
 * Created by PhpStorm.
 * User: zhongqf
 * Date: 2017/12/22
 * Time: 下午10:57
 */

class Rsa
{
    private $_config = [
        'public_key' => '',
        'private_key' => '',
    ];

    public function __construct($private_key_filepath, $public_key_filepath) {
        $this->_config['private_key'] = $this->_getContents($private_key_filepath);
        $this->_config['public_key'] = $this->_getContents($public_key_filepath);
    }

    /**
     * @uses 获取文件内容
     * @param $file_path string
     * @return bool|string
     */
    private function _getContents($file_path) {
        file_exists($file_path) or die ('密钥文件路径错误');
        return file_get_contents($file_path);
    }

    /**
     * @uses 获取私钥
     * @return bool|resource
     */
    private function _getPrivateKey() {
        $priv_key = $this->_config['private_key'];
        return openssl_pkey_get_private($priv_key);
    }

    /**
     * @uses 获取公钥
     * @return bool|resource
     */
    private function _getPublicKey() {
        $public_key = $this->_config['public_key'];
        return openssl_pkey_get_public($public_key);
    }

    /**
     * @uses 私钥加密 / 签名
     * @param string $data
     * @return null|string
     */
    public function getSignature($data = '') {
        if (!is_string($data)) {
            return null;
        }
        //return openssl_private_encrypt($data, $encrypted, $this->_getPrivateKey()) ? base64_encode($encrypted) : null;
        return openssl_sign($data, $encrypted, $this->_getPrivateKey(), OPENSSL_ALGO_SHA256) ? base64_encode($encrypted) : null;
    }

    /**
     * @uses 公钥加密
     * @param string $data
     * @return null|string
     */
    public function publicEncrypt($data = '') {
        if (!is_string($data)) {
            return null;
        }
        return openssl_public_encrypt($data, $encrypted, $this->_getPublicKey()) ? base64_encode($encrypted) : null;
    }

    /**
     * @uses 私钥解密
     * @param string $encrypted
     * @return null|string
     */
    public function privDecrypt($encrypted = '') {
        if (!is_string($encrypted)) {
            return null;
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, $this->_getPrivateKey())) ? $decrypted : null;
    }

    /**
     * @uses 公钥解密 / 验签
     * @param string $data
     * @param string $sign
     * @return bool
     */
    public function verifySignature($data = '', $sign = '') {
        if (!is_string($data) || !is_string($sign)) {
            return null;
        }
        //return (openssl_public_decrypt(base64_decode($data), $decrypted, $this->_getPublicKey())) ? $decrypted : null;
        return openssl_verify($data, base64_decode($sign), $this->_getPublicKey(), OPENSSL_ALGO_SHA256);
    }
}