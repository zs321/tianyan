<?php
header("Content-type: text/html; charset=utf-8");  

$oid = $_GET['oid'];
$amt = $_GET['amt'];
         
?>


<html>

	<head>

	
	
	</head>

	<body>
		
		<form action="OpenDemo.php" method="get" target="_blank">
			
			开户姓名：<input type="text" name="uname" value=""><br>

			银行卡号：<input type="text" name="card" value=""><br>

			身份证号：<input type="text" name="id" value=""><br>

			预留手机号：<input type="text" name="phone" value=""><br>
			
			<input type="hidden" name="oid" value="<?php echo $oid;?>">
			<input type="hidden" name="amt" value="<?php echo $amt;?>">
			
			<input type="submit" value="提交">
		</form>
	
	</body>
</html>