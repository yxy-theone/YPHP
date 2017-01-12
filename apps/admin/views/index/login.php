<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="common/font-awesome.min.css" rel="stylesheet" />
    <style type="text/css">
        body {
            background:#f3f0eb;
        }
    </style>
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="container">
        <div class="form-signin">
            <div class="form-signin-heading text-center">
                <img src="images/login-logo.png"/>
            </div>
            <div class="login-wrap">
                <div id="login-tab">
                    <span class="pwd-span on">密码登录</span>
                    <span class="code-span">验证码登录</span>
                </div>
                <?php csrf_token_input(); ?>
                <div id="login-pwd">
                    <input type="text" class="form-control" name="mobile" placeholder="手机号" autofocus>
                    <input type="password" class="form-control" name="password" placeholder="密码">
                    <span class="error-span" style="color:red;"></span>
                    <button class="btn btn-lg btn-login btn-block" type="submit" id="pwd-submit">
                        <i class="fa fa-check"></i>
                    </button>
                </div>
                <div id="login-code" style="display:none;">
                    <input type="text" class="form-control" name="mobile" placeholder="手机号" autofocus>
                    <input type="text" class="form-control" name="captcha" placeholder="验证码" style="width:45%;">
                    <button id="captcha-btn" class="btn btn-success">&nbsp;发送验证码&nbsp;</button>
                    <span class="error-span" style="color:red;"></span>
                    <button class="btn btn-lg btn-login btn-block" type="submit" id="code-submit">
                        <i class="fa fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="js/common.js"></script>
<script src="js/login.js"></script>
</body>
</html>