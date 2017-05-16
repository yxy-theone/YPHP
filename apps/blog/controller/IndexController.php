<?php
namespace apps\blog\controller;

use framework\lib\Route;
use framework\lib\Log;

class IndexController extends \framework\Controller
{
	public $login_sign = 'adminname';//指定登录标识 默认username

	public function indexOp()
	{	
		$m_article = M('me/article');
		$oss = \framework\lib\Conf::get('params','web')['oss-blog'];
		$oss_url = 'http://'.$oss['hotel_bucket'].'.'.$oss['end_point'].'/';
		$condition = [];
		$condition["state"]=0;
        $category = intval($_GET['category']);
        if($category > 0){
            $condition["category"]=$category;
        }
		$articles = $m_article->getArticleList($condition,"*",12,"sort DESC,id DESC");
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$recommend_articles = $m_article->getArticleList(['recommend'=>1,'astrict'=>0,'state'=>0],"id,title,icon,click",8,"id DESC");//推荐文章
		$new_articles = $m_article->getArticleList(['astrict'=>0,'state'=>0],"id,title,icon,click",8,"createtime DESC");//最新文章
		$this->display('index',[
			'oss_url'=>$oss_url,
			'articles'=>$articles,
			'categorys'=>$categorys,
			'tags'=>$tags,
			'recommend_articles'=>$recommend_articles,
			'new_articles'=>$new_articles
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
        $articles = M('me/article')->getArticleList($condition,"id,title,icon,category,tags,sort,recommend,astrict,createtime,updatetime",12,"sort DESC,id DESC");
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
		$m_article = M('me/article');
		$article = $m_article->getArticle(['id'=>$id]);
		$_GET['category'] = $article['category'];//详情页中文章分类影响导航
		if (empty($article)) throw new \Exception("找不到这篇文章", 404);
		$m_article->editArticle(['id'=>$id],['click'=>['exp','click+1']]);
		if ($article['astrict'] == 1 && empty($_SESSION['adminname'])) {
			$article['article_content'] = '<h2 class="text-danger">Sorry,这篇文章只有管理员才能查看</h2>';
		}
		$next_article = $m_article->order('id DESC')->getArticle(['id'=>['lt',$id]],'id,title');//下一篇
		$prev_article = $m_article->order('id ASC')->getArticle(['id'=>['gt',$id]],'id,title');//上一篇
		$oss = \framework\lib\Conf::get('params','web')['oss-blog'];
		$oss_url = 'http://'.$oss['hotel_bucket'].'.'.$oss['end_point'].'/';
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$recommend_articles = $m_article->getArticleList(['recommend'=>1,'astrict'=>0,'state'=>0],"id,title,icon,click",8,"id DESC");//推荐文章
		$new_articles = $m_article->getArticleList(['astrict'=>0,'state'=>0],"id,title,icon,click",8,"createtime DESC");//最新文章
		$this->display('detail',[
			'article'=>$article,
			'oss_url'=>$oss_url,
			'next_article'=>$next_article,
			'prev_article'=>$prev_article,
			'categorys'=>$categorys,
			'tags'=>$tags,
			'recommend_articles'=>$recommend_articles,
			'new_articles'=>$new_articles
		]);
	}

	/**
	 * PHPMailer 测试
	 */
	public function emailTestOp(){
		exit("test end");
		$config = \framework\lib\Conf::get('mail','web');
		try{
		    $users = array('2135420174@qq.com');
		    $title = 'YPHP';
		    $content = '<h1>PHP是世界上最好的编程语言！</h1>';
		    $attachment = array();
		    sendMail($config,$users,$title,$content,$attachment);
		    echo 'OK';
		}catch(\Exception $e){
		    var_dump($e->getMessage());
		}
	}
}