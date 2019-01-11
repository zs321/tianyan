<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统提示</title>
    <link href='__CSS__/26707bedc25a4344bb99d7e1215825f4.css' rel='stylesheet' type='text/css'>
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet">
    <link href="__CSS__/nifty.min.css" rel="stylesheet">
    <link href="__CSS__/ionicons.min.css" rel="stylesheet">
</head>
<body>
<div id="container" class="cls-container">
    <div class="cls-content">
        <?php if(isset($message)) {?>
        <h1 class="error-code text-info"><i class="ion-happy-outline"></i></h1>
        <p class="text-main text-semibold text-lg text-uppercase">操作成功</p>
        <div class="pad-btm text-semibold">
        <?php echo($message); ?>
        </div>
        <?php }else{?>
        <h1 class="error-code text-info"><i class="ion-sad-outline"></i></h1>
        <p class="text-main text-semibold text-lg text-uppercase">操作失败</p>
        <div class="pad-btm text-semibold">
        <?php echo($error); ?>
        </div>
        <?php }?>
        <div class="pad-top">
            页面将在 <b id="wait" style="color: #ff0000;">10</b> 自动<a id="href" href="<?php echo($jumpUrl); ?>">跳转</a>
        </div>
        <hr class="new-section-sm">
        <div class="pad-top">
            <a class="btn btn-success" style="color: #FFFFFF" href="<?php echo($jumpUrl); ?>">点击立即跳转 ...</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>