<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客管理';
View::registerMetaTag(['name' => 'description', 'content' => '添加标签']);
?>
<?php $IS_UPDATE_FORM=!empty($_GET["id"]); ?>
<ul class="breadcrumb panel">
    <li><a href="javascript:;"><i class="fa fa-tags"></i> 我的博客</a></li>
    <li><a href="<?php echo Route::urlManager('blog/tag')?>"><i class="fa fa-tags"></i>标签管理</a></li>
    <?php if($IS_UPDATE_FORM){ ?>
        <li class="active">修改标签</li>
    <?php }else{ ?>
        <li class="active">添加标签</li>
    <?php } ?>
</ul>
<div class="container">
    <form id="article_form" method="post" class="form-horizontal">
        <?php csrf_token_input(); ?>
        <fieldset>
            <?php if($IS_UPDATE_FORM){ ?>
                <input type="hidden" name="id" value="<?php echo intval($_GET["id"]); ?>">
            <?php } ?>
            <div class="form-group">
                <label  class="col-sm-2 control-label">标签名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name"  value="<?php echo str_replace('&nbsp;',' ',$tag['name']); ?>"  placeholder="请输入标签名称">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">标签颜色</label>
                <div class="col-sm-8">
                    <input type="color" name="color"  value="<?php echo $tag['color']; ?>">
                </div>
            </div>
            <fieldset>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <input class="btn btn-success" type="submit" value="提交"/>
                </div>
            </div>
            </fieldset>
        </fieldset>
    </form>
</div>