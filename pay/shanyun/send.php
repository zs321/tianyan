<?php


include 'inc.php';	
		
	
$params['secretKey'] =$config['secretKey'];

$url = $config['gateway'];
$params['pay_type'] = "1";
$params['orderid'] = $_REQUEST['orderid'];
$params['merchant_id'] = $config['merchant_id'];
$params['amount'] = $_REQUEST['price'];
$params['buyer'] =  $_REQUEST['orderid'];
$params['notify_url'] = $config['notify_url'];
$params['title'] ="pay";
$params['params'] = "";
$params['return_url'] = $config['return_url'];

$paramString  = getParamString($params);

form_p($url, $paramString);	


function form_p($url, $paramString)
{
    $xx = '';
    foreach ($paramString as $key => $value) {
        $xx .= '<input type="hidden" name="' . $key . '" value="' . $value . '" /><br />';
    }
    echo '
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
            <title>Ö§¸¶..</title>
        </head >
        <body>
            <form id="myfrom" name="myfrom" method="post" onsubmit="return sumbitTest();" action="' . $url . '">  
                ' . $xx . '
            </form>    
            <script language = "JavaScript" type = "text/javascript">
                setTimeout("document.myfrom.submit()",1000) ;//´Ë´¦Îª1ÃëÌá½»£¬¿É¸ü¸ÄÎªÁ¢¿ÌÌá½»¡£
            </script>
        </body>
    </html>
    ';
}