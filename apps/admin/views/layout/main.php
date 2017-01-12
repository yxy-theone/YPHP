<?php 
use framework\lib\View;
use framework\lib\Route;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo View::$title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php View::getMetaTag() ?>
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="common/font-awesome.min.css" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">
    <?php View::getCssFile() ?>
</head>
<body class="sticky-header">
<?php $act=$this->act;$op=$this->op;?>
<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">
        <div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
        <div class="logo-icon text-center"><a href="index.html"><img src="images/logo_icon.png"></a></div>

        <div class="left-side-inner">
            <!-- visible to small devices only -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media logged-user">
                    <img src="images/photos/user-avatar.png" class="media-object">
                    <div class="media-body">
                        <h4><a href="<?php echo Route::urlManager('user/me')?>"><?php echo $_SESSION['adminname'] ?></a></h4>
                        <?php  ?>
                        <span><?php echo $_SESSION['mobile'] ?></span>
                    </div>
                </div>
                <h5 class="left-nav-title">用户信息</h5>
                <ul class="nav nav-pills nav-stacked custom-nav">
                  <li><a href="<?php echo Route::urlManager('user/me')?>"><i class="fa fa-user"></i> <span>个人中心</span></a></li>
                  <li><a href="<?php echo Route::urlManager('index/logout')?>"><i class="fa fa-sign-out"></i> <span>退出</span></a></li>
                </ul>
            </div>
            <!-- end -->

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li class="<?php echo ($op == 'index')?"active":""; ?>"><a href="<?php echo Route::urlManager('index/index')?>"><i class="fa fa-home"></i> <span>首页</span></a></li>
                <li class="menu-list <?php echo (in_array($op, ['nav','role','user']))?"nav-active":""; ?>"><a href="javascript:;"><i class="fa fa-cogs"></i> <span>系统设置</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="javscript:;">导航管理</a></li>
                        <li><a href="javscript:;">角色管理</a></li>
                        <li><a href="javscript:;">用户管理</a></li>
                    </ul>
                </li>
                <li class="menu-list <?php echo ($act == 'api')?"nav-active":""; ?>"><a href="javascript:;"><i class="fa fa-map-signs"></i> <span>接口中心</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="<?php echo Route::urlManager('api/api')?>">接口管理</a></li>
                        <li><a href="<?php echo Route::urlManager('api/code')?>">项目管理</a></li>
                        <li><a href="<?php echo Route::urlManager('api/code')?>">接口状态码</a></li>
                    </ul>
                </li>
                <li class="menu-list <?php echo ($act == 'blog')?"nav-active":""; ?>"><a href="javascript:;"><i class="fa fa-tags"></i> <span>我的博客</span></a>
                    <ul class="sub-menu-list">
                        <li class="<?php echo (in_array($op, ['article','add_article','edit_article']))?"active":""; ?>"><a href="<?php echo Route::urlManager('blog/article')?>">文章管理</a></li>
                        <li class="<?php echo (in_array($op, ['category','add_category','edit_category']))?"active":""; ?>"><a href="<?php echo Route::urlManager('blog/category')?>">栏目管理</a></li>
                        <li class="<?php echo (in_array($op, ['tag','add_tag','edit_tag']))?"active":""; ?>"><a href="<?php echo Route::urlManager('blog/tag')?>">标签管理</a></li>
                    </ul>
                </li>
                <li><a href="index.php"><i class="fa fa-image"></i> <span>图片管理</span></a></li>
            </ul>
            <!--sidebar nav end-->

        </div>
    </div>
    <!-- left side end-->

    <!-- main content start-->
    <div class="main-content" >
        <!-- header section start-->
        <div class="header-section">
            <a class="toggle-btn"><i class="fa fa-bars"></i></a>

            <form class="searchform" action="<?php echo Route::urlManager('index/search')?>" method="post">
                <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
            </form>

            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">You have 5 Mails </h5>
                            <ul class="dropdown-list normal-list">
                                <li class="new">
                                    <a href="">
                                        <span class="thumb"><img src="images/photos/user1.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">John Doe <span class="badge badge-success">new</span></span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="new"><a href="">Read All Mails</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">Notifications</h5>
                            <ul class="dropdown-list normal-list">
                                <li class="new">
                                    <a href="">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        <span class="name">Server #1 overloaded.  </span>
                                        <em class="small">34 mins</em>
                                    </a>
                                </li>
                                <li class="new"><a href="">See All Notifications</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <img src="images/photos/user-avatar.png"/>
                            <?php echo $_SESSION['adminname'] ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                            <li><a href="<?php echo Route::urlManager('index/chan_pass')?>"><i class="fa fa-edit"></i> <span>修改密码</span></a></li>
                            <li><a href="<?php echo Route::urlManager('index/logout')?>"><i class="fa fa-sign-out"></i> <span>退出</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- header section end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <?php echo $content ?>
        </div>
        <!--body wrapper end-->

        <footer class="sticky-footer">
            2016 &copy; Mr.余
        </footer>
    </div>
    <!-- main content end-->
</section>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/common.js"></script>
<script src="js/scripts.js"></script>
<?php View::getJsFile();?>
<?php View::getJs();?>
</body>
</html>