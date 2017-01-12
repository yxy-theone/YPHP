<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客管理';
View::registerMetaTag(['name' => 'description', 'content' => '添加栏目']);
?>
<?php $IS_UPDATE_FORM=!empty($_GET["id"]); ?>
<ul class="breadcrumb panel">
    <li><a href="javascript:;"><i class="fa fa-tags"></i> 我的博客</a></li>
    <li><a href="<?php echo Route::urlManager('blog/category')?>"><i class="fa fa-tags"></i>栏目管理</a></li>
    <?php if($IS_UPDATE_FORM){ ?>
        <li class="active">修改栏目</li>
    <?php }else{ ?>
        <li class="active">添加栏目</li>
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
                <label  class="col-sm-2 control-label">栏目名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name"  value="<?php echo str_replace('&nbsp;',' ',$name); ?>"  placeholder="请输入栏目名称">
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