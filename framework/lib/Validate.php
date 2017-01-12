<?php
namespace framework\lib;
/**
 * 验证类
 */
class Validate{

	//是否身份证
	public static function isIDCard($id){
		$len = strlen($id);
		if($len != 18) {
			return 0;
		}
		$a=str_split($id,1);
		$w=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
		$c=array(1,0,'X',9,8,7,6,5,4,3,2);
		$sum = 0;
		for($i=0;$i<17;$i++){
			$sum= $sum + $a[$i]*$w[$i];
		}
		$r=$sum%11;
		$res=$c[$r];
		if ($res == $a[17]) {
			return 1;
		} else {
			return 0;
		}
		//return preg_match("/(^\d{15}$)|(^\d{17}\d|x|X$)/",$str);
	}

	//是否Email
    public static function isEmail($str){
		return preg_match('/^([.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\\.[a-zA-Z0-9_-])+/',$str);
	}

	//是否手机号
	public static function isMobile($str){
		return preg_match("/^13[0-9]{9}$|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/",$str);
	}
	
	//验证是否是6-12位字母或数字,一些特殊字符(_-?.)组成的密码
	public static function isPassword($str){
		return preg_match("/^[a-zA-Z0-9_\-?\.]{6,12}$/",$str);
	}

	/**
	 * 判断中文姓名(UTF-8,2-5位)
	 * @param  str  $name 真实姓名
	 * @return boolean
	 */
	public static function isChineseName($name){
		return preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,5}$/', $name);
	}

	/**
	 * 是否为安全字符串
	 * 汉子、字母、数字、#_()（）
	 */
	public static function isSafeChar($char){
		return preg_match('/^[\x80-\xffa-zA-Z0-9_#()（）]+$/', $char);
	}

	/**
	 * 是否为文件名
	 */
	public static function isFileName($filename){
		return preg_match('/^[a-zA-Z0-9_.-]+$/', $filename);
	}

	/**
	 * 是否为微信号
	 */
	public static function isWeixinNumber($number){
		return preg_match('/^[a-zA-Z0-9_-]+$/', $number);
	}

	/**
	 * 是否为类别 值允许字母数字或者&nbsp;
	 */
	public static function isFilterPostData($str){
		return preg_match('/^[a-zA-Z0-9&nbsp;]+$/', $str);
	}

	/**
	 * 是否为颜色格式 如#000000
	 */
	public static function isColor($color){
		return preg_match('/^#[a-zA-Z0-9]{6}$/', $color);
	}
}