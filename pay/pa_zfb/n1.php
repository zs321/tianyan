<?php
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//ȥ�����һ��&�ַ�
	$arg = substr($arg,0,count($arg)-2);
	
	//�������ת���ַ�����ôȥ��ת��
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}



$url=createLinkstring($_POST)."\n";


file_put_contents("ceshi.txt", $url, FILE_APPEND);

?>