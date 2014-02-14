<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>内容管理系统</title>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public<?php echo ($style); ?>/css/common.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public<?php echo ($style); ?>/css/admin.css"/>
    </head>
    <body>

</head>
<body id="login">
    <div class="login wp">
        <div class="logintop"></div>
        <div class="loginlogo"><img src="/qiuyun/dogocms32/Public<?php echo ($style); ?>/images/logo.gif"/></div>

        <form action="<?php echo U('Admin/Passport/dologin');?>" method="post" name="login_box" id="login_box">
            <div class="loginfrom">
                <div class="loginfrom_con">
                    <div class="user">
                        <input type="text" name="user_name" class="logininput" required />
                    </div>
                    <div class="pass">
                        <input type="password" name="user_password" class="logininput" required />
                    </div>
                    <div class="yazhengma">
                        <div class="yz_left">
                            <input type="text" name="vd_code" id="vd_code" class="logininput" required />
                        </div><!--yz_left-->
                        <div class="yz_right">
                            <span class="dogo-click-yzmurl" style="cursor: pointer;"><img src="<?php echo U('Passport/verify');?>" title="看不清？点击更换另一个验证码。">看不清楚？点击图片</span>
                        </div><!--yz_right-->
                        <div class="clear"></div><!--clear-->
                    </div><!--yazhengma-->
                </div><!--loginfrom_con-->
                <div class="loginad"></div><!--dloginad-->
                <div class="loginfromfoot">
                    <div class="loginfromfoot_left">
                        <h3><a href="/qiuyun/dogocms32" target="_blank">←去向站点首页</a></h3>
                    </div><!--loginfromfoot_left-->
                    <div class="loginfromfoot_right">
                        <button class="login_sub button" value="登录">登录</button>
                    </div><!--loginfromfoot_right-->
                    <div class="clear"></div>
                </div><!--loginfromfoot-->
            </div><!--loginfrom-->
        </form>


    </div>
    <script type="text/javascript" src="/qiuyun/dogocms32/Public/Common/js/jquery-2.1.0.min.js"></script>




<script>
    $(function() {
        $('.dogo-click-yzmurl').click(function() {
            var url = "<?php echo U('Passport/verify');?>?tm=" + Math.random();
            $('.dogo-click-yzmurl img').attr('src', url);
        });
    });
</script>
</body>
</html>