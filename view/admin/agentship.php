<?php require_once 'header.php' ?>
    <h3>
        <span class="current">
            代理结算
        </span>
    </h3>
    <div style="position:absolute;text-align:right;margin-top:-30px;margin-left:100px;"
    class="red">
        (点击结算后，未结订单收入将结算到账户余额中，然后等待付款。未结收入不含当天的订单)
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline" action="" method="get">
                <div class="form-group">
                    <input type="text" class="form-control" name="kw" placeholder="代理名/编号"
                    value="<?php echo $search['kw']?>">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-search">
                    </span>
                    &nbsp;立即查询
                </button>
            </form>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr class="info">
                <th width="100">
                    <a href="?by=id&sort=<?php echo $by=='id' && $sort ? 0 : 1 ?>">
                        用户编号
                        <span class="glyphicon glyphicon-triangle-<?php echo $by=='id' && $sort ? 'bottom' : 'top'?>">
                        </span>
                    </a>
                </th>
                <th>
                    用户名
                </th>
                <th>
                    结算类型
                </th>
                <th>
                    <a href="?by=paid&sort=<?php echo $by=='paid' && $sort ? 0 : 1 ?>">
                        已付金额
                        <span class="glyphicon glyphicon-triangle-<?php echo $by=='paid' &&$sort ? 'bottom' : 'top'?>">
                        </span>
                    </a>
                </th>
                <th>
                    <a href="?by=unpaid&sort=<?php echo $by=='unpaid' &&$sort ? 0 : 1 ?>">
                        账户余额
                        <span class="glyphicon glyphicon-triangle-<?php echo $by=='unpaid' &&$sort ? 'bottom' : 'top'?>">
                        </span>
                    </a>
                </th>
                <th>
                    未结订单数
                </th>
                <th>
                    未结订单收入
                </th>
                <th>
                    操作
                </th>
            </tr>
        </thead>
        <tbody>
         <?php if($lists):?><?php foreach($lists as $key=>$val):$where=array('fields'=>'is_state=? and is_ship_agent=? and agentid=? and gprice>? and addtime<?','values'=>array(1,0,$val['id'],0,strtotime(date('Y-m-d'))),);$total_unship_count=$this->model()->select()->from('orders')->where($where)->count();$total_unship_income=$this->model()->select(array('income'=>'realmoney*(gprice-uprice)'))->from('orders')->where($where)->sum();?><tr data-id="<?php echo $val['id']?>"><td><?php echo $val['id']?></td><td><div class="dropdown"><a href="javascript:;" class="dropdown-toggle" id="menulist" data-toggle="dropdown" aria-expanded="true"><?php echo $val['username']?><span class="caret"></span><ul class="dropdown-menu" aria-labelledby="menulist"><li><a href="javascript:;" onclick="showContent('基本信息','<?php echo $this->dir?>users/getuserinfo/<?php echo $val['id']?>')"><span class="glyphicon glyphicon-triangle-right"></span>&nbsp;基本信息
                            </a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="showContent('设置分成比率','<?php echo $this->dir?>users/getuserprice/<?php echo $val['id']?>')">
                                    <span class="glyphicon glyphicon-triangle-right">
                                    </span>
                                    &nbsp;分成比率
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="showContent('收款信息','<?php echo $this->dir?>users/getbadata/<?php echo $val['id']?>')">
                                    <span class="glyphicon glyphicon-triangle-right">
                                    </span>
                                    &nbsp;收款信息
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="showContent('接入信息','<?php echo $this->dir?>users/getapidata/<?php echo $val['id']?>')">
                                    <span class="glyphicon glyphicon-triangle-right">
                                    </span>
                                    &nbsp;接入信息
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->dir?>userlogs/?uname=<?php echo $val['username']?>">
                                    <span class="glyphicon glyphicon-triangle-right">
                                    </span>
                                    &nbsp;登陆日志
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->dir?>users/?superid=<?php echo $val['id']?>">
                                    <span class="glyphicon glyphicon-triangle-right">
                                    </span>
                                    &nbsp;下级用户
                                </a>
                            </li>
                            </ul>
                            </div>
                            </td>
                            <td>
                                <?php echo $this->setConfig->shipType($val['ship_type'])?>
                            </td>
                            <td class="green">
                                <?php echo $val[ 'paid']?>
                                    元
                            </td>
                            <td class="red">
                                <?php echo $val[ 'unpaid']?>
                                    元
                            </td>
                            <td>
                                <?php echo $total_unship_count?>
                                    条&nbsp;
                                    <a href="<?php echo $this->dir?>orders?is_state=1&is_ship_agent=0&agentid=<?php echo $val['id']?>"
                                    target="_blank">
                                        <span class="glyphicon glyphicon-share-alt" data-toggle="tooltip" title="查看订单列表">
                                        </span>
                                    </a>
                            </td>
                            <td class="orange">
                                <?php echo $total_unship_income[ 'income']?>
                                    元
                            </td>
                            <td>
                                <a href="javascript:;" onclick="showContent('付款给商户','<?php echo $this->dir?>agentship/ship/<?php echo $val['id']?>');"
                                class="btn btn-danger btn-sm">
                                    <span class="glyphicon glyphicon-credit-card">
                                    </span>
                                    &nbsp;结算
                                </a>
                            </td>
                            </tr>
                            <?php endforeach;?>
                                <?php else:?>
                                    <tr>
                                        <td colspan="8">
                                            no data.
                                        </td>
                                        <?php endif;?>
        </tbody>
    </table>
    <?php echo $lists ? $pagelist : ''?>
        <?php require_once 'footer.php' ?>