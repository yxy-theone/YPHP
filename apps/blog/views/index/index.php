<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客首页';
View::registerMetaTag(['name' => 'description', 'content' => 'YPHP']);
View::registerJsFile("resources/js/blog.js?v=20170421");
?>
<?php if(empty($articles)){ ?>
<h1 class="text-center" style="padding-top:50px;">暂无文章,看看其他内容吧!!!</h1>
<?php }else{ ?>
<div class="row tm-margin-t-big">
	<?php foreach ($articles as $v) { ?>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="article-list-box">
            <div class="article-list-image">
                <a  class="open-single-frame" href="<?php echo Route::urlManager('index/detail',['id'=>$v['id']])?>">
                    <img width="600" height="300" src="<?php echo $oss_url.$v['icon'] ?>" class="attachment-box-image size-box-image wp-post-image" />
                </a>
                <?php if ($v['astrict'] == 1) { ?>
                    <div class="astrict">
                        <div class="text">限制</div>
                    </div>
                <?php } ?>
            </div>
            <h2><a class="open-single-frame" href="<?php echo Route::urlManager('index/detail',['id'=>$v['id']])?>"><span class="entry-title-primary"><?php echo $v['title'] ?></span></a></h2>
            <div class="info">
                <div class="article-type"><?php echo $categorys[$v['category']] ?></div>
                <div class="article-date">
                <?php echo getRelativeTime($v['createtime']);?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div id="loadmore" class="loadmore" _category="<?php echo intval($_GET['category'])?>">
    <a class="btn-loadmore" href="javascript:;" title="更多文章">更多文章</a>
</div>    
<div id="juhua-loading">
    <img alt="Loading..." src="resources/img/loading.gif"><span> </span>
</div>
<?php } ?>