<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>内容管理系统</title>
        <link id="easyuiTheme" rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public/Easyui/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public/Easyui/themes/default/portal.css"/><!--此css引入要根据情况修改-->
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public/Easyui/themes/icon.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public<?php echo ($style); ?>/css/common.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public<?php echo ($style); ?>/css/admin.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public/Kindeditor/themes/default/default.css"/>
        <link rel="stylesheet" type="text/css" href="/qiuyun/dogocms32/Public/Kindeditor/themes/simple/simple.css"/>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Common/js/jquery-2.1.0.min.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Easyui/jquery.portal.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Easyui/common.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Easyui/locale/easyui-lang-zh_CN.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Kindeditor/kindeditor-min.js"></script>
        <script type="text/javascript" src="/qiuyun/dogocms32/Public/Kindeditor/lang/zh_CN.js"></script>
    </head>

    <body>

</head>
<body class="easyui-layout layout_index">
    <script type="text/javascript">
        $(function() {
            $('.sider li a').click(function() {
                var classId = 'index';
                var subtitle = $(this).text();
                var url = $(this).attr('cmshref');
                var rel = $(this).attr('rel');
                if (rel == 'dialog') {
                    var type = $(this).attr('type');
                    openDialog(type, url, subtitle);
                    return false;
                }
                if (!$('#tabs_' + classId).tabs('exists', subtitle)) {
                    $('#tabs_' + classId).tabs('add', {
                        title: subtitle,
                        content: subtitle,
                        closable: true,
                        href: url,
                        tools: [{
                                iconCls: 'icon-mini-refresh',
                                handler: function() {
                                    updateTab(classId, url, subtitle);
                                }
                            }]
                    });
                    return false;
                } else {
                    $('#tabs_' + classId).tabs('select', subtitle);
                    return false;
                }

            });
        });
    </script>

    <noscript>
    <div style=" position:absolute; z-index:100000; height:246px;top:0px;left:0px; width:100%; background:white; text-align:center;">
        <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
    </div>
    </noscript>
    <div data-options="region:'north',border:false,split:true" style="height:60px;padding:2px">
        <div class="admintop">
            <div class="adminlogo fleft">
                <img src="/qiuyun/dogocms32/Public<?php echo ($style); ?>/images/logo.gif" width="" height="55px"/>
            </div>
            <div class="admincon fleft"></div>
            <div class="adminmember fright">

                
<div style="background:#fafafa;padding:5px;margin-top:2px;width:340px;border:1px solid #ccc">
    <a href="/qiuyun/dogocms32/" target="_blank" iconCls="icon-ok">网站首页</a>
    <a href="#" class="easyui-menubutton" menu="#mm3" iconCls="icon-ok">个人资料</a>
    <a href="#" class="easyui-menubutton" menu="#skin" iconCls="icon-edit">skin</a>
    <a href="http://www.qiuyuntech.com" target="_blank"  iconCls="icon-help">站点支持</a>
</div>


<div id="mm3" style="width:100px;">
    <div iconCls="icon-edit"><a href="<?php echo U('Admin/Passport/logout');?>" class="">个人资料</a></div>
    <div iconCls="icon-edit"><a href="<?php echo U('Admin/Passport/logout');?>" class="">修改密码</a></div>
    <div iconCls="icon-undo"><a href="<?php echo U('Admin/Passport/logout');?>" class="">退出登录</a></div>
</div>
<div id="skin" style="width:100px;">
    <div onclick="changeTheme('default');">default</div>
    <div onclick="changeTheme('black');">black</div>
    <div onclick="changeTheme('bootstrap');">bootstrap</div>
    <div onclick="changeTheme('gray');">gray</div>
    <div onclick="changeTheme('metro');">metro</div>
</div>
            </div>
            <div class="clear"></div>
        </div><!--admintop-->
    </div>
    <div data-options="region:'west',split:true,title:'<?php echo L('menu_name');?>'" style="width:200px;">
        <div class="easyui-accordion sider" data-options="fit:true,border:false">
            <!--//左侧菜单导航-->
            <?php if(is_array($menu)): foreach($menu as $key=>$list): ?><div title="<?php echo ($list["label"]); ?>" data-options="iconCls:'icon-mini-add'" style="padding:10px;">
        <ul class="easyui-tree" data-options="animate:true">
            <?php if(is_array($list["items"])): foreach($list["items"] as $skey=>$slist): ?><li data-options="state:'closed'">
                    <span><?php echo ($slist["label"]); ?></span>
                    <ul>
                        <?php if(is_array($slist["items"])): foreach($slist["items"] as $sskey=>$sslist): ?><li><a href="javascript:viod(0);" cmshref="<?php echo ($sslist["link"]); ?>" type="<?php echo ($sslist["type"]); ?>" rel="<?php echo ($sslist["rel"]); ?>"><?php echo ($sslist["label"]); ?></a></li><?php endforeach; endif; ?>
                    </ul>
                </li><?php endforeach; endif; ?>
        </ul>
    </div><!--waiceng--><?php endforeach; endif; ?>
            <!--//左侧菜单导航-->
        </div><!--accordion-->

    </div><!--west-->
    <div data-options="region:'east',split:true,collapsed:true,title:'公告及官方微博'" style="width:220px;padding:10px;">
        
    </div>
    <div data-options="region:'south',split:false,border:false" style="height:50px;padding:10px; text-align:center;">dingcms.com/qiuyuntech.com版权<br/>qiuyuntech.com开发团队</div>
    <!--//主体内容部分-->
    <div data-options="region:'center'" class="indexcenter" title="内容管理系统">
        <div id="tabs_index" class="easyui-tabs"  fit="true" border="false"  >
            <div title="<?php echo L('home');?>" style="overflow:hidden; " >
                <div id="pp" style="position:relative;">
    <div style="width:50%;">
        <div title="Clock" style="height:200px;padding:5px;">

        </div>
        <div title="Tutorials" collapsible="true" closable="true" style="height:200px;padding:5px;">

        </div>

    </div>
    <div style="width:50%;">

        <div title="Tutorials" collapsible="true" closable="false" style="height:200px;padding:5px;">

        </div>
        <div title="Tutorials" collapsible="true" closable="true" style="height:200px;padding:5px;">

        </div>

    </div>

</div>
<script>
    $(function() {
        $('#pp').portal({
            border: false,
            fit: true,
        });

    });

</script>
            </div>

            <div title="<?php echo L('welcome');?>" style="padding:10px; " data-options="closable:true" >

            </div>
        </div>
    </div><!--center-->
    <!--//主体内容部分-->
    <div id="dialog_cms" data-options="iconCls:'icon-save'">
    </div>




</body>
</html>