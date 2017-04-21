<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <title>Error Page</title>
    <style type="text/css">
    body {
        background: #424f63;
        font-family: 'Open Sans', sans-serif;
        color: #7a7676;
        line-height: 20px;
        overflow-x: hidden;
        font-size: 14px;
        text-align: center;
    }
    .error-page {
        background: #6bc5a4;
    }
    .error-wrapper {
        margin-top: 15%;
    }
    .error-wrapper h2 {
        font-size: 40px;
        color: #fff;
        font-weight: bold;
    }
    .error-wrapper h3 {
        font-size: 32px;
        color: #474747;
        font-weight: bold;
        line-height: 30px;
        margin-top: 0;
    }
    .error-wrapper .nrml-txt {
        font-size: 18px;
        color: #474747;
        font-weight: normal;
        line-height: 30px;
    }
    .error-wrapper .nrml-txt a {
        color: #a7ffdf;
    }
    .error-wrapper .back-btn {
        color: #fff;
        border: 1px solid #fff;
        padding: 15px 30px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
        margin-top: 50px;
    }
    .error-wrapper .back-btn:hover {
        background: #fff;
        color: #6bc5a4;
        border-color: #fff;
    }
  </style>
</head>

<body class="error-page">
<section>
    <div class="container">
        <section class="error-wrapper text-center">
            <?php if ($code == 404) { ?>
                 <h1><img src="resources/img/404-error.png"></h1>
                 <h2>page not found</h2>
            <?php }else{ ?>
                <h1><img src="resources/img/500-error.png"></h1>
                <h2>bad request</h2>
            <?php } ?>
            <h3><?php echo htmlentities($message) ?></h3>
            <?php if (!empty($reffer_url)) { ?>
                <a class="back-btn" href="<?php echo $reffer_url ?>">返回上一页</a>
            <?php } ?>
            <a class="back-btn" href="index.php">返回首页</a>
        </section>
    </div>
</section>
</body>
</html>