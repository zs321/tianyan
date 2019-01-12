<?php require_once 'header.php' ?>
    <h3>
        <span class="current">
            通道分组列表
        </span>
        &nbsp;/&nbsp;
        <span>
            修改通道
        </span>
    </h3>
    <br>
    <style>
        .form-group>span.col-md-4{font-size:0.9em;color:#6B6D6E;line-height: 30px}
    </style>
    <form class="form-ajax form-horizontal" action="<?php echo $this->dir?>group/editsave/<?php echo $data;?>"
    method="post">
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">
                通道分组名称：
            </label>
            <div class="col-md-4">
                <input type="text" name="group_name" class="form-control" required value="<?php echo $group['group_name']?>">
            </div>
        </div>
        <div class="form-group">
            <label  for="channelid[]" class="col-md-2 control-label">通道列表</label>
            <div class="col-md-4">
                <?php
                    foreach ($tongdao as $row){
                        if($row["ischecked"] == true){
                            echo "<input type='checkbox' name='channelid[]' value='".$row['id']."' checked >".$row["name"]."&emsp;";
                        }else{
                            echo "<input type='checkbox' name='channelid[]' value='".$row['id']."'>".$row["name"]."&emsp;";
                        }
                    }
                ?>
            </div>
        </div>

        <div class="form-group ">
            <div class="col-md-offset-2 col-md-4">
                <button type="submit" class="btn btn-success">
                    &nbsp;
                    <span class="glyphicon glyphicon-save">
                    </span>
                    &nbsp;保存设置&nbsp;
                </button>
            </div>
        </div>
    </form>
    <script>
        $('[name=acpcode]').change(function() {
            if ($(this).val() == '') {
                $('.gateway').hide();
                $('.gateway select').html('');
            }
            $.post('<?php echo $this->dir?>acc/getAcl', {
                acpcode: $(this).val()
            },
            function(ret) {
                if (ret) {
                    $('.gateway').removeClass('hide').show();
                    $('.gateway select').html(ret);
                }
            });
        });
    </script>
    <?php require_once 'footer.php' ?>