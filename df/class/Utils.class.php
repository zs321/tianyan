<?php
class Utils{
	/*日志打印*/
	public static function dataRecodes($title,$data){
        $handler = fopen('class/result.txt','a+');
        $content = PHP_EOL."================".$title."===================\n";
        if(is_string($data) === true){
            $content .= $data."；";
        }
        if(is_array($data) === true){
            forEach($data as $k=>$v){
                $content .= $k."：".$v.'；';
            }
        }
        $flag = fwrite($handler,$content);
        fclose($handler);
        return $flag;
    }
}