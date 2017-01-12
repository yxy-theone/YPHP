<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="padding-top: 10%;">
    <div class="alert alert-danger" role="alert">
        <h2>出错了</h2>
        <h3><?php echo $error; ?></h3>
        <?php if($expire>0){ ?>
        <a  id="btn_back" class="btn btn-default" href="<?php echo $url; ?>">返回(<?php echo $expire; ?>)</a>
        <?php } ?>
    </div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<?php if($expire>0){ ?>
   <script type="text/javascript">
         var expire=<?php echo $expire; ?>;
         var btn=$("#btn_back");
         var interval=setInterval(function(){
             btn.text("返回("+(--expire)+")");
             if(expire==0){
                 clearInterval(interval);
                 location.href=btn.attr('href');
             }
         },1000);
   </script>
<?php } ?>
</body>
</html>