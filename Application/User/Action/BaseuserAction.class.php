<?php

/**
 * BasememberAction.class.php
 * 前台页面公共方法
 * 前台核心文件，其他页面需要继承本类方可有效
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace User\Action;
use Think\Action;
class BaseuserAction extends Action {

    //初始化
    function _initialize()
    {
        //此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        $status = session('LOGIN_M_STATUS');
        if ($status != 'TRUE') {
            $this->redirect('..'.__MODULE__.'/Passport/login');
        }
        $this->assign('count_address', $this->getAddressCount());
        $this->assign('count_apilist', $this->getApiListCount());
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $navhead = R('Api/News/getNav', array('header')); //导航菜单
        $this->assign('navhead', $navhead);
        $this->assign('style', __PUBLIC__ . '/Skin/Member/' . $skin);
        $this->assign('style_cmomon', __PUBLIC__ . '/Common');
        $this->assign('header', './App/Tpl/Member/' . $skin . '/header.html');
        $this->assign('footer', './App/Tpl/Member/' . $skin . '/footer.html');
        $this->assign('sidebar', './App/Tpl/Member/' . $skin . '/sidebar.html');
    }

    /*
     * getSkin
     * 获取站点设置的主题名称
     * @todo 使用程序读取主题皮肤名称
     */

    public function getSkin()
    {
        $skin = R('Api/News/getCfg', array('cfg_member_skin'));
        if(!$skin){
            $skin = 'default';
        }
        return $skin;
    }
    /*
     * getAddressCount
     * 获取收货地址数量
     * 
     */

    public function getAddressCount()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $count = $m->where($condition)->count();
        return $count;
    }
    /*
     * getApiListCount
     * 获取apiList数量
     * 
     */

    public function getApiListCount()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $count = $m->where($condition)->count();
        return $count;
    }
    
}

?>
