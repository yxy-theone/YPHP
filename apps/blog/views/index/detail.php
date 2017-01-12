<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客文章——'.$article['title'];
View::registerMetaTag(['name' => 'description', 'content' => 'YPHP']);
View::registerCssFile("http://cdn.bootcss.com/highlight.js/8.0/styles/monokai_sublime.min.css");
// View::registerCssFile("http://cdn.mengzhidu.com/file/highlight/solarized-light.css");
View::registerJsFile("http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js");
?>
<div id="article-info">
    <h1 id="article-title"><?php echo $article['title'] ?></h1>
    <hr>
    <div id="article-content">
        <?php echo htmlspecialchars_decode($article['article_content']) ?>
    </div>
</div>
<?php
$js = <<<JSBLOCK
    hljs.initHighlightingOnLoad();
JSBLOCK;
View::registerJs($js);
?>