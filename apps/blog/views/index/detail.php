<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客文章—'.$article['title'];
View::registerMetaTag(['name' => 'description', 'content' => 'YPHP']);
View::registerCssFile("http://cdn.bootcss.com/highlight.js/8.0/styles/monokai_sublime.min.css");
View::registerCssFile("resources/css/article.css");
// View::registerCssFile("http://cdn.mengzhidu.com/file/highlight/solarized-light.css");
View::registerJsFile("http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js");
?>
<div id="article-info">
    <h1 id="article-title"><?php echo $article['title'] ?></h1>
    <div class="article-author">
    	<a href="javascript:;" target="_blank">
    		<img class="Avatar article-authorAvatar" src="http://blog.yjxxkj.cn/resources/img/avatar.jpg">
    	</a>
    	<a href="javascript:;" target="_blank" class="article-authorName"><?php echo $article['author_name'] ?></a>
        <span class="article-createtime"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s',$article['createtime']) ?></span>
    </div>
    <hr>
    <div id="article-content">
        <?php echo htmlspecialchars_decode($article['article_content']) ?>
    </div>
</div>
<p>
<?php $article_tag = explode(',', $article['tags']); ?>
<?php foreach ($article_tag as $k) { ?>
    <span class="tag-span" style="color:#FFF;margin-left:10px;background: <?php echo $tags[$k]['color']; ?>"><?php echo $tags[$k]['name']; ?></span>
<?php } ?>
</p>
<hr>
<div>
	<?php if (!empty($prev_article)): ?>
		<p class="pull-left" id="prev-article"><a href="<?php echo Route::urlManager('index/detail',['id'=>$prev_article['id']])?>"><span style="color:#000;">上一篇:</span><?php echo $prev_article['title'] ?></a></p>
	<?php endif ?>

	<?php if (!empty($next_article)): ?>
		<p class="pull-right" id="next-article"><a href="<?php echo Route::urlManager('index/detail',['id'=>$next_article['id']])?>"><?php echo $next_article['title'] ?><span style="color:#000;">:下一篇</span></a></p>
	<?php endif ?>
</div>
<hr style="clear: both;">
<?php
$js = <<<JSBLOCK
    hljs.initHighlightingOnLoad();
JSBLOCK;
View::registerJs($js);
?>