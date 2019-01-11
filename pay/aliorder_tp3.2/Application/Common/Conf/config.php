<?php
if ($_SERVER['HTTP_HOST']=='localhost') {
    $qrcode = 'http://localhost/Public/Uploads/Qrcode/Create/';
    $db_name = 'alipaydata';
    $db_user = 'alipaydata';
    $db_pwd = 'W1pJL240lodFL0O';
    $yuming ='http://localhost';
}else{
    $qrcode = 'http://ai.1899pay.com/Public/Uploads/Qrcode/Create/';
    $db_name = 'alipaydata';
    $db_user = 'alipaydata';
    $db_pwd = 'W1pJL240lodFL0O';
    $yuming ='http://ai.1899pay.com';
}
return array(
    //系统支付请求配置
    "PAY_REQ_KEY"=>"",//请求密钥
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING'  =>array(
        '__PUBLIC__' => '/Public', // 更改默认的/Public 替换规则
        '__JS__' => '/Public/js', // 增加新的JS类库路径替换规则
        '__CSS__' => '/Public/css', // 增加新的JS类库路径替换规则
        '__IMG__' => '/Public/images', // 增加新的JS类库路径替换规则
        '__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
    ),

    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => $db_name, // 数据库名
    'DB_USER'   => $db_user, // 用户名
    'DB_PWD'    => $db_pwd, // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'p_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式
     
     'YUMING'   =>$yuming,
    //客户端参数名称配置
    'API_KEY'=>'key',//系统与客户端关系唯一标识
    'ZFB_ACCOUNT'=>'zfb',//支付宝交易记录账号字段
    'WX_ACCOUNT'=>'wx',//微信交易记录账号字段
    'MONEY'=>'money',//金额
    'NOTE'=>'b',//备注
    'ALI_SN'=>'o',//支付宝单号
    'ADDTIME'=>'t',//交易时间

    'QRCODE'=>$qrcode,//二维码解析后重新生成的图片读取地址
    //二维码上传保存绝对路径
    'QRCODE_UPLOAD'=>'./Public/Uploads/Qrcode/',
    //二维码缩略图保存绝对路径
    'QRCODE_THUMB'=>'./Public/Uploads/Qrcode/Thumb/',
    //二维码缩略图被解码读取时的路径
    'QRDECODE'=>'./Public/Uploads/Qrcode/Thumb/',
    //用解码后的二维码字符串重新生成的二维码图片
    'QRCODE_CREATE'=>'./Public/Uploads/Qrcode/Create/',
);