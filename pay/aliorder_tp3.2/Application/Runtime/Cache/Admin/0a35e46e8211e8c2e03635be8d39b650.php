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

            <div id="page-content">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">订单列表</h3>
        </div>
        <div class="panel-body">
            <div class="pad-btm form-inline">
                <div class="row">
                    <div class="col-sm-12 table-toolbar">
                        <form action="<?php echo U('Order/index');?>" method="get">
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label for="dtp_input1" class="control-label">订单日期：</label>
                                <div class="input-group date form_datetime" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1">
                                    <input class="form-control" size="16" type="text" value="<?php echo ($sday); ?>" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <label class="control-label">&nbsp;&nbsp;至&nbsp;&nbsp;</label>
                                <div class="input-group date form_datetime" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input2" style="margin-right: 10px;">
                                    <input class="form-control" size="16" type="text" value="<?php echo ($eday); ?>" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" name="sday" id="dtp_input1" value="<?php echo ($sday); ?>" />
                                <input type="hidden" name="eday" id="dtp_input2" value="<?php echo ($eday); ?>" />
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_input3" class="control-label">订单号：</label>
                                <input type="text" class="form-control" value="<?php echo ($orderno); ?>" name="orderno" id="dtp_input3"/>
                            </div>
                            <br>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select1" class="control-label">支付方式：</label>
                                <select class="form-control" name="paytype" id="paytype">
                                    <option value="">选择支付方式</option>
                                    <option value="1" <?php if($paytype == '1'): ?>selected<?php endif; ?>>微信</option>
                                    <option value="2" <?php if($paytype == '2'): ?>selected<?php endif; ?>>支付宝</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select1" class="control-label">收款账号：</label>
                                <select class="form-control" name="account" id="account" >
                                    <option value="">选择收款账号</option>
                                    <?php if(is_array($re)): foreach($re as $key=>$v): ?><option value="<?php echo ($v["account"]); ?>" <?php if($v["account"] == $account ): ?>selected<?php endif; ?>><?php echo ($v["account"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select2" class="control-label">支付状态：</label>
                                <select class="form-control" name="paystatus" id="dtp_select2">
                                    <option value="">选择支付状态</option>
                                    <option value="1" <?php if($paystatus == '1'): ?>selected<?php endif; ?>>支付成功</option>
                                    <option value="0" <?php if($paystatus == '0'): ?>selected<?php endif; ?>>待支付</option>
                                    <option value="-1" <?php if($paystatus == '-1'): ?>selected<?php endif; ?>>支付失败</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <label for="dtp_select3" class="control-label">下发状态：</label>
                                <select class="form-control" name="noticestatus" id="dtp_select3">
                                    <option value="">选择下发状态</option>
                                    <option value="1" >发送成功</option>
                                    <option value="0" >发送失败</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;margin-bottom: 10px;">
                                <button class="btn btn-default"><i class="ion-search"></i> 检索</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="demo-foo-row-toggler" class="table table-bordered text-center  toggle-circle">
                    <thead>
                    <tr>
                        <th class="text-center">订单ID</th>
                        <th class="text-center">订单号</th>
                        <th class="text-center">支付宝单号</th>
                        <th class="text-center">订单金额</th>
                        <th class="text-center">备注</th>
                        <th class="text-center">收款账号</th>
                        <th class="text-center">支付方式</th>
                        <th class="text-center">支付状态</th>
                        <th class="text-center">下发状态</th>
                        <th class="text-center">下单时间</th>
                        <th class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($lists)): foreach($lists as $key=>$v): ?><tr>
                            <td><?php echo ($v["order_id"]); ?></td>
                            <td><?php echo ($v["order_number"]); ?></td>
                            <td><?php echo ($v["ali_sn"]); ?></td>
                            <td>
                                <?php echo $v["order_money"]; ?>
                            </td>
                            <td><?php echo ($v["note"]); ?></td>
                            <td><?php echo ($v["account"]); ?></td>
                            <td>
                                <?php switch($v["pay_type"]): case "1": ?>微信<?php break;?>
                                    <?php case "2": ?>支付宝<?php break; endswitch;?>
                            </td>
                            <td>
                                <?php switch($v["pay_status"]): case "0": ?><div style="color:orange;">待支付</div><?php break;?>
                                    <?php case "1": ?><div style="color: #68B83D">支付成功</div><?php break;?>
                                    <?php case "-1": ?><div style="color: #FF1111">支付失败</div><?php break; endswitch;?>
                            </td>
                            <td>
                                --

                            </td>
                            <td>
                                <?php echo date("Y-m-d H:i:s",$v["addtime"]); ?>
                            </td>
                            <td>
                                <a href="<?php echo U('Order/detail',array('d'=>$v['order_id']));?>">查看详情</a>
                                <?php if($v['pay_status'] == 1): ?>|
                                    <a href="<?php echo U('Order/send',array('d'=>$v['order_id']));?>">订单重发</a><?php endif; ?>
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
<script>
    $(function () {
        $('.form_datetime').datetimepicker({
            language:  'zh-CN',
            format: "yyyy-mm-dd hh:ii:ss",
            autoclose: true
        });
    })
    $('#paytype').change(function(){
        var type = $("#paytype").val();
        $.ajax({
            url:'<?php echo U("Order/getAccountList");?>',
            type:'get',
            data:{type:type},
            dataType:'json',
            success:function(data){
                if (data.state == 'SUCCESS') {
                    $('#account').html(data.data);
                }
            }
        })
    })
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