<?php
     

 

function Post($url, $post = null)  
{  
     $context = array();  
  
     if (is_array($post))  
     {  
         ksort($post);  
  
         $context['http'] = array  
         (     
  
             'timeout'=>60,  
             'method' => 'POST',  
             'content' => http_build_query($post, '', '&'),  
         );  
     }  
  
     return file_get_contents($url, false, stream_context_create($context));  
}  
  function post_curls($url, $post)
    {
        $curl = curl_init(); // ����һ��CURL�Ự
        curl_setopt($curl, CURLOPT_URL, $url); // Ҫ���ʵĵ�ַ
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // ����֤֤����Դ�ļ��
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // ��֤���м��SSL�����㷨�Ƿ����
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // ģ���û�ʹ�õ������
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // ʹ���Զ���ת
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // �Զ�����Referer
        curl_setopt($curl, CURLOPT_POST, 1); // ����һ�������Post����
		 $post=http_build_query($post, '', '&'); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post�ύ�����ݰ�
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // ���ó�ʱ���Ʒ�ֹ��ѭ��
        curl_setopt($curl, CURLOPT_HEADER, 0); // ��ʾ���ص�Header��������
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // ��ȡ����Ϣ���ļ�������ʽ����
        $res = curl_exec($curl); // ִ�в���
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//��ץ�쳣
        }
        curl_close($curl); // �ر�CURL�Ự
        return $res; // �������ݣ�json��ʽ
 
    }