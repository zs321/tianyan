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
<link rel="stylesheet" type="text/css" href="/Public/webuploader/0.1.5/webuploader.css" />


<style type="text/css">
.money {
    background: #ffffff;
    color: #ff1107;
    position: absolute;
    bottom: 0px;
    left: 0;
    height: 26px;
    line-height: 22px;
    width: 100%;
    z-index: 100;
    margin:0;
}
.money input {
    margin-left: 5px;
    margin-top: 0px;
    width: 70px;
	width: 57px;
    text-align: center;
    background:none;border:none;color: #777777;border-width:1px;border-color:#bfbfbf;border-style:solid;
}
/*.imgWrap img{margin-top:-24px;}*/
</style>
<div id="page-content">
    <div class="row">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">新增二维码</h3>
            </div>
            <form class="panel-body form-horizontal form-padding" method="post" >
                <div class="form-group pad-ver">
                    <label class="col-md-3 control-label">二维码类型：</label>
                    <div class="col-md-9">
                        <div class="radio">
                            <?php switch($type): case "0": ?><input id="hot_0" class="magic-radio" type="radio" name="types" value="1" checked>
                                    <label for="hot_0">微信</label>
                                    <input id="hot_1" class="magic-radio" type="radio" name="types" value="2" >
                                    <label for="hot_1">支付宝</label><?php break;?>
                                <?php case "1": ?><input id="hot_0" class="magic-radio" type="radio" name="types" value="1" checked>
                                    <label for="hot_0">微信</label><?php break;?>
                                <?php case "2": ?><input id="hot_1" class="magic-radio" type="radio" name="types" value="2" checked>
                                    <label for="hot_1">支付宝</label><?php break; endswitch;?>
                        </div>
                    </div>
                </div>
                <div class="form-group pad-ver">
                    <label class="col-md-3 control-label">所属账号：</label>
                    <div class="col-md-3">
                        <select class="form-control" name="account" id="account">
                            <?php if(is_array($re)): foreach($re as $key=>$v): ?><option value="<?php echo ($v["account"]); ?>"><?php echo ($v["account"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">金额前缀：</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="sameMoney">
                    </div>
                    <!-- <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="sameMoney()">统一前缀</a>
                    </div> -->
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">金额幅度：</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="rangeMoney" placeholder="递增递减幅度">
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="upMoney()">递增</a>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="downMoney()">递减</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">备注：</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control" id="startNote" placeholder="备注开始">
                    </div>
                    <div class="col-md-1">
                        <input type="text" class="form-control" id="endNote" placeholder="备注停止">
                    </div> 
                    <div class="col-md-4">
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="upNote()">递增</a>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="downNote()">递减</a>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="sameNote()">统一备注</a>
                    </div>
                    <!-- <div class="col-md-1">
                        <input type="text" class="form-control" id="endNote" placeholder="备注停止">
                    </div> -->
                    <!-- <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="upNote()">递增</a>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="downNote()">递减</a>
                    </div> -->
                </div>
                <div class="uploader-list-container">
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div id="filePicker-2"></div>
                            <p>或将照片拖到这里，单次最多可选1000张</p>
                        </div>
                    </div>
                    <div class="statusBar" style="display:none;">
                        <div class="progress"> <span class="text">0%</span> <span class="percentage"></span> </div>
                        <div class="info"></div>
                        <div class="btns">
                            <div id="filePicker2"></div>
                            <div class="uploadBtn">开始上传</div>
                        </div>
                    </div>
                </div>
               <input type="hidden" name="type" value="<?php if(($type) == "0"): ?>1<?php else: echo ($type); endif; ?>" id="qrcode_type">
            </form>
        </div>
    </div>
</div>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/webuploader/0.1.5/webuploader.min.js"></script>
<script type="text/javascript" >
// 二维码类型改变事件
$('.magic-radio').change(function(){
    var type = $("input[name='types']:checked").val();
    $.ajax({
            url:'<?php echo U("Qrcode/getAccountList");?>',
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
//统一金额前缀
function sameMoney(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var sameMoney = $('#sameMoney').val();
    if ($.trim(sameMoney)=='' || !judgeSign(sameMoney)) {
        alert('请在金额前缀上面输入正数！');
        return false;
    }
    $("input[name='money']").val(sameMoney);

}
//统一备注
function sameNote(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var startNote = $('#startNote').val();
    if ($.trim(startNote)=='' || !judgeSign(startNote)) {
        alert('请在备注开始上面输入整数！');
        return false;
    }
    $("input[name='note']").val(startNote);

}
//金额递增对应填写的幅度
function upMoney(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var sameMoney = $('#sameMoney').val();
    if ($.trim(sameMoney)=='' || !judgeSign(sameMoney)) {
        alert('请在金额前缀上面输入一个开始金额（正数）！');
        return false;
    }
    var rangeMoney = $('#rangeMoney').val();
    if ($.trim(rangeMoney)=='' || !judgeSign(rangeMoney)) {
        alert('请在金额幅度上面输入正数！');
        return false;
    }
    for (var i = 0; i < qrcodeNum; i++) {
        $("input[name='money']").eq(i).val(sameMoney*1+rangeMoney*i*1);
    }
}
//金额递减对应填写的幅度
function downMoney(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var sameMoney = $('#sameMoney').val();
    if ($.trim(sameMoney)=='' || !judgeSign(sameMoney)) {
        alert('请在金额前缀上面输入一个开始金额（正数）！');
        return false;
    }
    var rangeMoney = $('#rangeMoney').val();
    if ($.trim(rangeMoney)=='' || !judgeSign(rangeMoney)) {
        alert('请在金额幅度上面输入正数！');
        return false;
    }
    for (var i = 0; i < qrcodeNum; i++) {
        var m = sameMoney*1-rangeMoney*i*1;
        if (m<=0) {
            m = sameMoney;
        }
        $("input[name='money']").eq(i).val(m);
    }
}
//备注递增1
function upNote(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var startNote = $.trim($('#startNote').val());
    var endNote = $.trim($('#endNote').val());
    alert(startNote);
    if (startNote=='') {
        startNote=0;
    }
    if (endNote=='') {
        endNote=0;
    }
    if (startNote==0 && endNote==0) {
        for (var i = 0; i < qrcodeNum; i++) {
            n = startNote*1+i;
            $("input[name='note']").eq(i).val(n);
        }
    }else{
        var j = endNote*1-startNote*1;
        for (var i = 0; i < qrcodeNum; i++) {
            if (j<=0 || i>j) {
                n = startNote;
            }else if (i<=j){
                n = startNote*1+i;
            }
            $("input[name='note']").eq(i).val(n);
        }
    }
    
    
}
//备注递减1
function downNote(){
    var qrcodeNum = $('.money').length;
    if (qrcodeNum==0) {
        alert('请先上传二维码！');
        return false;
    }
    var startNote = $.trim($('#startNote').val());
    var endNote = $.trim($('#endNote').val());
    if (startNote=='') {
        startNote=0;
    }
    if (endNote=='') {
        endNote=0;
    }
    var j = startNote*1-endNote*1;
    for (var i = 0; i < qrcodeNum; i++) {
        if (j<=0 || i>j) {
            n = startNote;
        }else if (i<=j){
            n = startNote*1-i;
        }
        $("input[name='note']").eq(i).val(n);
    }
}
//判断一个变量是否是正数
function judgeSign(num) {
    var reg = new RegExp("^-?[0-9]*.?[0-9]*$");
    if ( reg.test(num) ) {
        var absVal = Math.abs(num);
        if (absVal) {
            return true;
        }else{
            return false;
        }
    }else {
        return false;
    }
}

var success_num=0;
var file_size=0;
(function( $ ){
    // 当domReady的时候开始初始化
    $(function() {

        var $wrap = $('.uploader-list-container'),

            // 图片容器
            $queue = $( '<ul class="filelist"></ul>' )
                .appendTo( $wrap.find( '.queueList' ) ),

            // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find( '.statusBar' ),

            // 文件总体选择信息。
            $info = $statusBar.find( '.info' ),

            // 上传按钮
            $upload = $wrap.find( '.uploadBtn' ),

            // 没选择文件之前的内容。
            $placeHolder = $wrap.find( '.placeholder' ),

            $progress = $statusBar.find( '.progress' ).hide(),

            // 添加的文件数量
            fileCount = 0,

            // 添加的文件总大小
            fileSize = 0,

            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 240 * ratio,
            thumbnailHeight = 350 * ratio,

            // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

            // 所有文件的进度信息，key为file id
            percentages = {},
            // 判断浏览器是否支持图片的base64
            isSupportBase64 = ( function() {
                var data = new Image();
                var support = true;
                data.onload = data.onerror = function() {
                    if( this.width != 1 || this.height != 1 ) {
                        support = false;
                    }
                }
                data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                return support;
            } )(),

            // 检测是否已经安装flash，检测flash的版本
            flashVersion = ( function() {
                var version;

                try {
                    version = navigator.plugins[ 'Shockwave Flash' ];
                    version = version.description;
                } catch ( ex ) {
                    try {
                        version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                                .GetVariable('$version');
                    } catch ( ex2 ) {
                        version = '0.0';
                    }
                }
                version = version.match( /\d+/g );
                return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
            } )(),

            supportTransition = (function(){
                var s = document.createElement('p').style,
                    r = 'transition' in s ||
                            'WebkitTransition' in s ||
                            'MozTransition' in s ||
                            'msTransition' in s ||
                            'OTransition' in s;
                s = null;
                return r;
            })(),

            // WebUploader实例
            uploader;



        // 实例化
        uploader = WebUploader.create({
            pick: {
                id: '#filePicker-2',
                label: '点击选择图片'
            },
            formData: {
                uid: 1
                
            },
            dnd: '#dndArea',
            paste: '#uploader',
            swf: '../Uploader.swf',
            chunked: false,
            chunkSize: 512 * 1024,
            server: '<?php echo U("dataHandle");?>',
            duplicate :true,//可重复上传（相同文件）  
            // runtimeOrder: 'flash',

            // accept: {
            //     extensions: 'gif,jpg,jpeg,bmp,png',
            //     mimeTypes: 'image/*'
            // },

            // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
            disableGlobalDnd: true,
            fileNumLimit: 1000,//图片上传数量限制
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
        });

        // 拖拽时不接受 js, txt 文件。
        uploader.on( 'dndAccept', function( items ) {
            var denied = false,
                len = items.length,
                i = 0,
                // 修改js类型
                unAllowed = 'text/plain;application/javascript ';

            for ( ; i < len; i++ ) {
                // 如果在列表里面
                if ( ~unAllowed.indexOf( items[ i ].type ) ) {
                    denied = true;
                    break;
                }
            }

            return !denied;
        });

        uploader.on('dialogOpen', function() {
            console.log('here');
        });

        // uploader.on('filesQueued', function() {
        //     uploader.sort(function( a, b ) {
        //         if ( a.name < b.name )
        //           return -1;
        //         if ( a.name > b.name )
        //           return 1;
        //         return 0;
        //     });
        // });
        // 添加“添加文件”的按钮，
        uploader.addButton({
            id: '#filePicker2',
            label: '继续添加'
        });

        uploader.on('ready', function() {
            window.uploader = uploader;
        });

        // 当有文件添加进来时执行，负责view的创建
        function addFile( file ) {
            var $li = $( '<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>'+
                    '<p class="progress" style="display:none;"><span></span></p>' +
                    '<div class="money">金额<input type="text" name="money" placeholder="订单金额" id="money_'+file.id+'"><input type="text" name="qrcode_money" placeholder="二维码金额" id="qrcode_money"><input type="text" name="note" placeholder="备注" id="note_'+file.id+'"></div>' +
                    '</li>' ),

                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span>' +
                    '<span class="rotateRight">向右旋转</span>' +
                    '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find( 'p.imgWrap' ),
                $info = $('<p class="error"></p>'),

                showError = function( code ) {
                    switch( code ) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;

                        case 'interrupt':
                            text = '上传暂停';
                            break;

                        default:
                            text = '上传失败，请重试';
                            break;
                    }

                    $info.text( text ).appendTo( $li );
                };

            if ( file.getStatus() === 'invalid' ) {
                showError( file.statusText );
            } else {
                // @todo lazyload
                $wrap.text( '预览中' );
                uploader.makeThumb( file, function( error, src ) {
                    var img;

                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }

                    if( isSupportBase64 ) {
                        img = $('<img src="'+src+'">');
                        $wrap.empty().append( img );
                    } else {
                        $.ajax('../server/preview.php', {
                            method: 'POST',
                            data: src,
                            dataType:'json'
                        }).done(function( response ) {
                            if (response.result) {
                                img = $('<img src="'+response.result+'">');
                                $wrap.empty().append( img );
                            } else {
                                $wrap.text("预览出错");
                            }
                        });
                    }
                }, thumbnailWidth, thumbnailHeight );

                percentages[ file.id ] = [ file.size, 0 ];
                file.rotation = 0;
            }

            file.on('statuschange', function( cur, prev ) {
                if ( prev === 'progress' ) {
                    $prgress.hide().width(0);
                } else if ( prev === 'queued' ) {
                    $li.off( 'mouseenter mouseleave' );
                    $btns.remove();
                }

                // 成功
                if ( cur === 'error' || cur === 'invalid' ) {
                    console.log( file.statusText );
                    showError( file.statusText );
                    percentages[ file.id ][ 1 ] = 1;
                } else if ( cur === 'interrupt' ) {
                    showError( 'interrupt' );
                } else if ( cur === 'queued' ) {
                    percentages[ file.id ][ 1 ] = 0;
                } else if ( cur === 'progress' ) {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if ( cur === 'complete' ) {
                    $li.append( '<span class="success"></span>' );
                }

                $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
            });

            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });

            $btns.on( 'click', 'span', function() {
                var index = $(this).index(),
                    deg;

                switch ( index ) {
                    case 0:
                        uploader.removeFile( file );
                        return;

                    case 1:
                        file.rotation += 90;
                        break;

                    case 2:
                        file.rotation -= 90;
                        break;
                }

                if ( supportTransition ) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                    // use jquery animate to rotation
                    // $({
                    //     rotation: rotation
                    // }).animate({
                    //     rotation: file.rotation
                    // }, {
                    //     easing: 'linear',
                    //     step: function( now ) {
                    //         now = now * Math.PI / 180;

                    //         var cos = Math.cos( now ),
                    //             sin = Math.sin( now );

                    //         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                    //     }
                    // });
                }


            });

            $li.appendTo( $queue );
        }

        // 负责view的销毁
        function removeFile( file ) {
            var $li = $('#'+file.id);

            delete percentages[ file.id ];
            updateTotalProgress();
            $li.off().find('.file-panel').off().end().remove();
        }

        function updateTotalProgress() {
            var loaded = 0,
                total = 0,
                spans = $progress.children(),
                percent;

            $.each( percentages, function( k, v ) {
                total += v[ 0 ];
                loaded += v[ 0 ] * v[ 1 ];
            } );

            percent = total ? loaded / total : 0;


            spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
            spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
            updateStatus();
        }
        //当某个文件的分块在发送前触发，主要用来询问是否要添加附带参数，大文件在开起分片上传的前提下此事件可能会触发多次。
        uploader.on('uploadBeforeSend', function (obj, data, headers) {
            var m_tag = false;
            var n_tag = false;
            data.money = $.trim($("#money_"+data.id).val());
            data.note = $.trim($("#note_"+data.id).val());
            data.qrcode_type = $("input[name='types']:checked").val();
			data.qmoney = $('#qrcode_money').val();
            data.account = $('#account').val()
            if (data.money=='' || !judgeSign(data.money)) {
                m_tag = true;
            }
            if (data.note=='' || m_tag===true) {
                if (m_tag===true) {
                    $("#money_"+data.id).css('border','1px solid red');
                }
                if (data.note=='') {
                    $("#note_"+data.id).css('border','1px solid red');
                }
                // setState( 'uploading' );
                return false;
            }
        });
        //上传成功服务器端返回信息
        uploader.on('uploadSuccess',function(file,response){
        　　var $li =  $('#' + file.id);
        　　if(response.code==0){
        　　　　//这里做你需要做的操作
                $li.append( '<span class="success"></span>' );
                $li.removeClass().addClass( 'state-complete');
                success_num++;
                file_size =file_size+file.size;
        　　}else{
                $li.find('.success').remove();
                $li.append( '<span class="success" style="color:#fff;background:red;bottom:30px;">'+response.msg+'</span>' );
            }
        })
        
        //上传完成，不管成功失败时触发
        uploader.on('uploadComplete',function(file,response){
        })
        function updateStatus() {
            var text = '', stats;

            if ( state === 'ready' ) {
                text = '选中' + fileCount + '张图片，共' +
                        WebUploader.formatSize( fileSize ) + '。';
            } else if ( state === 'confirm' ) {
                stats = uploader.getStats();
                if ( stats.uploadFailNum ) {
                    text = '已成功上传' + stats.successNum+ '张照片至XX相册，'+
                        stats.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>'
                }

            } else {
                stats = uploader.getStats();
                file_s=Math.round(file_size/1024*100)/100;
                file_f=fileCount-success_num
                text = '共' + fileCount + '张（' +
                        WebUploader.formatSize( fileSize )  +
                        '），已上传' + success_num + '张（'+file_s+'K）';
                text += '，失败' + file_f + '张';
                // text = '共' + fileCount + '张（' +
                //         WebUploader.formatSize( fileSize )  +
                //         '），已上传' + stats.successNum + '张';

                // if ( stats.uploadFailNum ) {
                //     text += '，失败' + stats.uploadFailNum + '张';
                // }
            }

            $info.html( text );
        }

        function setState( val ) {
            var file, stats;

            if ( val === state ) {
                return;
            }

            $upload.removeClass( 'state-' + state );
            $upload.addClass( 'state-' + val );
            state = val;

            switch ( state ) {
                case 'pedding':
                    $placeHolder.removeClass( 'element-invisible' );
                    $queue.hide();
                    $statusBar.addClass( 'element-invisible' );
                    uploader.refresh();
                    break;

                case 'ready':
                    $placeHolder.addClass( 'element-invisible' );
                    $( '#filePicker2' ).removeClass( 'element-invisible');
                    $queue.show();
                    $statusBar.removeClass('element-invisible');
                    uploader.refresh();
                    break;

                case 'uploading':
                    $( '#filePicker2' ).addClass( 'element-invisible' );
                    $progress.show();
                    $upload.text( '暂停上传' );
                    break;

                case 'paused':
                    $progress.show();
                    $upload.text( '继续上传' );
                    break;

                case 'confirm':
                    $progress.hide();
                    $( '#filePicker2' ).removeClass( 'element-invisible' );
                    $upload.text( '开始上传' );

                    stats = uploader.getStats();
                    if ( stats.successNum && !stats.uploadFailNum ) {
                        setState( 'finish' );
                        return;
                    }
                    break;
                case 'finish':
                    stats = uploader.getStats();
                    if ( stats.successNum ) {
                        // alert( '上传成功' );
                    } else {
                        // 没有成功的图片，重设
                        state = 'done';
                        location.reload();
                    }
                    break;
            }

            updateStatus();
        }

        uploader.onUploadProgress = function( file, percentage ) {
            var $li = $('#'+file.id),
                $percent = $li.find('.progress span');

            $percent.css( 'width', percentage * 100 + '%' );
            percentages[ file.id ][ 1 ] = percentage;
            updateTotalProgress();
        };

        uploader.onFileQueued = function( file ) {
            fileCount++;
            fileSize += file.size;

            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }

            addFile( file );
            setState( 'ready' );
            updateTotalProgress();
        };

        uploader.onFileDequeued = function( file ) {
            fileCount--;
            fileSize -= file.size;

            if ( !fileCount ) {
                setState( 'pedding' );
            }

            removeFile( file );
            updateTotalProgress();

        };
        uploader.on( 'all', function( type ) {
            var stats;
            switch( type ) {
                case 'uploadFinished':
                    setState( 'confirm' );
                    break;

                case 'startUpload':
                    setState( 'uploading' );
                    break;

                case 'stopUpload':
                    setState( 'paused' );
                    break;

            }
        });

        uploader.onError = function( code ) {
            alert( 'Eroor: ' + code );
        };

        $upload.on('click', function() {
            if ( $(this).hasClass( 'disabled' ) ) {
                return false;
            }
            /*判断不为空开始*/
            var m = 0;
            var n = 0;
            $('input[name="money"]').each(function(){
                if ($(this).val()=='') {
                    $(this).css('border','1px solid red');
                    m++;
                }else{
                    $(this).css('border','1px solid #bfbfbf');
                }
            });
            $('input[name="note"]').each(function(){
                if ($(this).val()=='') {
                    $(this).css('border','1px solid red');
                    n++;
                }else{
                    $(this).css('border','1px solid #bfbfbf');
                }
            });
            if (m>0 && n>0) {
                alert('有'+m+'个文件二维码未添加金额,有'+n+'个文件二维码未添加备注,请补充');
                return false;
            }else if(m>0 && (n==0)){
                alert('有'+m+'个文件二维码未添加金额,请补充');
                return false;
            }else if((m==0) && n>0){
                alert('有'+n+'个文件二维码未添加备注,请补充');
                return false;
            }
            /*判断不为空结束*/
            if ( state === 'ready' ) {
                uploader.upload();
            } else if ( state === 'paused' ) {
                uploader.upload();
            } else if ( state === 'uploading' ) {
                uploader.stop();
            }
        });

        $info.on( 'click', '.retry', function() {
            uploader.retry();
        } );

        $info.on( 'click', '.ignore', function() {
            alert( 'todo' );
        } );

        $upload.addClass( 'state-' + state );
        updateTotalProgress();
    });

})( jQuery );
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