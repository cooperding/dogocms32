<?php

/**
 * BasehomeAction.class.php
 * 前台页面公共方法
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Shop\Action;
use Think\Action;
class BaseAction extends Action {

    //初始化
    function _initialize()
    {
        $skin = R('Common/System/getCfg', array('cfg_skin_shop'));//获取前台主题皮肤名称
        if (!$skin) {
            $skin = C('DEFAULT_THEME');
        }
        $navhead = R('Common/System/getNav', array('header')); //导航菜单
        $this->assign('navhead', $navhead);
        
        $this->assign('style_common', '/Common');
        $this->assign('style', '/Skin/Shop/' . $skin);
        $this->assign('tpl_header', './Theme/Shop/' . $skin . '/tpl_header.html');
        $this->assign('tpl_footer', './Theme/Shop/' . $skin . '/tpl_footer.html');
    }

}

?>
