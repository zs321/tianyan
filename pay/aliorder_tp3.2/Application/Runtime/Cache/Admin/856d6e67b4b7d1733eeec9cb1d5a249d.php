<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo C('SYSTEM_TITLE');?> | 后台管理系统</title>
    <link rel="shortcut icon" href="/Public/favicon.ico" type="image/x-icon" />

    <link href='/Public/css/26707bedc25a4344bb99d7e1215825f4.css' rel='stylesheet' type='text/css'>
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/Public/css/nifty.min.css" rel="stylesheet">
    <link href="/Public/css/pace.min.css" rel="stylesheet">
    <link href="/Public/css/ionicons.min.css" rel="stylesheet">
    <link href="/Public/css/themify-icons.min.css" rel="stylesheet">

    <link href="/Public/css/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="/Public/css/nifty-demo.min.css" rel="stylesheet">
    <script src="/Public/js/pace.min.js"></script>
    <script src="/Public/js/jquery-2.2.4.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>

    <script src="/Public/js/bootstrap-datepicker.min.js"></script>
    <script src="/Public/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/Public/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

    <script src="/Public/js/nifty.min.js"></script>

</head>

<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg">
    <header id="navbar">
        <div id="navbar-container" class="boxed">

            <!--Brand logo & name-->
            <!--================================-->
            <div class="navbar-header">
                <a href="<?php echo U('Main/index');?>" class="navbar-brand">
                    <img src="/Public/images/picture/logo.png" alt="Nifty Logo" class="brand-icon">
                    <div class="brand-title">
                        <span class="brand-text">&nbsp;管理系统</span>
                    </div>
                </a>
            </div>
            <!--================================-->
            <!--End brand logo & name-->


            <!--Navbar Dropdown-->
            <!--================================-->
            <div class="navbar-content clearfix">
                <ul class="nav navbar-top-links pull-left">

                    <!--Navigation toogle button-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <li class="tgl-menu-btn">
                        <a class="mainnav-toggle" href="#">
                            <i class="demo-pli-view-list"></i>
                        </a>
                    </li>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End Navigation toogle button-->

                </ul>
                <ul class="nav navbar-top-links pull-right">
                    <!--User dropdown-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <li id="dropdown-user" class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="pull-right">
                                    <!--<img class="img-circle img-user media-object" src="/Public/images/picture/1.png" alt="Profile Picture">-->
                                    <i class="demo-pli-male ic-user"></i>
                                </span>
                            <div class="username hidden-xs"><?php echo ($username); ?></div>
                        </a>


                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">
                            <!-- User dropdown menu -->
                            <ul class="head-list">
                                <li>
                                    <a href="<?php echo U('Main/center');?>">
                                        <i class="demo-pli-male icon-lg icon-fw"></i> 账户中心
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo U('Main/password');?>">
                                        <i class="demo-pli-gear icon-lg icon-fw"></i> 密码修改
                                    </a>
                                </li>
                            </ul>

                            <!-- Dropdown footer -->
                            <div class="pad-all text-right">
                                <a href="<?php echo U('login/logout');?>" class="btn btn-primary">
                                    <i class="demo-pli-unlock"></i> 注销
                                </a>
                            </div>
                        </div>
                    </li>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End user dropdown-->
                </ul>
            </div>
            <!--================================-->
            <!--End Navbar Dropdown-->

        </div>
    </header>
    <!--===================================================-->
    <!--END NAVBAR-->

    <div class="boxed">

        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">

            <link href="/Public/css/magic-check.min.css" rel="stylesheet">
<style type="text/css">
    .min{color: red;}
