<?php
 
// 签名证书路径
const SDK_SIGN_CERT_PATH = "./assets/certs/test.pfx"; 
// 签名证书密码
const SDK_SIGN_CERT_PWD = '890373';
// 密码加密证书（这条一般用不到的请随便配）
const SDK_ENCRYPT_CERT_PATH = './assets/certs/service_cert.cer';

// 验签证书路径（请配到文件夹，不要配到具体文件）
const SDK_VERIFY_CERT_DIR = "./assets/certs/";
// 后台请求地址
const SDK_BACK_TRANS_URL = "http://api.256pos.com/gateway/api/backScanTransReq.php";

// 前台通知地址 (商户自行配置通知地址)
const SDK_FRONT_PAY_NOTIFY_URL = 'http://pay.7zcyl.com/pay/sfb/return_url.php'; 
// 后台通知地址 (商户自行配置通知地址)
const SDK_BACK_NOTIFY_URL = 'http://pay.7zcyl.com/pay/sfb/notify_url.php';

//日志 目录
const SDK_LOG_FILE_PATH = 'logs';

//日志级别
const SDK_LOG_LEVEL = 2;
