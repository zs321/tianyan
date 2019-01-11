<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo C('SYSTEM_TITLE');?> | 后台管理系统</title>
    <link rel="shortcut icon" href="/Public/favicon.ico" type="image/x-icon" />
    <link href='/Public/css/26707bedc25a4344bb99d7e1215825f4.css' rel='stylesheet' type='text/css'>
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/nifty.min.css" rel="stylesheet">
    <link href="/Public/css/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="/Public/css/nifty-demo.min.css" rel="stylesheet">
    <link href="/Public/css/magic-check.min.css" rel="stylesheet">
    <link href="/Public/css/pace.min.css" rel="stylesheet">
    <script src="/Public/js/pace.min.js"></script>
    <script src="/Public/js/jquery-2.2.4.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <style type="text/css">
        .form-group input{
            padding:12px;
        }
    </style>
</head>
<body>
<div id="container" class="cls-container">
    <div id="bg-overlay"></div>
    <div class="cls-content">
        <div class="cls-content-sm panel">
            <div class="panel-body">
                <div class="mar-ver pad-btm">
                    <h3 class=" mar-no"><?php echo C('SYSTEM_TITLE');?>后台管理系统</h3>
                </div>
                <form action="<?php echo U('Login/submit');?>" method="post">
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control" placeholder="用户名" autofocus>
                    </div>
                    <div class="form-group">
                        <input type="password" name="userpwd" class="form-control" placeholder="密码">
                    </div>
                    <div class="form-group">
                        <img style="float: right; display: block; height: 44px; width: 40%; cursor: pointer;" src="<?php echo U('Login/verify',array('t'=>time()));?>" onclick="this.src='<?php echo U('login/verify',array('t'=>time()));?>'" alt="验证码" title="点击刷新" />
                        <input style="width: 55%;" type="text" name="vcode" class="form-control" placeholder="验证码">
                    </div>
                    <button class="btn btn-primary btn-lg btn-block" type="submit">登 录</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js" type="text/javascript"></script>
</body>
</html>