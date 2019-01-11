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

            <style type="text/css">
    .form-control.input_w80{width: 80px;}
</style>
<div id="page-content">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">二维码列表</h3>
        </div>
        <div class="panel-body">
            <div class="pad-btm form-inline">
                <div class="row">
                    <div class="col-sm-6 table-toolbar-left">
                        <a href="#" class="btn btn-primary">微信</a>
                        <a href="<?php echo U('Qrcode/aliCode');?>" class="btn btn-default">支付宝</a>
                        <a href="<?php echo U('Qrcode/add');?>?type=1" class="btn btn-default"><i class="demo-pli-add"></i> 添加二维码</a>
                    </div>
                    <div class="col-sm-6 table-toolbar-right">

                    </div>
                </div>
            </div>
            <div class="pad-btm form-inline">
                <div class="row">
                    <div class="col-sm-12 table-toolbar">
                        <form action="<?php echo U('Qrcode/index');?>" method="get" onsubmit="return checkInput();">
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select2" class="control-label">所属账号：</label>
                                <select class="form-control" name="account" id="dtp_select2">
                                    <option value="">选择账号</option>
                                    <?php if(is_array($re)): foreach($re as $key=>$v): ?><option value="<?php echo ($v["account"]); ?>" <?php if($v["account"] == $account ): ?>selected<?php endif; ?>><?php echo ($v["account"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_input3" class="control-label">金额范围</label>
                                <input type="text" class="form-control input_w80" value="<?php echo ($start_m); ?>" name="start_m" placeholder="开始金额" id="start_m"/>-
                                <input type="text" class="form-control input_w80" value="<?php echo ($end_m); ?>" name="end_m" placeholder="结束金额" id="end_m">
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_input3" class="control-label">备注范围：</label>
                                <input type="text" class="form-control input_w80" value="<?php echo ($start_n); ?>" name="start_n" placeholder="开始备注" id="start_n">-
                                <input type="text" class="form-control input_w80" value="<?php echo ($end_n); ?>" name="end_n" placeholder="结束备注" id="end_n">
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select2" class="control-label">状态：</label>
                                <select class="form-control" name="tag" id="dtp_select2">
                                    <option value="-1">选择状态</option>
                                        <option value="0" <?php if($tag == 0 ): ?>selected<?php endif; ?>>空闲</option>
                                        <option value="1" <?php if($tag == 1 ): ?>selected<?php endif; ?>>占用</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <button class="btn btn-default"><i class="ion-search"></i> 查询</button>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <a href="<?php echo U('Qrcode/decodeFailList');?>?type=1" class="btn btn-primary">待解码</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">类型</th>
                        <th class="text-center">二维码</th>
                        <th class="text-center">二维码原图片</th>
                        <th class="text-center">所属账号</th>
                        <th class="text-center">金额</th>
                        <th class="text-center">备注</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">添加时间</th>
                        <th class="text-center">操作管理</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($lists)): foreach($lists as $key=>$v): ?><tr>
                            <td><?php echo ($v["code_id"]); ?></td>
                            <td>微信</td>
                            <td>
                            <?php if(empty($v["qrdecode"])): ?><a href="#" class="label label-table label-success" onclick="inputShow(this,<?php echo ($v["code_id"]); ?>)" style="background: #38a0f4;">重解码</a>
                            <?php else: ?>
                            <?php echo ($v["qrdecode"]); endif; ?>
                            </td>
                            <td>
								
								<?php if(!empty($v["qrcode"])): if(stripos($v["qrcode"],'HTTPS')){ echo $v["qrcode"]; } else{ echo $codePath.$v["qrcode"]; } endif; ?>
								
                            </td>
                            <td><?php echo ($v["account"]); ?></td>
                            <td><?php echo ($v["money"]); ?></td>
                            <td><?php echo ($v["note"]); ?></td>
                            <td>
                                <?php switch($v["tag"]): case "0": ?><div class="label label-table label-success">空闲</div><?php break;?>
                                    <?php case "1": ?><div class="label label-table label-warning">占用</div><?php break; endswitch;?>
                            </td>
                            <td><?php echo (date("Y-m-d H:i:s",$v["addtime"])); ?></td>
                            <td>
                                <a href="<?php echo U('Qrcode/show',array('code_id'=>$v['code_id'],'type'=>1));?>" target="_blank">查看</a>
                                |
                                <a href="#" onclick="delQrcode(<?php echo ($v['code_id']); ?>,'<?php echo ($v["qrcode"]); ?>',1)">删除</a>
                            </td>
                        </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-right">
                <?php echo ($page); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function checkInput(){
        var start_m = $.trim($('#start_m').val());
        var end_m = $.trim($('#end_m').val());
        if (start_m != '') {
            if (!judgeSign(start_m)) {
                alert('开始金额请输入非负数！');
                return false;
            }
        }
        if (end_m != '') {
            if (!judgeSign(end_m)) {
                alert('结束金额请输入非负数！');
                return false;
            }
        }
        
    }

//判断一个变量是否是非负数
function judgeSign(num) {
    var reg = new RegExp("^-?[0-9]*.?[0-9]*$");
    if ( reg.test(num) || num==0) {
        var absVal = Math.abs(num);
        if (absVal || num==0) {
            return true;
        }else{
            return false;
        }
    }else {
        return false;
    }
}

/*删除二维码*/
function delQrcode(code_id,qrcode,type){
    var url = "<?php echo U('Admin/Qrcode/del');?>";
    var data = {code_id:code_id,qrcode:qrcode,type:type};
    $.ajax({
        url : url,
        data : data,
        dataType : 'json',
        type : 'post',
        async : false,
        success : function(result){
            
            if (result== 'success')
            {
                alert('操作成功！');
                window.location.reload();
            }else{
                alert('操作失败！');
            }
        }
    });
}
function inputShow(obj,code_id){
    var html = '<input type="text" id="qrdecode" name="qrdecode"><input type="hidden" id="code_id" name="code_id" value="'+code_id+'"><span class="label label-table label-success" onclick="qrdecode(this)" style="background: #38a0f4;">提交</span>';
    $(obj).parent().html(html);
}
function inputHide(obj,code_id){
    var html = '<a href="#" class="label label-table label-success" style="background: #38a0f4;" onclick="inputShow(this,'+code_id+')">重解码</a>';
    $(obj).parent().html(html);
}
/*二维码重新解码*/
function qrdecode(obj){
    var url = "<?php echo U('Admin/Qrcode/qrdecode');?>";
    var qrdecode = $('#qrdecode').val();
    var code_id = $('#code_id').val();
    var type = 1;
    var data = {code_id:code_id,qrdecode:qrdecode,type:type};
    $.ajax({
        url : url,
        data : data,
        dataType : 'json',
        type : 'post',
        async : false,
        success : function(result){
            if (result== 'success')
            {
                $(obj).parent().html(qrdecode);
                alert('操作成功！');
            }else{
                alert('操作失败！');
            }
        }
    });
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