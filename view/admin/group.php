<?php require_once 'header.php' ?>
    <h3>
    <span class="current">
        通道分组列表
    </span>
        &nbsp;/&nbsp;
        <span>
        添加分组
    </span>
    </h3>
    <br>

    <div class="set set0">
<!--        -->
<!--        <div class="panel panel-default">-->
<!--            <div class="panel-body">-->
<!--                <div class="btn-group">-->
<!--                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"-->
<!--                            aria-haspopup="true" aria-expanded="false">-->
<!--                        切换网关-->
<!--                        <span class="caret">-->
<!--                    </span>-->
<!--                    </button>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        --><?php //foreach($acp as $key=>
//                                      $val):?>
<!--                            <li>-->
<!--                                <a href="--><?php //echo $this->dir?><!--acc/change/--><?php //echo $val['code']?><!--">-->
<!--                                    --><?php //echo $val['name']?>
<!--                                </a>-->
<!--                            </li>-->
<!--                        --><?php //endforeach;?>
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->



        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr class="info">
                    <th>
                        分组编号
                    </th>
                    <th>
                       分组名称
                    </th>
                    <th>
                        接入商户
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>


                <?php if($lists):?>
                    <?php foreach($lists as $key=>$val):?>
                        <tr data-id="<?php echo $val['group_id']?>">
                            <td>
                                <?php echo $val['group_id']?>
                            </td>
                            <td>
                                <?php echo $val['group_name']?>
                            </td>
                            <td>
                                <?php
                                    if($val["id"]){
                                        ?>
                                        <span class="green">
                                            <?php echo $val['username']."(".$val['id'].")"?>
                                        </span>
                                        <?php
                                    }else{
                                        ?>
                                        <span class="red">此通道分组暂未指定用户被使用</span>
                                        <?php
                                    }
                                ?>

                            </td>
                            <td>
                                <a href="<?php echo $this->dir?>group/edit/<?php echo $val['group_id']?>" data-toggle="tooltip"
                                   title="编辑">
                                    <span class="glyphicon glyphicon-edit">
                                    </span>
                                </a>
                                &nbsp;
                                <a href="javascript:;" onclick="del(<?php echo $val['group_id']?>,'<?php echo $this->dir?>group/del')"
                                   data-toggle="tooltip" title="删除">
                                    <span class="glyphicon glyphicon-trash">
                                    </span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="10">
                            no data.
                        </td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .form-group>span.col-md-4{font-size:0.9em;color:#6B6D6E;line-height: 30px}
    </style>
    <div class="set set1 hide">
        <form class="form-ajax form-horizontal" action="<?php echo $this->dir?>group/save"
              method="post">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">
                    通道分组名称：
                </label>
                <div class="col-md-4">
                    <input type="text" name="group_name" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label  for="channelid[]" class="col-md-2 control-label">通道列表</label>
                <div class="col-md-4">
                    <?php
                    foreach ($tongdao as $row){
                        echo "<input type='checkbox' name='channelid[]' value='".$row['id']."'>".$row["name"]."&emsp;";
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
    </div>
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
                    }else{
                        $('.gateway').hide();
                        $('.gateway select').html('');
                    }
                });
        });
    </script>
<?php require_once 'footer.php' ?>