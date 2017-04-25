<?php 
use framework\lib\View;
use framework\lib\Route;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta charset="UTF-8">
	<title><?php echo View::$title; ?></title>
	<?php View::getMetaTag() ?>
	<link rel="stylesheet" type="text/css" media="all" href="resources/common/reset.css" />
	<link rel="stylesheet" href="resources/common/font-awesome.min.css">
	<link rel="stylesheet" href="resources/css/bootstrap.min.css">
	<link rel="stylesheet" href="resources/css/comm.css">
	<link rel="stylesheet" href="resources/css/toolbar.css">
	<?php View::getCssFile() ?>
</head>
<body>
<?php $act=$this->act;$op=$this->op;?>
	<div class="tm-blog-img-container"></div>

	<header>
        <div class="container-fluid">
            <div class="tm-header-inner">
                <a href="<?php echo Route::urlManager('index/index')?>" class="navbar-brand tm-site-name">YPHP</a>
                <nav class="navbar tm-main-nav">
                    <button class="navbar-toggler hidden-md-up" type="button" data-toggle="collapse" data-target="#tmNavbar">
                        &#9776;
                    </button>
                    <div class="collapse navbar-toggleable-sm" id="tmNavbar">
                        <ul class="nav navbar-nav">
                        	<li class="nav-item <?php echo empty($_GET['category'])?'active':''; ?>">
                        	    <a href="<?php echo Route::urlManager('index/index')?>" title="全部文章" class="nav-link">All</a>
                        	</li>
                        	<?php foreach ($categorys as $k => $name) { ?>
                            	<li class="nav-item <?php echo ($_GET['category']==$k)?'active':''; ?>">
                            	    <a href="<?php echo Route::urlManager('index/index',['category'=>$k])?>" class="nav-link"><?php echo $name; ?></a>
                            	</li>
                            <?php } ?>
                        </ul>
                    </div> 
                </nav>
            </div>                                  
        </div>
    </header>
	
	<section class="tm-section">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
	                <?php echo $content ?>
	            </div>

	            <aside class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 tm-aside-r">
	                <div class="tm-aside-container">
	                    <h3 class="tm-gold-text tm-title">
	                        写在前面
	                    </h3>
	                    <p>个人框架,个人项目,可以尝试入侵,但是希望能把手段和结果反馈给我,QQ:2135420174,我向你学习。准备整个项目安全贡献榜,哈哈。</p>
	                    <hr class="tm-margin-t-small">
	                    <h3 class="tm-gold-text tm-title">
	                        所有标签
	                    </h3>
	                    <nav id="tag-nav">
	                        <ul class="nav">
	                        	<?php foreach ($tags as $k => $tag) { ?>
	                            	<li style="background:<?php echo $tag['color'] ?>;"><a href="#<?php echo $k ?>"><?php echo $tag['name'] ?></a></li>
	                            <?php } ?>
	                        </ul>
	                    </nav>
	                	<!--<hr class="tm-margin-t-small">
						<h5 class="tm-gold-text tm-title">帮朋友推广，报来源优惠50元</h5>
	                	<div class="ad">
	                		<a href="" target="_blank">
	                			<img src="" width="100%">
	                		</a>
	                	</div>-->
	                	<hr class="tm-margin-t-small">
	                    <h3 class="tm-gold-text tm-title">
	                        友情链接
	                    </h3>
	                    <nav>
	                        <ul class="nav">
	                            <li><a href="javascript:;" class="tm-footer-link">聘游旅行TripOn</a></li>
	                        </ul>
	                    </nav>
	                    <hr class="tm-margin-t-small">
	                    <h3 class="tm-gold-text tm-title">
	                        关于
	                    </h3>
	                    <nav>
	                        <ul class="nav">
	                            <li><a href="http://www.yjxxkj.cn" class="tm-footer-link">Mr.余</a></li>
	                            <li><a href="https://github.com/yxy-theone/YPHP" target="_blank" class="tm-footer-link">YPHP-Github</a></li>
	                        </ul>
	                    </nav>
	                </div> 
	            </aside>
	        </div>
	    </div>
	</section>
    
    <footer class="tm-footer">
        <div class="row">
            <div class="col-xs-12 tm-copyright-col">
                <p class="tm-copyright-text">
                    <span>Copyright &copy; 2016.Mr.余 All rights reserved.</span>
                    <span id="power-span">Power By <a target="_blank" href="index.html">YPHP</a></span>
                </p>
            </div>
        </div>
    </footer>

    <div class="toolbar">
       <a href="###" class="toolbar-item toolbar-item-weixin">
       <span class="toolbar-layer"></span>
       </a>
       <a href="javascript:;" onclick="javascript:alert('暂未开放,可直接联系QQ');" class="toolbar-item toolbar-item-feedback"></a>
       <a href="javascript:;" id="back-to-top" class="toolbar-item toolbar-item-top" style="display:none"></a>
    </div>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="resources/js/tether.min.js"></script>
<script src="resources/js/bootstrap.min.js"></script>
<?php View::getJsFile();?>
<?php View::getJs();?>
<script>  
    //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失  
    $(function () {  
        $(window).scroll(function(){  
            if ($(window).scrollTop()>200){  
                $("#back-to-top").fadeIn(1500);  
            }else{  
                $("#back-to-top").fadeOut(1500);  
            }  
        });  

        //当点击跳转链接后，回到页面顶部位置  

        $("#back-to-top").click(function(){  
            $('body,html').animate({scrollTop:0},800);  
            return false;  
        });  
    }); 
</script>
</body>
</html>