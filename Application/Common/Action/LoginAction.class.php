<?php

/**
 * LoginAction.class.php
 * 第三方接口文件--用于第三方登录
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:20
 * @package  Controller
 */
namespace Common\Action;
use Think\Action;
class LoginAction extends BaseApiAction {

    /**
     * saewb 
     * 信息记录表
     * R('Api/News/newsLog', array($array));//写入信息记录
     * @param
     * @return
     * @todo 
     */
    public function saewb($array)
    {
        //新浪微博登录链接地址接口
        $data = $this->authConfig('sae');
        $wb_akey = $data['wb_akey'];
        $wb_skey = $data['wb_skey'];
        $wb_callback_url = $data['wb_callback_url'];
        import('ORG.Util.SaeTOAuthV2'); // 导入新浪微博登录
        $sae_wb = new SaeTOAuthV2($wb_akey, $wb_skey);
        $code_url = $sae_wb->getAuthorizeURL($wb_callback_url);
        return $code_url;
    }

    /*
     * saeCallBack
     * 登录成功后回调函数
     * @param $type 来源类型 10默认官网，11新浪微博，12QQ登录，13人人登录
     * @param $hour (int) 时间范围以小时为单位，
     */

    public function saeCallBack($code)
    {
        //新浪微博登录链接地址接口
        $type = 11;
        $data = $this->authConfig('sae');
        $wb_akey = $data['wb_akey'];
        $wb_skey = $data['wb_skey'];
        $wb_callback_url = $data['wb_callback_url'];
        import('ORG.Util.SaeTOAuthV2'); // 导入新浪微博登录
        $sae_wb = new SaeTOAuthV2($wb_akey, $wb_skey);

        $keys = array();
        $keys['code'] = $code;
        $keys['redirect_uri'] = $wb_callback_url;
        $token = $sae_wb->getAccessToken('code', $keys);
        if ($token) {
            $uid = $token['uid'];
            $sae_c = new SaeTClientV2($wb_akey, $wb_skey, $token['access_token']);
            $user_message = $sae_c->show_user_by_id($uid); //根据ID获取用户等基本信息

            $m = M('Members');
            $condition['uid'] = array('eq', $token['uid']);
            $condition['type'] = array('eq', $type);
            $rs = $m->where($condition)->find();
            if ($rs) {//如果存在执行登录操作
                $rs_find = $m->where($condition)->find();
                $ip = get_client_ip();
                $data['ip'] = $ip;
                $data['logintime'] = time();
                $m->where($condition)->save($data);
                session('LOGIN_MEMBERS_STATUS', 'TRUE');
                session('LOGIN_MEMBERS_NAME', $rs_find['user_name']);
                session('LOGIN_MEMBERS_UID', $rs_find['id']);
                session('LOGIN_MEMBERS_IP', $ip);
                session('LOGIN_MEMBERS_LOGINTIME', $data['logintime']);
                session('LOGIN_MEMBERS_TELPHONE', $rs_find['telphone']);
                $array = array('status' => 0);
            } else {//如果不存在执行写入并登录
                $ip = get_client_ip();
                $data['uid'] = $token['uid'];
                $data['type'] = $type;
                $data['id'] = $this->guid();
                $data['image'] = $user_message['avatar_hd'];
                $data['signature'] = $user_message['description'];
                $data['email'] = $email;
                $data['user_name'] = $user_message['screen_name'] ? $user_message['screen_name'] : $uid;
                $data['addtime'] = time();
                $data['ip'] = $ip;
                $data['logintime'] = time();
                $rs_add = $m->add($data);
                if ($rs_add) {
                    $rs_find = $m->where($condition)->find();
                    session('LOGIN_MEMBERS_STATUS', 'TRUE');
                    session('LOGIN_MEMBERS_NAME', $rs_find['user_name']);
                    session('LOGIN_MEMBERS_UID', $rs_find['id']);
                    session('LOGIN_MEMBERS_IP', $ip);
                    session('LOGIN_MEMBERS_LOGINTIME', $data['logintime']);
                    session('LOGIN_MEMBERS_TELPHONE', $rs_find['telphone']);
                    $array = array('status' => 0);
                } else {
                    $array = array('status' => 1, 'msg' => '登录失败');
                }
            }
        } else {
            $array = array('status' => 1);
        }
        return $array;
    }

    /*
     * authConfig
     * 第三方登录API信息配置参数
     */

    public function authConfig($type)
    {
        if ($type == 'sae') {//新浪微博接口信息
            $data['wb_akey'] = $this->getCfg('cfg_weibo_akey'); //App Key
            $data['wb_skey'] = $this->getCfg('cfg_weibo_skey'); //App Secret
            $data['wb_callback_url'] = $this->getCfg('cfg_weibo_callbackurl'); //回调方法地址
        }
        return $data;
    }

    //生成uuid
    function guid()
    {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
        return $uuid;
    }

    /*
     * getCfg
     * 获取站点配置
     * @todo
     */

    public function getCfg($name)
    {
        $m = M('SiteInfo');
        $condition['sys_name'] = array('eq', $name);
        $rs = $m->where($condition)->find();
        return $rs['sys_value'];
    }

}
