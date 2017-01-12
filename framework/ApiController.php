<?php
namespace framework;

/**
 * 接口控制器基类
 */
class ApiController
{
	public $act;
	public $op;
	public $identy = [];//存放用户信息
	public $code_info = [
		'200'=>'OK 成功',
		'400'=>'错误请求',
		'401'=>'无权限',
		'403'=>'禁止访问',
		'404'=>'URI不存在',
		'500'=>'非法请求',
		'503'=>'服务器繁忙',
	];

	public function __construct($act,$op)
	{
		$this->$act = $act;
		$this->op = $op;
		if(in_array($this->op, $this->behavior())||in_array('ALL_OPTIONS',$this->behavior())){
			//根据acesstoken获取用户数据，并注册identy信息
			if(empty($this->identy)){
				throw new \Exception('请登录',401);
			}
			if(!$this->behavior()){
				throw new \Exception('权限不足',500);
			}
		}
	}

	/**
	 * 执行方法前,那些方法需要登录状态才能访问
	 * @return [array] [一维数组]
	 */
	protected function behavior()
	{
		return [];
	}

	/**
	 * 登录后。对于权限的验证，预执行方法
	 * @return bool 是否拥有该路由权限
	 */
	protected function before()
	{
		return true;
	}

	protected function output($data='',$is_success=true,$code=200){
		if (empty($data)) {
			$data = $this->code_info[$code];
		}
		@header("Content-Type: application/json; charset=utf-8");
	    //@header('Access-Control-Allow-Origin:*'); //*代表可访问的地址，可以设置指定域名
	    @header('Access-Control-Allow-Methods:POST,GET');
	    exit(json_encode(array("code"=>$code,"success"=>$is_success,"data"=>$data),JSON_UNESCAPED_UNICODE));
	}
}