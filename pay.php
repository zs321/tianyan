<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<textarea name="myimg" rows="5" cols="60">sdasdasdasdada</textarea><br>
//文本框
<input type="button" value="点击复制以上代码" onclick="Copy(myimg)"/>
//按钮
然后是js代码
<script language="javascript">
    function
    Copy(target){
//新建Copy函数
        target.select();
//用select函数将文本内容选择
        js=myimg.createTextRange();
//createTextRange()用于获取myimg文本框内容
        js.execCommand("Copy"); //execCommand("Copy")将js里面的字符串复制到剪切板。
        alert("复制成功!");
    }
</script>
</html>
