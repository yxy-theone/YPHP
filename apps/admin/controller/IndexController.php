<?php
namespace apps\admin\controller;

use framework\lib\Validate;
use framework\lib\Log;

class IndexController extends \framework\Controller
{
	public $login_sign = 'adminname';//指定登录标识 默认username

	public function behavior()
	{
		return ['index','logout'];
	}

	public function indexOp()
	{
		$this->display('index',[
			'name'=>'小样',
			'play'=>'ball'
		]);
	}

	/**
	 * 自定义的错误处理方法
	 * 可以根据$_GET['code']来判断当前错误状态 之后的跳转路由等
	 */
	public function errorOp(){
		$code = intval($_GET['code']);
		switch ($code) {
			case '404':
				$reffer_url = "";
				break;
			default:
				$reffer_url = get_referer();
				break;
		}
		$this->onlyDisplay('error',[
			'code'=>$code,
			'message'=>$_GET['message'],
			'reffer_url'=>$reffer_url
		]);
	}

	/**
	 * 密码登录
	 */
	public function loginOp()
	{	
		if(chksubmit(false)){//登录操作
			$phone = $_POST['phone'];
			if (!Validate::isMobile($phone)) {
				json_output(1,false,'手机号码格式不正确');
			}
			$condition = ['phone'=>$phone];
			$admin = M("me/admin")->getAdmin($condition,'id,adminname,password,is_admin,login_fail_times');
			if ($admin) {
				//账号state 1正常 2停用
				if ($admin['state'] == 2) {
					json_output(2,false,'账号异常');
				}
				if($admin['login_fail_times'] > 5){
					json_output(2,false,'账号异常,请使用手机验证码登录');
				}
				if(!password_verify($_POST["password"],$admin["password"])){
					M("me/admin")->editAdmin(["phone"=>$phone],['login_fail_times'=>['exp','login_fail_times+1']]);//登录失败次数+1
					Log::record('密码登录错误  手机号:'.$phone.',  密码:'.$_POST["password"]);
                    json_output(1,false,'用户名或密码错误');
                }
    			$login_data=[];
                $login_data["last_login_time"]=time();
                $login_data["last_login_ip"]=getIp();
                $login_data["login_fail_times"]=0;
			}else{
				json_output(1,false,'用户名或密码错误');
			}
            if(!M("me/admin")->editAdmin(["id"=>$admin["id"]],$login_data)){
            	//修改数据失败，报警
            	Log::record($phone.'登录成功后数据更新失败');
            }
            $_SESSION["id"]=$admin["id"];
            $_SESSION["adminname"]=$admin["adminname"];
            $_SESSION["is_admin"]=$admin["is_admin"]=="0"?false:true;
            json_output(0,success,'登录成功');
		}
		$this->onlyDisplay('login');
	}

	/**
	 * 发送登录验证码
	 */
	public function send_codeOp(){
		$phone = $_POST['phone'];
		if (!Validate::isMobile($phone)) {
			json_output(1,false,'用户名或密码错误');
		}
		json_output(0,true,'发送成功');
	}

	/**
	 * 验证码登录
	 */
	public function captcha_loginOp(){
		if(chksubmit(false)){//登录操作
			$phone = $_POST['phone'];
			if (!Validate::isMobile($phone)) {
				json_output(1,false,'用户名或密码错误');
			}
			$captcha = \framework\lib\conf::get('captcha','web');
			if ($_POST['captcha'] != $captcha) {
				Log::record('验证码登录错误  手机号:'.$phone.',  验证码:'.$_POST["captcha"]);
				json_output(1,false,'验证码错误');
			}
			$condition = ['phone'=>$phone];
			$admin = M("me/admin")->getAdmin($condition,'id,adminname,is_admin');
			if ($admin) {
				//账号state 1正常 2停用
				if ($admin['state'] == 2) {
					json_output(2,false,'账号异常');
				}
    			$login_data=[];
                $login_data["last_login_time"]=time();
                $login_data["last_login_ip"]=getIp();
			}else{
				json_output(1,false,'用户名或密码错误');
			}
            M("me/admin")->editAdmin(["id"=>$admin["id"]],$login_data);
            $_SESSION["id"]=$admin["id"];
            $_SESSION["adminname"]=$admin["adminname"];
            $_SESSION["is_admin"]=$admin["is_admin"]=="0"?false:true;
            json_output(0,success,'登录成功');
		}
	}

	/**
	 * 退出 清空session
	 */
	public function logoutOp()
	{
		session_unset();
        session_destroy();
		redirect(\framework\lib\Route::urlManager('index/login'));
	}
}