<?php
if ($_SERVER['HTTP_HOST']=='localhost') {
	header('location:http://localhost/index.php/Admin/Index/index');
}else{
	header('location:http://ai.1899pay.com/index.php/Admin/Index/index');
}
