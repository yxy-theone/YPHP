<?php
use framework\lib\View;
use framework\lib\Route;
use framework\lib\Paging;

View::$title = '博客管理';
View::registerMetaTag(['name' => 'description', 'content' => '文章管理']);
view::registerJsFile("js/pop_confirm.js");
?>
<ul class="breadcrumb panel">
    <li><a href="javascript:;"><i class="fa fa-tags"></i> 我的博客</a></li>
    <li class="active">文章管理</li>
</ul>
<div class="container-full">
    <div style="padding-top: 50px;">
        <form class="form-inline"  method="get">
            <input type="hidden" name="act" value="blog">
            <input type="hidden" name="op" value="article">
            <div class="form-group">
                <label>标题</label>
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($_GET["title"]); ?>">
            </div>
            <div class="form-group">
                <label>分类</label>
                <select name="category" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach ($categorys as $k => $name) { ?>
                    <option value="<?php echo $k ?>" <?php echo $_GET['category']==$k?"selected='selected'":""; ?>><?php echo $name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>推荐</label>
                <select name="recommend" class="form-control">
                    <option value="">请选择</option>
                    <option value="0" <?php echo $_GET['recommend']=="0"?"selected='selected'":""; ?>>默认</option>
                    <option value="1" <?php echo $_GET['recommend']=="1"?"selected='selected'":""; ?>>推荐</option>
                </select>
            </div>
            <div class="form-group">
                <label>状态</label>
                <select name="state" class="form-control">
                    <option value="">请选择</option>
                    <option value="0" <?php echo $_GET['state']=="0"?"selected='selected'":""; ?>>正常</option>
                    <option value="1" <?php echo $_GET['state']=="1"?"selected='selected'":""; ?>>下线</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">查询</button>
        </form>
    </div>
    <div class="row top-tool-bar">
        <div class="col-xs-offset-10 col-xs-2"><a href="<?php echo Route::urlManager('blog/add_article')?>" class="btn btn-default add">添加文章</a></div>
    </div>
    <br>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>标题</th>
            <th>作者</th>
            <th>类别</th>
            <th>排序</th>
            <th>限制</th>
            <th>推荐</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $i => $v) { ?>
            <tr>
                <th scope="row"><?php echo $i + 1; ?></th>
                <td><?php echo $v['title']; ?></td>
                <td><?php echo $v['author_name']; ?></td>
                <td><?php echo $categorys[$v['category']]; ?></td>
                
                <?php 
                    if ($v['sort'] >= 50) {
                        $sort_info = '<span class="text-danger">置顶</span>';
                    }else if($v['sort'] > 0){
                        $sort_info = '<span>'.$v['sort'].'</span>';
                    }else if($v['sort']==0){
                        $sort_info = '<span class="text-info">正常</span>';
                    }
                ?>
                <td><?php echo $sort_info; ?></td>
                
                <?php if ($v['astrict']==0) {?>
                    <td>
                        <strong>正常</strong>
                        <a class="btn btn-xs btn-danger" href="<?php echo Route::urlManager('blog/edit_astrict',['id'=>$v['id'],'astrict'=>1])?>">限制</a>
                    </td>
                <?php }else if($v['astrict']==1){?>
                    <td><strong class="text-warning">限制</strong>
                        <a class="btn btn-xs btn-info" href="<?php echo Route::urlManager('blog/edit_astrict',['id'=>$v['id'],'astrict'=>0])?>">取消</a>
                    </td>
                <?php } ?>

                <?php if ($v['recommend']==0) {?>
                    <td>
                        <strong>正常</strong>
                        <a class="btn btn-xs btn-warning" href="<?php echo Route::urlManager('blog/edit_recommend',['id'=>$v['id'],'recommend'=>1])?>">推荐</a>
                    </td>
                <?php }else if($v['recommend']==1){?>
                    <td><strong class="text-success">推荐</strong>
                        <a class="btn btn-xs btn-primary" href="<?php echo Route::urlManager('blog/edit_recommend',['id'=>$v['id'],'recommend'=>0])?>">取消</a>
                    </td>
                <?php } ?>

                <?php if ($v['state']==0) {?>
                    <td>
                        <strong>正常</strong>
                        <a class="btn btn-xs btn-danger" href="<?php echo Route::urlManager('blog/edit_state',['id'=>$v['id'],'state'=>1])?>">下线</a>
                    </td>
                <?php }else if($v['state']==1){?>
                    <td><strong class="text-info">下线</strong>
                        <a class="btn btn-xs btn-success" href="<?php echo Route::urlManager('blog/edit_state',['id'=>$v['id'],'state'=>0])?>">恢复</a>
                    </td>
                <?php } ?>

                <td>
                    <a class="btn btn-xs btn-info" href="<?php echo Route::urlManager('blog/edit_article',['id'=>$v['id']])?>">修改</a>
                    &nbsp;
                    <a class="btn btn-xs btn-danger sgBtn" onclick="del(this,<?php echo $v["id"]; ?>)" href="javascript:void(0)">删除</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<nav class="text-center">
    <?php Paging::bar(); ?>
</nav>
<?php
$js = <<<JSBLOCK
    function del(sender,id){
       $(sender).pop_confirm("确实删除该文章?",function(){
           location.href=getUrl("index.php?act=blog&op=del_article&id="+id);
       });
    }
JSBLOCK;
View::registerJs($js);
?>