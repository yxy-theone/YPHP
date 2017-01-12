<?php
namespace apps\admin\controller;

use framework\lib\Route;
use framework\lib\Validate;
use framework\lib\Cache;
use framework\lib\Log;

class BlogController extends \framework\Controller
{
	public $login_sign = 'adminname';//指定登录标识 默认username

	public function behavior()
	{
		return ['ALL_OPTIONS'];//ALL_OPTIONS 表示全部方法都需要登录后才能访问
	}

	/**
	 * 文章管理
	 */
	public function articleOp()
	{
		$condition = array();
		$title = $_GET['title'];
        if(!empty($title)&&Validate::isSafeChar($title)){
            $condition["title"]=array("like",'%'.$title.'%');
        }
        $category = intval($_GET['category']);
        if($category > 0){
            $condition["category"]=$category;
        }
        if(in_array($_GET["recommend"],array("0","1"))){//0默认 1推荐
            $condition["recommend"]=$_GET["recommend"];
        }
        if(in_array($_GET["state"],array("0","1"))){//0正常 1下线
            $condition["state"]=$_GET["state"];
        }
		$articles = M('me/article')->getArticleList($condition,"*",10,"sort DESC,id DESC");
		$categorys = M('me/article_category')->getCategory();
		$this->display('article',[
			'articles'=>$articles,
			'categorys'=>$categorys
		]);
	}

	/**
	 * 添加博客文章
	 */
	public function add_articleOp()
	{
		if(chksubmit()){
			$title = _htmlspecialchars($_POST['title']);
			$icon = $_POST['icon'];
			if (!empty($icon)) {
				if (!Validate::isFileName($icon)) throw new \Exception("错误请求");
			}
			$category = intval($_POST['category']);
			$sort = intval($_POST['sort']);
			$tags = '';
			if (!empty($_POST['tags'])&&is_array($_POST['tags'])) {
				$tags = implode(',',$_POST['tags']);
				if (!preg_match('/^[0-9,]+$/', $tags)) throw new \Exception("标签添加错误");
			}
			if (empty($title)||!Validate::isSafeChar($title)||$category<=0||$sort<0) {
				throw new \Exception("错误请求");
			}
			$data = [];
			$data['title'] = $title;
			$data['icon'] = $icon;
			$data['category'] = $category;
			$data['sort'] = $sort;
			$data['tags'] = $tags;
			$data['article_content'] = _htmlspecialchars($_POST['article_content']);

			//加上作者信息
			$data['author_id'] = $_SESSION['id'];
			$data['author_name'] = $_SESSION['adminname'];
			$data['createtime'] = time();
			if(M('me/article')->addArticle($data)){//添加文章成功
				redirect(Route::urlManager('blog/article'));
			}else{
				Log::record("添加文章失败".json_encode($data));
				throw new \Exception("添加文章失败");
			}
		}
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$this->display('article_form',[
			'categorys'=>$categorys,
			'tags'=>$tags
		]);
	}

	/*
     * 修改限制
     */
    public function edit_astrictOp(){
        $id = intval($_GET['id']);
        $astrict=$_GET["astrict"];
        if($astrict!=0){
            $astrict=1;
        }
        if(M("me/article")->editArticle(["id"=>$id],["astrict"=>$astrict])){
            redirect(Route::urlManager('blog/article'));
        }
        Log::record("修改限制失败");
        throw new \Exception("修改限制失败");
    }

	/*
     * 修改推荐
     */
    public function edit_recommendOp(){
        $id = intval($_GET['id']);
        $recommend=$_GET["recommend"];
        if($recommend!=0){
            $recommend=1;
        }
        if(M("me/article")->editArticle(["id"=>$id],["recommend"=>$recommend])){
            redirect(Route::urlManager('blog/article'));
        }
        Log::record("修改推荐失败");
        throw new \Exception("修改推荐失败");
    }

    /*
     * 修改状态
     */
    public function edit_stateOp(){
        $id = intval($_GET['id']);
        $state=$_GET["state"];
        if($state!=0){
            $state=1;
        }
        if(M("me/article")->editArticle(["id"=>$id],["state"=>$state])){
            redirect(Route::urlManager('blog/article'));
        }
        Log::record("修改状态失败");
        throw new \Exception("修改状态失败");
    }

