    <?php require_once 'header.php' ?>
 <div class="row wrapper wrapper-content">
            <div class="row">
                <div class="col-md-12">
                                           <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <em class="fa fa-list">
                                    </em>
                             <?php echo $title ?>
                                </div>
                            </div>
                       
							
							
       
        <?php if($this->
            userData['is_state']=='0'):?>
            <div class="alert alert-warning" style="margin-bottom:0;">
                <span class="glyphicon glyphicon-info-sign">
                </span>
                &nbsp;您当前的账号状态为
                <span class="label label-danger">
                    未审核
                </span>
                ，请继续完善注册信息然后联系客服以便审核。
            </div>
            <?php endif;?>
			 <div class="panel-body">
                <div class="content-box">
                    <?php if($this->userData['is_state']=='0'):?>
                        <div class="text-center">
                            <a href="/member/userinfo" class="btn btn-warning">
                                <span class="glyphicon glyphicon-circle-arrow-right">
                                </span>
                                &nbsp;继续完善注册信息
                            </a>
                        </div>
                        <?php else:?>
						     <div class="panel-body">
						<div class="table-responsive">
                                        <table class="table table-bordered">
										                                <thead>
                                    <tr>
                                        <th>
                                            通道名称
                                        </th>
                                        <th>
                                            结算费率
                                        </th>
                                        <th>
                                            当前状态
                                        </th>
                                        <th>
                                            操作
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($userprice):?>
                                        <?php foreach($userprice as $key=>$val):?>


										 <?php if($val[ 'is_state']=='0' ):?>

                                            <tr>
                                                <td>
                                                    <?php echo $val[ 'name']?>
                                                </td>
                                                <td>
                                                    <?php echo $val[ 'uprice']?>
                                                </td>
                                                <td class="label<?php echo $val['id']?>">
                                                    <?php if($val[ 'is_state']=='0' ):?>
                                                        <span class="label label-success">
                                                  		           <i class="fa fa-check-circle"></i>
                                                        </span>
                                                        <?php else:?>
                                                            <span class="label label-danger">
                                            		<i class="fa fa-times-circle"></i>
                                                            </span>
                                                            <?php endif;?>
                                                </td>
                                                <td class="btn<?php echo $val['id']?>">
                                                    <?php if($val[ 'is_state']=='0' ):?>
                                                        <a href="javascript:;" onclick="alert('请联系平台管理员开通！')" class="btn btn-default btn-sm">
                                                            关闭
                                                        </a>
                                                        <?php else:?>
                                                            <a href="javascript:;" onclick="alert('请联系平台管理员开通！')" class="btn btn-default btn-sm">
                                                                打开
                                                            </a>
                                                            <?php endif;?>
                                                </td>
                                            </tr>



											<?php endif;?>



                                            <?php endforeach;?>
                                                <?php endif;?>
                                </tbody>
                            </table>
							</div>
                            <?php endif;?>
                </div>
    </div>  
	</div>
	   </div>
	   	   </div>
		   	   </div>
			    </div>
    <?php require_once 'footer.php' ?>
	



