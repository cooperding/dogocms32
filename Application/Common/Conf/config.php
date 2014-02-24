<?php

return array(
    'DEFAULT_C_LAYER' => 'Action',
    'SESSION_AUTO_START' => true, //是否开启session
    'LOAD_EXT_CONFIG' => 'db', // 加载扩展配置文件
    'MODULE_DENY_LIST' => array('Common', 'Runtime'),
    'MODULE_ALLOW_LIST' => array('Home', 'Admin', 'User', 'B2c', 'Food', 'Api'), // 允许访问的模块列表
    'DEFAULT_MODULE' => 'Home', //默认模块
    'URL_CASE_INSENSITIVE' => false, // 默认false 表示URL区分大小写 true则表示不区分大小写
    'MULTI_MODULE' => true, // true开启多模块访问，false关闭多模块访问
    'URL_MODEL' => '2', //URL模式
    //完整域名部署
//    'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
//    'APP_SUB_DOMAIN_RULES' => array(
//        'admin.domain1.com' => 'Admin', // admin.domain1.com域名指向Admin模块
//        'test.domain2.com' => 'Test', // test.domain2.com域名指向Test模块
//    ),
//    
    //'URL_404_REDIRECT' => 'hhhhhhh', // 404 跳转页面 部署模式有效
    'URL_HTML_SUFFIX' => 'html', // URL伪静态后缀设置
    'URL_DENY_SUFFIX' => 'ico|png|gif|jpg', // URL禁止访问的后缀设置
    'READ_DATA_MAP' => true, //字段映射,在数据获取的时候自动处理的话
    'LANG_SWITCH_ON' => true, // 开启语言包功能
    'LANG_AUTO_DETECT' => false, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'en-us,zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => 'l', // 默认语言切换变量
    'TAGLIB_BUILD_IN' => 'cx,html',
    'TAGLIB_PRE_LOAD' => 'cx,html,dogocms', //扩展标签dogocms
    'DEFAULT_TIMEZONE' => 'PRC', //默认时区
    'VIEW_PATH' => './Theme/',
    'HTML_CACHE_ON'     =>    true, // 开启静态缓存
    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
);