	/**
	 * 上传文章封面图片
	 */
	public function uploadOp(){
		if(!isset($_FILES['article_image'])) json_output(6,false,'错误请求');
		$fileInfo = $_FILES['article_image'];
		$oss = \framework\lib\conf::get('params','web')['oss-blog'];
		$accessKeyId = $oss['access_key_id'];
		$accessKeySecret = $oss['access_key_secret'];
		$endpoint = $oss['end_point'];
		$bucket = $oss['hotel_bucket'];
		try {
		    $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
		} catch (\OSS\Core\OssException $e) {
		    Log::record($e->getMessage());//记录日志 oss实例化失败
		    json_output(1,false,'oss实例化失败');
		}
		try {
            $object = md5(uniqid(microtime(true),true)).'.'.strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION));
            $filePath = $fileInfo['tmp_name'];
            $ossClient->uploadFile($bucket, $object, $filePath);
            $fileinfo = [];
            $fileinfo['name'] = $object;
            $fileinfo['url'] = 'http://'.$bucket.'.'.$endpoint.'/'.$object;
            json_output(0,true,$fileinfo);//上传成功
        } catch (\OSS\Core\OssException $e) {
            Log::record($e->getMessage());//记录日志 上传到oss失败
            json_output(6,false,'图片上传失败');
        }
	}

	/**
	 * 上传文章内容图片
	 */
	public function upload_imgOp(){
		if(!isset($_FILES['wangEditorH5File'])) exit("error|服务器端错误");
		$fileInfo = $_FILES['wangEditorH5File'];
		$oss = \framework\lib\conf::get('params','web')['oss-blog'];
		$accessKeyId = $oss['access_key_id'];
		$accessKeySecret = $oss['access_key_secret'];
		$endpoint = $oss['end_point'];
		$bucket = $oss['hotel_bucket'];
		try {
		    $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
		} catch (\OSS\Core\OssException $e) {
		    Log::record($e->getMessage());//记录日志 oss实例化失败
		    json_output(1,false,'oss实例化失败');
		}
		try {
            $object = md5(uniqid(microtime(true),true)).'.'.strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION));
            $filePath = $fileInfo['tmp_name'];
            $ossClient->uploadFile($bucket, $object, $filePath);
            $url = 'http://'.$bucket.'.'.$endpoint.'/'.$object;
            exit($url);
        } catch (\OSS\Core\OssException $e) {
            Log::record($e->getMessage());//记录日志 上传到oss失败
            exit("error|图片上传失败");
        }
	}

	/**
	 * 修改博客文章
	 */
	public function edit_articleOp()
	{
		$id = intval($_GET['id']);
		if ($id < 0) throw new \Exception("非法请求");
		$m_article = M('me/article');
		if(chksubmit()){
			$title = _htmlspecialchars($_POST['title']);
			$icon = $_POST['icon'];
			if (!empty($icon)) {
				if (!Validate::isFileName($icon)) throw new \Exception("错误请求");
			}
			$category = intval($_POST['category']);
			$sort = intval($_POST['sort']);
			$tags = '';
			if (!empty($_POST['tags'])&&is_array($_POST['tags'])) {
				$tags = implode(',',$_POST['tags']);
				if (!preg_match('/^[0-9,]+$/', $tags)) throw new \Exception("标签添加错误");
			}
			if (empty($title)||!Validate::isSafeChar($title)||$category<=0||$sort<0) {
				throw new \Exception("错误请求");
			}
			$data = [];
			$data['title'] = $title;
			$data['icon'] = $icon;
			$data['category'] = $category;
			$data['sort'] = $sort;
			$data['tags'] = $tags;
			$data['article_content'] = _htmlspecialchars($_POST['article_content']);

			$data['updatetime'] = time();
			if(!$m_article->editArticle(['id'=>$id],$data)){//修改文章失败
				Log::record("修改文章失败".json_encode($data));
				throw new \Exception("修改文章失败");
			}
		}
		$article = $m_article->getArticle(['id'=>$id]);
		if (empty($article)) throw new \Exception("非法请求");
		if (!empty($article["icon"])) {
			$oss = \framework\lib\conf::get('params','web')['oss-blog'];
			$endpoint = $oss['end_point'];
			$bucket = $oss['hotel_bucket'];
			$article["icon_url"] = 'http://'.$bucket.'.'.$endpoint.'/'.$article["icon"];
		}
		$categorys = M('me/article_category')->getCategory();
		$tags = M('me/article_tag')->getTag();
		$this->display('article_form',[
			'article'=>$article,
			'categorys'=>$categorys,
			'tags'=>$tags
		]);
	}

	/**
	 * 删除博客文章
	 */
	public function del_articleOp(){
		$id = intval($_GET['id']);
		if(M("me/article")->delArticle(["id"=>$id])){
		    redirect(Route::urlManager('blog/article'));
		}else{
		    Log::record("删除文章失败");
			throw new \Exception("删除文章失败");
		}
	}

	/**
	 * 博客栏目管理
	 */
	public function categoryOp()
	{
		$m_category = M('me/article_category');
		$category_arr = $m_category->getCategory();
		$this->display('category',[
			'category_arr'=>$category_arr
		]);
	}

	/**
	 * 添加博客栏目
	 */
	public function add_categoryOp()
	{
		$m_category = M('me/article_category');
		if(chksubmit()){
			$name = str_replace(' ','&nbsp;',$_POST['name']);
			if(!Validate::isFilterPostData($name)){
				throw new \Exception("非法请求");
			}
			$data = [];
			$data['name'] = $name;
			if($m_category->addCategory($data)){
				Cache::del('category_arr');//如果修改成功 更新缓存
				redirect(Route::urlManager('blog/category'));
			}
			throw new \Exception("添加失败");
		}
		$this->display('category_form');
	}

	/**
	 * 修改博客栏目
	 */
	public function edit_categoryOp()
	{
		$id = intval($_GET['id']);
		if ($id < 0) throw new \Exception("非法请求");
		$m_category = M('me/article_category');
		if(chksubmit()){
			$name = str_replace(' ','&nbsp;',$_POST['name']);
			if(!Validate::isFilterPostData($name)){
				throw new \Exception("非法请求");
			}
			$data = [];
			$data['name'] = $name;
			if($m_category->editCategory(['id'=>$id],$data)){
				Cache::del('category_arr');//如果修改成功 更新缓存
			}
		}
		$name = $m_category->getCategory($id);
		$this->display('category_form',[
			'name'=>$name
		]);
	}

	/**
	 * 删除博客栏目
	 */
	public function del_categoryOp()
	{
		$id = intval($_GET['id']);
		if ($id < 0) throw new \Exception("非法请求");
		$m_category = M('me/article_category');
		if($m_category->delCategory(['id'=>$id])){
			Cache::del('category_arr');//如果修改成功 更新缓存
		}
		redirect(Route::urlManager('blog/category'));
	}

	/**
	 * 博客标签管理
	 */
	public function tagOp()
	{
		$m_tag = M('me/article_tag');
		$tag_arr = $m_tag->getTag();
		$this->display('tag',[
			'tag_arr'=>$tag_arr
		]);
	}

	/**
	 * 添加博客标签
	 */
	public function add_tagOp()
	{
		if(chksubmit()){
			$name = str_replace(' ','&nbsp;',$_POST['name']);
			$color = $_POST['color'];
			if(empty($name)||empty($color)||!Validate::isFilterPostData($name)||!Validate::isColor($color)){
				throw new \Exception("非法请求");
			}
			$data = [];
			$data['name'] = $name;
			$data['color'] = $color;
			if(M('me/article_tag')->addTag($data)){
				Cache::del('tag_arr');//如果添加成功 更新缓存
				redirect(Route::urlManager('blog/tag'));
			}
			throw new \Exception("添加失败");
		}
		$this->display('tag_form');
	}

	/**
	 * 修改博客标签
	 */
	public function edit_tagOp()
	{
		$id = intval($_GET['id']);
		if ($id < 0) throw new \Exception("非法请求");
		$m_tag = M('me/article_tag');
		if(chksubmit()){
			$name = str_replace(' ','&nbsp;',$_POST['name']);
			$color = $_POST['color'];
			if(empty($name)||empty($color)||!Validate::isFilterPostData($name)||!Validate::isColor($color)){
				throw new \Exception("非法请求");
			}
			$data = [];
			$data['name'] = $name;
			$data['color'] = $color;
			if(M('me/article_tag')->editTag(['id'=>$id],$data)){
				Cache::del('tag_arr');//如果添加成功 更新缓存
			}
		}
		$tag = $m_tag->getTag($id);
		if (empty($tag)) throw new \Exception("获取文章标签失败");
		$this->display('tag_form',[
			'tag'=>$tag
		]);
	}

	/**
	 * 删除博客标签
	 */
	public function del_tagOp()
	{
		$id = intval($_GET['id']);
		if ($id < 0) throw new \Exception("非法请求");
		if(M('me/article_tag')->delTag(['id'=>$id])){
			Cache::del('tag_arr');//如果修改成功 更新缓存
		}
		redirect(Route::urlManager('blog/tag'));
	}
}