</style>
<div id="page-content">
    <div class="row">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo ($act); ?>账号</h3>
            </div>
            <form class="panel-body form-horizontal form-padding" method="post" action="<?php echo U('Account/dataHandle'); if(!empty($re)): ?>?account_id=<?php echo ($re["account_id"]); endif; ?>" onsubmit="return check()">
                <div class="form-group">
                    <label class="col-md-3 control-label">账号：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="account" value="<?php echo ($re["account"]); ?>" id="account">
                        <input type="hidden" name="oldAccount" value="<?php echo ($re["account"]); ?>">
                    </div>
                    <label class="col-md-2 control-label min" style="text-align: left;"></label>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">备注：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="note" value="<?php echo ($re["note"]); ?>" id="note">
                    </div>
                    <label class="col-md-2 control-label min" style="text-align: left;"></label>
                </div>
				<div class="form-group">
                    <label class="col-md-3 control-label">支付宝UserId：</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="ali_userid" value="<?php echo ($re["ali_userid"]); ?>" id="ali_userid">
                    </div>
                    <label class="col-md-2 control-label min" style="text-align: left;"></label>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">金额范围：</label>
                    <div class="col-md-3">
                        <select class="form-control" name="amount" id="amount">
                            <?php if(is_array($amount)): foreach($amount as $key=>$v): ?><option value="<?php echo ($v["min"]); ?>——<?php echo ($v["max"]); ?>" <?php if(($v["min"] == $re["min"] ) and ($v["max"] == $re["max"] )): ?>selected='selected'<?php endif; ?>><?php echo ($v["min"]); ?>——<?php echo ($v["max"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                    <label class="col-md-2 control-label min" style="text-align: left;"></label>
                </div>
                <div class="form-group pad-ver">
                    <label class="col-md-3 control-label">账号类型：</label>
                    <div class="col-md-9">
                        <?php if(!empty($re)): ?><div class="radio">
                                <input id="hot_0" class="magic-radio" type="radio" name="type" value="1" <?php if($re["type"] == 1): ?>checked<?php endif; ?>>
                                <label for="hot_0">微信</label>

                                <input id="hot_1" class="magic-radio" type="radio" name="type" value="2" <?php if($re["type"] == 2): ?>checked<?php endif; ?>>
                                <label for="hot_1">支付宝</label>
                            </div>
                        <?php else: ?>
                            <div class="radio">
                                <input id="hot_0" class="magic-radio" type="radio" name="type" value="1" checked>
                                <label for="hot_0">微信</label>

                                <input id="hot_1" class="magic-radio" type="radio" name="type" value="2" >
                                <label for="hot_1">支付宝</label>
                            </div><?php endif; ?>
                    </div>
                </div>
				<div class="form-group pad-ver">
                    <label class="col-md-3 control-label">帐号性质</label>
                    <div class="col-md-9">
                        <?php if(!empty($re)): ?><div class="radio">
                                <input id="hot_2" class="magic-radio" type="radio" name="astatus" value="0" <?php if($re["astatus"] == 0): ?>checked<?php endif; ?>>
                                <label for="hot_2">个人</label>

                                <input id="hot_3" class="magic-radio" type="radio" name="astatus" value="1" <?php if($re["astatus"] == 1): ?>checked<?php endif; ?>>
                                <label for="hot_3">企业</label>
                            </div>
                        <?php else: ?>
                            <div class="radio">
                                <input id="hot_2" class="magic-radio" type="radio" name="astatus" value="0" >
                                <label for="hot_2">个人</label>

                                <input id="hot_3" class="magic-radio" type="radio" name="astatus" value="1" checked>
                                <label for="hot_3">企业</label>
                            </div><?php endif; ?>
                    </div>
                </div>
				
                <div class="form-group pad-ver">
                    <label class="col-md-3 control-label">账号状态：</label>
                    <div class="col-md-9">
                        <?php if(!empty($re)): ?><div class="radio">
                                <input id="hot_2" class="magic-radio" type="radio" name="status" value="0" <?php if($re["status"] == 0): ?>checked<?php endif; ?>>
                                <label for="hot_2">启用</label>

                                <input id="hot_3" class="magic-radio" type="radio" name="status" value="1" <?php if($re["status"] == 1): ?>checked<?php endif; ?>>
                                <label for="hot_3">禁用</label>
                            </div>
                        <?php else: ?>
                            <div class="radio">
                                <input id="hot_2" class="magic-radio" type="radio" name="status" value="0" checked>
                                <label for="hot_2">启用</label>

                                <input id="hot_3" class="magic-radio" type="radio" name="status" value="1" >
                                <label for="hot_3">禁用</label>
                            </div><?php endif; ?>
                    </div>
                </div>
                <div style="height: 20px;"></div>
                <div class="form-group text-center">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-primary">点击提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function check() {
        var account = $('#account').val();
        if (account == null || $.trim(account)=='') {
            $('.min').html('账号不能为空');
            return false;
        }else{
            $('.min').html('');
        }
        return true;
    }
</script>



        </div>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->


        <!--MAIN NAVIGATION-->
        <!--===================================================-->
        <nav id="mainnav-container">
            <div id="mainnav">

                <!--Menu-->
                <!--================================-->
                <div id="mainnav-menu-wrap">
                    <div class="nano">
                        <div class="nano-content">

                            <!--Profile Widget-->
                            <!--================================-->
                            <div id="mainnav-profile" class="mainnav-profile">
                                <div class="profile-wrap">
                                    <div class="pad-btm">
                                        <span class="label label-success pull-right">管理员</span>
                                        <img class="img-circle img-sm img-border" src="/Public/images/picture/1.png" alt="Profile Picture">
                                    </div>
                                    <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                            <span class="pull-right dropdown-toggle">
                                                <i class="dropdown-caret"></i>
                                            </span>
                                        <p class="mnp-name"><?php echo ($loginname); ?></p>
                                    </a>
                                </div>
                                <div id="profile-nav" class="collapse list-group bg-trans">
                                    <a href="<?php echo U('Main/center');?>" class="list-group-item">
                                        <i class="demo-pli-male icon-lg icon-fw"></i> 账户中心
                                    </a>
                                    <a href="<?php echo U('Main/password');?>" class="list-group-item">
                                        <i class="demo-pli-gear icon-lg icon-fw"></i> 密码修改
                                    </a>
                                    <a href="<?php echo U('login/logout');?>" class="list-group-item">
                                        <i class="demo-pli-unlock icon-lg icon-fw"></i> 注销登录
                                    </a>
                                </div>
                            </div>


                            <!--Shortcut buttons-->
                            <!--================================-->
                            <div id="mainnav-shortcut">
                                <ul class="list-unstyled">
                                    <li class="col-xs-3" data-content="账户中心">
                                        <a class="shortcut-grid" href="<?php echo U('Main/center');?>">
                                            <i class="demo-psi-male"></i>
                                        </a>
                                    </li>
                                    <li class="col-xs-3" data-content="密码修改">
                                        <a class="shortcut-grid" href="<?php echo U('Main/password');?>">
                                            <i class="demo-pli-gear"></i>
                                        </a>
                                    </li>
                                    <li class="col-xs-3" data-content="注销登录">
                                        <a class="shortcut-grid" href="<?php echo U('login/logout');?>">
                                            <i class="demo-psi-lock-2"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--================================-->
                            <!--End shortcut buttons-->


                            <ul id="mainnav-menu" class="list-group">

    <!--Category name-->
    <li class="list-header">管理中心</li>

    <!--Menu list item-->
    <?php $menu=C('ADMIN_MENU'); foreach($menu as $key=>$val){?>
    <li <?php if(in_array(CONTROLLER_NAME,$val['active'])){echo 'class="active-sub"';}?>>
    <a href="<?php echo U($val['url'],$val['param']);?>">
        <i class="<?php echo ($val['icon']); ?>"></i>
        <span class="menu-title"><?php echo ($val['name']); ?></span>
        <i class="arrow"></i>
    </a>
    <?php if(count($val['menu'])){?>
    <ul class="collapse  <?php if(in_array(CONTROLLER_NAME,$val['active'])){echo 'in';}?>">
        <?php if($val['menu']){ foreach($val['menu'] as $k=>$v){?>
        <li <?php if(strtolower(CONTROLLER_NAME."/".ACTION_NAME)==strtolower($v['url'])){echo 'class="active-link"';}?>>
            <a href="<?php echo U($v['url'],$v['param']);?>">
                <?php echo $v['name'];?>
            </a>
        </li>
        <?php }}?>
    </ul>
    <?php }?>
    </li>
    <?php }?>
</ul>


                        </div>
                    </div>
                </div>
                <!--================================-->
                <!--End menu-->

            </div>
        </nav>
        <!--===================================================-->
        <!--END MAIN NAVIGATION-->

    </div>



    <!-- FOOTER -->
    <!--===================================================-->
    <footer id="footer">

        <!-- Visible when footer positions are fixed -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="show-fixed pull-right">
            You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
        </div>



        <!-- Visible when footer positions are static -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="hide-fixed pull-right pad-rgt">
            <?php echo C("COMPANY_URL");?>
        </div>



        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

        <p class="pad-lft">&#0169; 2017 <?php echo C("COMPANY_TITLE");?> 技术支持 联系电话:<?php echo C("COMPANY_PHONE");?></p>



    </footer>
    <!--===================================================-->
    <!-- END FOOTER -->


    <!-- SCROLL PAGE BUTTON -->
    <!--===================================================-->
    <button class="scroll-top btn">
        <i class="pci-chevron chevron-up"></i>
    </button>
    <!--===================================================-->



</div>
</body>
</html>