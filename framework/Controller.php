<?php
namespace framework;

/**
 * 控制器基类
 */
class Controller
{
	public $act;
	public $op;
	public $_csrf = true;//是否开启_csrf验证
	public $login_sign = 'username';//登录标识 获取此SESSION的值
	public $layout = 'main';
	
	public function __construct($act,$op)
	{
		$this->act = $act;
		$this->op = $op;
		if(in_array($this->op, $this->behavior())||in_array('ALL_OPTIONS',$this->behavior())){
			if(empty($_SESSION[$this->login_sign])){
				throw new \Exception('请登录',401);
			}
			if(!$this->before()){
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

	/**
	 * 调出视图 带布局文件，默认layout.php
	 */
	public function display($file,$assign = null)
	{
		$file = ROOT_PATH.'/apps/'.APP.'/views/'.$this->act.'/'.$file.'.php';
		$layout_file = ROOT_PATH.'/apps/'.APP.'/views/layout/'.$this->layout.'.php';
		if (is_array($assign)) {
			extract($assign);
		}
		if (!is_file($file)||!is_file($layout_file)) throw new \Exception("页面被狗吃了", 404);
		require_once $file;
		$content = ob_get_clean();// 得到当前缓冲区的内容并删除当前输出缓冲区内容
		require_once $layout_file;
	}

	/**
	 * 调出视图 不带布局文件
	 */
	public function onlyDisplay($file,$assign = null)
	{
		$file = ROOT_PATH.'/apps/'.APP.'/views/'.$this->act.'/'.$file.'.php';
		if ($assign) {
			extract($assign);
		}
		if (!is_file($file)) throw new \Exception("页面被狗吃了", 404);
		require_once $file;
	}
}