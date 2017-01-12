<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '我的博客-标签管理';
View::registerMetaTag(['name' => 'description', 'content' => '标签管理']);
view::registerJsFile("js/pop_confirm.js");
?>
<ul class="breadcrumb panel">
    <li><a href="javascript:;"><i class="fa fa-tags"></i> 我的博客</a></li>
    <li class="active">标签管理</li>
</ul>
<div class="container-full">
    <div class="row top-tool-bar">
        <div class="col-xs-offset-10 col-xs-2"><a href="<?php echo Route::urlManager('blog/add_tag')?>" class="btn btn-default add">添加标签</a></div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th style="width: 80px;">标签ID</th>
                    <th>标签名称</th>
                    <th>标签颜色</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tag_arr as $id => $v) { ?>
                    <tr>
                        <th style="padding-left: 15px;"><?php echo $id ?></th>
                        <td><?php echo $v['name'] ?></td>
                        <td><span style="background-color: <?php echo $v['color'] ?>;color: <?php echo $v['color'] ?>;">颜色</span></td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="<?php echo route::urlManager('blog/edit_tag',['id'=>$id])?>">修改</a>
                            <a class="btn btn-xs btn-danger sgBtn" onclick="del(this,<?php echo $id ?>)" href="javascript:void(0)">删除</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$js = <<<JSBLOCK
    function del(sender,id){
       $(sender).pop_confirm("确实删除该标签?",function(){
           location.href=getUrl("index.php?act=blog&op=del_tag&id="+id);
       });
    }
JSBLOCK;
View::registerJs($js);
?>