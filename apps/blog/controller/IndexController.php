<?php
namespace apps\blog\controller;

use framework\lib\Route;
use framework\lib\Log;

class IndexController extends \framework\Controller
{
	public $login_sign = 'adminname';//指定登录标识 默认username

	public function indexOp()
	{
		$oss = \framework\lib\Conf::get('params','web')['oss-blog'];
		$oss_url = 'http://'.$oss['hotel_bucket'].'.'.$oss['end_point'].'/';
		$condition = [];
		$condition["state"]=0;
        $category = intval($_GET['category']);
        if($category > 0){
            $condition["category"]=$category;
        }
		$articles = M('me/article')->getArticleList($condition,"*",10,"sort DESC,id DESC");
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$this->display('index',[
			'oss_url'=>$oss_url,
			'articles'=>$articles,
			'categorys'=>$categorys,
			'tags'=>$tags
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
	 * 获取文章列表
	 */
	public function getArticlesOp(){
		$oss = \framework\lib\Conf::get('params','web')['oss-blog'];
		$oss_url = 'http://'.$oss['hotel_bucket'].'.'.$oss['end_point'].'/';
		$condition = [];
		$condition["state"]=0;
        $category = intval($_GET['category']);
        if($category > 0){
            $condition["category"]=$category;
        }
        $articles = M('me/article')->getArticleList($condition,"id,title,icon,category,tags,sort,recommend,astrict,createtime,updatetime",10,"sort DESC,id DESC");
        $categorys = M('me/article_category')->getCategory();
        if (empty($articles))
        	json_output(0,false,'没有更多');
        $data = array();
        foreach ($articles as $k =>  $v) {
        	$articles[$k]['RelativeTime'] = getRelativeTime($v['createtime']);
        	$articles[$k]['category'] = $categorys[$v['category']];
        	$articles[$k]['icon'] = $oss_url.$v['icon'];
        	$articles[$k]['url'] = Route::urlManager('index/detail',['id'=>$v['id']]);
        }
        json_output(0,true,$articles);
	}

	/**
	 * 文章详情
	 */
	public function detailOp(){
		$id = intval($_GET['id']);//文章id
		if ($id <= 0) {
			Log::record("黑客尝试,来源ip:".getIp(),'DANGER');
			throw new \Exception("我会找到你的", 9);
		}
		$article = M('me/article')->getArticle(['id'=>$id]);
		if (empty($article))
			throw new \Exception("找不到这篇文章", 404);
		if ($article['astrict'] == 1 && empty($_SESSION['adminname'])) {
			$article['article_content'] = '<h2 class="text-danger">Sorry,这篇文章只有管理员才能查看</h2>';
		}
		$next_article = M('me/article')->getArticle(['id'=>['gt',$id]]);
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$this->display('detail',[
			'article'=>$article,
			'categorys'=>$categorys,
			'tags'=>$tags
		]);
	}
}