<?php
require_once 'inc.php';
use WY\app\model\Handleorder;
 echo '付款成功';die;
if($sign==$mysign){
        echo '付款成功';
 		@new Handleorder($sdorderno,$total_fee);
} else {
    echo 'sign error';
}
?>
