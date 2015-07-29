<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="Public/Admin/H-ui/lib/html5.js"></script>
    <script type="text/javascript" src="Public/Admin/H-ui/lib/respond.min.js"></script>
    <script type="text/javascript" src="Public/Admin/H-ui/lib/PIE_IE678.js"></script>
    <![endif]-->
    <link href="Public/Admin/H-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="Public/Admin/H-ui/css/H-ui.login.css" rel="stylesheet" type="text/css" />
    <link href="Public/Admin/H-ui/css/style.css" rel="stylesheet" type="text/css" />
    <link href="Public/Admin/H-ui/lib/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!--[if IE 7]>
    <link href="Public/Admin/H-ui/lib/font-awesome/font-awesome-ie7.min.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <link href="Public/Admin/H-ui/lib/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="Public/Admin/H-ui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>后台登录 - 后台试验中心</title>
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
        <form class="form form-horizontal" action="<?php echo U('Public/login');?>" method="post">
            <div class="row cl">
                <label class="form-label col-3"><i class="iconfont">&#xf00ec;</i></label>
                <div class="formControls col-8">
                    <input  name="username" type="text" placeholder="账户" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3"><i class="iconfont">&#xf00c9;</i></label>
                <div class="formControls col-8">
                    <input  name="passwd" type="password" placeholder="密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-8 col-offset-3">
                    <input id="codeinput" class="input-text size-L" type="text" placeholder="验证码"  style="width:150px;">
                    <img id="code" src="" style="width: 80px;height: 40px;"> <a id="kanbuq"  href="javascript:void(change_code(this));">看不清，换一张</a> </div>
            </div>
            <div class="row">
                <div class="formControls col-8 col-offset-3">
                    <label for="online">
                        <input type="checkbox" name="online" id="online" value="">
                        使我保持登录状态</label>
                </div>
            </div>
            <div class="row">
                <div class="formControls col-8 col-offset-3">
                    <input name="" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
                    <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer">Copyright 闻康集团（重庆）科技有限公司 by 技术支持中心</div>
<script type="text/javascript" src="Public/Admin/H-ui/lib/jquery.min.js"></script>
<script type="text/javascript" src="Public/Admin/H-ui/js/H-ui.js"></script>
<script>
    change_code();
    function change_code(obj) {
        document.getElementById("code").src = "<?php echo U('Public/simpleCaptcha');?>?" + Math.random();
        document.getElementById("codeinput").value = "";
        return false;
    }
</script>
</body>
</html>