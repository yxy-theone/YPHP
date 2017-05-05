<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客文章—'.$article['title'];
View::registerMetaTag(['name' => 'description', 'content' => 'YPHP']);
View::registerCssFile("http://cdn.bootcss.com/highlight.js/8.0/styles/monokai_sublime.min.css");
View::registerCssFile("resources/css/article.css?v=1");
// View::registerCssFile("http://cdn.mengzhidu.com/file/highlight/solarized-light.css");
View::registerJsFile("http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js");
?>
<div id="article-info">
    <h1 id="article-title"><?php echo $article['title'] ?></h1>
    <div class="article-author">
    	<a href="javascript:;" target="_blank">
    		<img class="Avatar article-authorAvatar" src="http://blog.yuphp.cn/resources/img/avatar.jpg">
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
<!--畅言PC和WAP自适应版-->
<div id="SOHUCS" sid="<?php echo $article['id'] ?>"></div> 
<script type="text/javascript"> 
    (function(){ 
        var appid = 'cysYPR99P'; 
        var conf = '2f14e54adfcce306bdd7c97782252f71'; 
        var width = window.innerWidth || document.documentElement.clientWidth;
        if (width < 960) { 
            window.document.write('<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" src="https://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=' + appid + '&conf=' + conf + '"><\/script>');
        } else { 
            var loadJs=function(d,a){
                var c=document.getElementsByTagName("head")[0]||document.head||document.documentElement;
                var b=document.createElement("script");
                b.setAttribute("type","text/javascript");
                b.setAttribute("charset","UTF-8");
                b.setAttribute("src",d);
                if(typeof a==="function"){
                    if(window.attachEvent){
                        b.onreadystatechange=function(){
                            var e=b.readyState;
                            if(e==="loaded"||e==="complete"){
                                b.onreadystatechange=null;a()
                            }
                        }
                    }else{
                        b.onload=a
                    }
                }
                c.appendChild(b)
            };
            loadJs("https://changyan.sohu.com/upload/changyan.js",function(){window.changyan.api.config({appid:appid,conf:conf})});
        }
    })();
</script>
<?php
$js = <<<JSBLOCK
    hljs.initHighlightingOnLoad();
JSBLOCK;
View::registerJs($js);
?>