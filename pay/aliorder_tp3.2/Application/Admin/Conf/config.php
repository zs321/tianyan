<?php
return array(
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
    'SYSTEM_TITLE' => '易游',
    'SYSTEM_VERSION'=> 'V1.0',
    'MANAGE_SESSION'=> 'managesystem',
    

    'ADMIN_MENU'=> array(
        array(
            'name'=>'数据统计',
            'url'=>'',
            'active'=>array('Main'),
            'icon'=>'ti-bar-chart',
            'menu'=>array(
				array('name'=>'数据统计','url'=>'main/index'),
				array('name'=>'二维码几率','url'=>'main/qrcode')
			)
        ),
        array(
            'name'=>'账号管理',
            'url'=>'',
            'active'=>array('Account'),
            'icon'=>'ion-person-stalker',
            'menu'=>array(
                array('name'=>'账号列表','url'=>'Account/accountList'),
                array('name'=>'添加账号','url'=>'Account/addEdit'),
                array('name'=>'金额范围','url'=>'Account/amountList'),
                array('name'=>'系统密钥','url'=>'Account/system'),
				array('name'=>'收款统计','url'=>'Account/numMoney'),
            )
        ),
        array(
            'name'=>'订单管理',
            'url'=>'',
            'active'=>array('Order'),
            'icon'=>'ti-shopping-cart-full',
            'menu'=>array(
                array('name'=>'全部订单','url'=>'Order/index'),
                array('name'=>'成功订单','url'=>'Order/successOrder'),
                array('name'=>'待支付订单','url'=>'Order/waitPay'),
                array('name'=>'失败订单','url'=>'Order/expireOrder'),
                array('name'=>'掉单处理','url'=>'Order/noticeFail'),
            )
        ),
        array(
            'name'=>'二维码管理',
            'url'=>'',
            'active'=>array('Qrcode'),
            'icon'=>'ti-settings',
            'menu'=>array(
                array('name'=>'二维码列表','url'=>'Qrcode/index'),
                array('name'=>'添加二维码','url'=>'Qrcode/add'),
                array('name'=>'有效期设置','url'=>'Qrcode/valiSet'),
            )
        )
    ),
);