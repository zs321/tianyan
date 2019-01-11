<?php
header("Content-type: text/html; charset=utf-8");

require_once 'inc.php';
use WY\app\model\Handleorder;

$r= xmlToArray(file_get_contents('php://input'));
		$james = fopen("ceshi.txt", "a+");
        fwrite($james, "\r\n" . date("Y-m-d H:i:s") ."  回调信息：".json_encode($r) . " \r\n");
        fclose($james);

$ordernumber=$r["spbillno"];
$paymoney=$r["tran_amt"]/100;





$handle=@new Handleorder($ordernumber,$paymoney);
$handle->updateUncard();

echo "success";


function xmlToArray($xml){ 
 
 //禁止引用外部xml实体 
 
libxml_disable_entity_loader(true); 
 
$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
 
$val = json_decode(json_encode($xmlstring),true); 
 
return $val; 
 
} 



?>
