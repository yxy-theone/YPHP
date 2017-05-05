<?php
function p($var)
{
	if(is_bool($var)){
		var_dump($var);
	}else if(is_null($var)){
		var_dump(NULL);
	}else{
		echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px #aaa solid;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($var,true)."</pre>";
	}
}

/**
 * 字符串过滤
 * 1.去前后空格 2.转成html
 */
function _htmlspecialchars($str){
    return htmlspecialchars(trim($str));
}

/**
 * 实例化模型类 M方法
 */
function M($model = null)
{
    static $_cache = [];
    if (!is_null($model) && isset($_cache[$model])) return $_cache[$model];
    $file_name = ROOT_PATH.'/model/'.$model.'Model.php';
    if (!file_exists($file_name)){
        exit("找不到模型文件".$model);
    }else{
        $path_array=explode("/",$model);
        $db_index=$path_array[0];
        $class_name = 'model\\'.$db_index.'\\'.$path_array[1].'Model';
        if (!class_exists($class_name)){
            exit('Model Error:  Class '.$class_name.' is not exists!');
        }else{
            return $_cache[$model] = new $class_name($db_index);
        }
    }
}

/**
 * 不显示信息直接跳转
 * @param string $url
 */
function redirect($url){
    header('Location: '.$url);
    exit();
}

/*
 * 获取上一步来源地址
 */
function get_referer(){
    return empty($_SERVER['HTTP_REFERER'])?'/':$_SERVER['HTTP_REFERER'];
}

/*
 * 令牌
 */
function csrf_token(){
    if (!$_SESSION['_csrf_token']) {
        $_SESSION['_csrf_token'] = md5(uniqid());
    }
    return $_SESSION['_csrf_token'];
}

/*
 * 令牌输入框
 */
function csrf_token_input(){echo "<input type='hidden' name='_csrf_token' value='". csrf_token() ."' />";}

/*
 * 检测提交
 * _csrf验证
 */
function chksubmit($refresh = true){
    $result=!empty($_REQUEST['_csrf_token'])&&!empty($_SESSION['_csrf_token'])&&$_REQUEST['_csrf_token']===$_SESSION['_csrf_token'];
    if ($refresh) {
        $_SESSION['_csrf_token']=null;
    }
    return $result;
}

/*
 * JSON方式输出
 */
function json_output($code=0,$is_success=true,$data=""){
    @header("Content-Type: application/json; charset=utf-8");
    //@header('Access-Control-Allow-Origin:*'); //*代表可访问的地址，可以设置指定域名
    @header('Access-Control-Allow-Methods:POST,GET');
    exit(json_encode(array("code"=>$code,"success"=>$is_success,"data"=>$data),JSON_UNESCAPED_UNICODE));
}

//获取真实IP
function getIp(){
    if( !empty($_SERVER["HTTP_ALI_CDN_REAL_IP"]) ){
       $cip = $_SERVER["HTTP_ALI_CDN_REAL_IP"];
    }elseif(!empty($_SERVER["HTTP_CLIENT_IP"])){
       $cip = $_SERVER["HTTP_CLIENT_IP"];
    }else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
       $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }else if(!empty($_SERVER["REMOTE_ADDR"])){
       $cip = $_SERVER["REMOTE_ADDR"];
    }else{
       $cip = '';
    }
    preg_match("/[\d\.]{7,15}/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);
    return $cip;
}

/**
 * 判断目录是否存在，不存在且非文件则创建
 */
function mkFolder($path)  {  
    if(!is_readable($path)){  
        is_file($path) or mkdir($path,0700);  
    }  
}

/**
 * 返回相对时间
 * @param  string $date 时间戳
 * @return string       相对时间
 */
function getRelativeTime($date) {
    $diff = time() - $date;
    if ($diff<60)
        return $diff."秒前";
    $diff = round($diff/60);
    if ($diff<60)
        return $diff."分钟前";
    $diff = round($diff/60);
    if ($diff<24)
        return $diff."小时前";
    $diff = round($diff/24);
    if ($diff<7)
        return $diff."天前";
    $diff = round($diff/7);
    if ($diff<4)
        return $diff."星期前";
    return "on ".date("Y-m-d", $date);
}

/**
 * 使用PHPMailer发送邮件
 * @param  array  $config     PHPMailer的配置信息
 * @param  array  $to         接收者数组
 * @param  string $title      标题
 * @param  string $content    邮件内容,可以是HTML
 * @param  array  $attachment 附件数组
 */
function sendMail($config,$to,$title,$content,$attachment=array()){
    $mail = new \PHPMailer;
    $mail->isSMTP();
    $mail->Host = $config['MAIL_HOST'];
    $mail->SMTPAuth = $config['MAIL_SMTPAUTH'];
    $mail->Username = $config['MAIL_USERNAME'];
    $mail->Password = $config['MAIL_PASSWORD'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = $config['MAIL_PORT'];
    $mail->CharSet = $config['MAIL_CHARSET'];
    $mail->setFrom($config['MAIL_FROM'], $config['MAIL_FROMNAME']);
    foreach($to as $val){
        $mail->addAddress($val);
    }
    $mail->addReplyTo($config['MAIL_REPLYTO']);
    $mail->addCC($config['MAIL_CC']);
    $mail->addBCC($config['MAIL_BCC']);
    if(!empty($attachment)){
        foreach($attachment as $val){
            $mail->addAttachment($val);
        }
    }
    $mail->isHTML($config['MAIL_ISHTML']);
    $mail->Subject = $title;
    $mail->Body = $content;
    $mail->AltBody = strip_tags($content);
    if (!$mail->send()) {
        throw new \Exception('邮件发送失败！请检查相关配置！');
    }
}