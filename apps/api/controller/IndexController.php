<?php
namespace apps\api\controller;

class IndexController extends \framework\ApiController
{
	public function indexOp()
	{
		p('it is index');
	}

	public function behavior()
	{
		return [];
	}

	public function errorOp()
	{
		$code = intval($_GET['code']);
		switch ($code) {
			case '404':
				$message = '资源不存在';
				break;
			default:
				$message = '';
				break;
		}
		$this->output($message,false,$code);
	}

	public function loginOp()
	{
		$this->output('请登录',false,401);
	}
}