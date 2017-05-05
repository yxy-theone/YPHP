<?php
use framework\lib\View;
use framework\lib\Route;

View::$title = '博客管理';
View::registerMetaTag(['name' => 'description', 'content' => '添加文章']);
View::registerCssFile("common/jquery_fileupload/css/jquery.fileupload.css");
View::registerJsFile("common/jquery_fileupload/js/vendor/jquery.ui.widget.js");
View::registerJsFile("common/jquery_fileupload/js/jquery.iframe-transport.js");
View::registerJsFile("common/jquery_fileupload/js/jquery.fileupload.js");
View::registerJsFile("js/bootstrapValidator.min.js");

View::registerCssFile("common/wangEditor/css/wangEditor.min.css");
View::registerJsFile("common/wangEditor/js/wangEditor.min.js");
?>
<?php $IS_UPDATE_FORM=!empty($_GET["id"]); ?>
<ul class="breadcrumb panel">
    <li><a href="javascript:;"><i class="fa fa-tags"></i> 我的博客</a></li>
    <li><a href="<?php echo Route::urlManager('blog/article')?>"><i class="fa fa-tags"></i>文章管理</a></li>
    <?php if($IS_UPDATE_FORM){ ?>
        <li class="active">修改文章</li>
    <?php }else{ ?>
        <li class="active">添加文章</li>
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
                <label  class="col-sm-2 control-label">文章标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="title"  value="<?php echo $article['title'] ?>"  placeholder="请输入文章标题">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章封面</label>
                <div class="col-sm-4">
                    <input type="hidden" id="icon" value="<?php echo $article["icon"] ?>" name="icon">
                    <span class="btn btn-success fileinput-button">
                        <i class="fa fa-upload"></i>
                        <span>上传图片</span>
                        <input id="fileupload" type="file" name="article_image">
                    </span>
                    <div id="progress" style="height:10px;margin-top:5px;display: none; " class="progress">
                        <div class="progress-bar progress-bar-info"></div>
                    </div>
                     <?php if($IS_UPDATE_FORM && !empty($article['icon'])){ ?>
                        <div id="image_box">
                            <a href="javascript:void(0)" class="thumbnail" style=" width: 132px;margin-top: 5px;">
                                <img  src="<?php echo $article["icon_url"] ?>" data-holder-rendered="true" style="height: 120px; width:120px; display: block;">
                            </a>
                        </div>
                    <?php }else{ ?>
                        <div id="image_box" style="display:none;">
                            <a href="javascript:void(0)" class="thumbnail" style=" width: 132px;margin-top: 5px;">
                                <img  src="" data-holder-rendered="true" style="height: 120px; width:120px; display: block;">
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <label class="col-sm-6 control-label" style="color:#ccc;text-align:left;">封面为500*500像素的PNG/JPG/GIF格式图片</label>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章分类</label>
                <div class="col-sm-3">
                    <select class="form-control" name="category">
                        <option value="">请选择栏目</option>
                        <?php foreach ($categorys as $k => $name) { ?>
                        <option value="<?php echo $k ?>" <?php echo $article['category']==$k?"selected='selected'":""; ?>><?php echo $name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">文章排序</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control" name="sort"  value="<?php echo $article['sort'] ?>"  placeholder="文章排序">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章内容</label>
                <div class="col-sm-9">
                    <textarea id="article_content" name="article_content" style="width:100%;height:400px;max-height:500px;">
                        <?php echo $article["article_content"] ?>
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章标签</label>
                <div class="col-sm-10">
                <?php if($IS_UPDATE_FORM){ ?>
                    <?php $tag_arr = explode(',', $article['tags']);?>
                <?php } ?>
                <?php foreach ($tags as $tag_id => $tag) { ?>
                    <label class="checkbox-inline">
                      <?php if ($IS_UPDATE_FORM&&in_array($tag_id, $tag_arr)) { ?>
                      <input type="checkbox" name="tags[]" value="<?php echo $tag_id;?>" checked="checked" ><?php echo $tag["name"];?>
                      <?php }else{ ?>
                      <input type="checkbox" name="tags[]" value="<?php echo $tag_id;?>"><?php echo $tag["name"];?>
                      <?php } ?>
                    </label>
                <?php } ?>
                </div>
            </div>
            <fieldset>
            <div class="form-group">
                <div class="col-sm-3 col-sm-offset-2">
                    <input class="btn btn-lg btn-success" type="submit" value="发布文章"/>
                </div>
            </div>
            </fieldset>
        </fieldset>
    </form>
</div>
<?php
$uploadImgUrl = Route::urlManager("blog/upload_img");
$js = <<<JSBLOCK
    $(function(){
        var editor = new wangEditor('article_content');
        editor.config.uploadImgUrl = '{$uploadImgUrl}';
        editor.create();

        $("#fileupload").fileupload({
            url: getUrl('index.php?act=blog&op=upload'),
            dataType: 'json',
            formData: {},
            add:function(e,data){
                $('#progress').show();
                data.submit();
            },
            done: function (e, data) {
                console.log(data);
                if(data.result.success){
                    var fileinfo=data.result.data;
                    $("#icon").val(fileinfo.name);
                    $('#progress').hide();
                    $("#image_box").show();
                    $("#image_box img").attr("src",fileinfo.url);
                }else{
                    alert(data.result.data);
                }
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress1 .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        });

        $('#article_form').bootstrapValidator({
            excluded:null,
            fields:{
                title: {
                    validators: {
                        notEmpty: {
                            message: '名称不能为空'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: '请选择文章类型'
                        }
                    }
                }
            }
        });

        $(document).ready(function() {
            $("input[name='tags[]']").attr('disabled',true);
            if($("input[name='tags[]']:checked").length>=3){
                $("input[name='tags[]']:checked").attr('disabled', false);
            }else{
                $("input[name='tags[]']").attr('disabled', false);
            }
            $("input[type=checkbox]").click(function() {
                $("input[name='tags[]']").attr('disabled',true);
                if($("input[name='tags[]']:checked").length>=3){
                    $("input[name='tags[]']:checked").attr('disabled', false);
                }else{
                    $("input[name='tags[]']").attr('disabled', false);
                }
            });
        });
    });
JSBLOCK;
view::registerJs($js);
?>