<?php

/**
 * IndexAction.class.php
 * 前台首页
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
class IndexAction extends BaseuserAction {

    /**
     * index
     * 会员主页信息
     * @return boolean
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function index()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $condition['id'] = array('eq', $uid);
        $data['uname'] = session('LOGIN_M_NAME');
        $data['ip'] = get_client_ip();
        $data['logintime'] = session('LOGIN_M_LOGINTIME');
        $data['addtime'] = session('LOGIN_M_ADDTIME');
        $data_signature = $m->field('signature')->where($condition)->find();
        $data['signature'] = $data_signature['signature'];
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '会员中心');
        $this->assign('sidebar_active', 'index');
        $this->assign('data', $data);
        $this->display($skin . ':index');
    }

    /**
     * personal
     * 个人资料
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function personal()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $condition['id'] = array('eq', $uid);
        $data = $m->field('username,sex,signature,birthday')->where($condition)->find();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '个人资料');
        $this->assign('sidebar_active', 'personal');
        $this->assign('data', $data);
        $this->display($skin . ':personal');
    }

    /**
     * personal
     * 个人资料
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function email()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $condition['id'] = array('eq', $uid);
        $data = $m->field('email,email_status')->where($condition)->find();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '邮箱信息');
        $this->assign('sidebar_active', 'email');
        $this->assign('data', $data);
        $this->display($skin . ':email');
    }

    /**
     * changePwd
     * 修改密码
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function changePwd()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '修改密码');
        $this->assign('sidebar_active', 'changepwd');
        $this->display($skin . ':changepwd');
    }

    /**
     * addressList
     * 收货地址列表
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressList()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $data = $m->where($condition)->select();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '收货地址列表');
        $this->assign('sidebar_active', 'address');
        $this->assign('list', $data);
        $this->display($skin . ':address_list');
    }

    /**
     * addressAdd
     * 收货地址-添加
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressAdd()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '添加收货地址');
        $this->assign('sidebar_active', 'address');
        $this->display($skin . ':address_add');
    }

    /**
     * addressEdit
     * 收货地址-编辑
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressEdit()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $condition['id'] = array('eq', $this->_get('id'));
        $data = $m->where($condition)->find();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '修改收货地址');
        $this->assign('sidebar_active', 'changepwd');
        $this->assign('data', $data);
        $this->display($skin . ':address_edit');
    }

    /**
     * apiList
     * api 接口列表信息
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiList()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $data = $m->where($condition)->select();
        foreach($data as $k=>$v){
            if($v['status']=='20'){
                $data[$k]['status'] = '可用';
            }else{
                $data[$k]['status'] = '禁用';
            }
        }
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', 'API列表');
        $this->assign('sidebar_active', 'apilist');
        $this->assign('list', $data);
        $this->display($skin . ':api_list');
    }

    /**
     * apiListAdd
     * api接口-添加
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiListAdd()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '添加API信息');
        $this->assign('sidebar_active', 'apilist');
        $this->display($skin . ':api_add');
    }

    /**
     * apiListEdit
     * api接口-编辑
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiListEdit()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $condition['id'] = array('eq', $this->_get('id'));
        $data = $m->where($condition)->find();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '修改API信息');
        $this->assign('sidebar_active', 'apilist');
        $this->assign('data', $data);
        $this->display($skin . ':api_edit');
    }

    /**
     * doPersonal
     * 更新个人资料
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function doPersonal()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $condition['id'] = array('eq', $uid);
        $data['updatetime'] = time();
        $data['sex'] = $this->_post('sex');
        $data['birthday'] = strtotime($this->_post('birthday'));
        $data['signature'] = $this->_post('signature');
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/personal');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * doEmail
     * 更新邮箱
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function doEmail()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $condition['id'] = array('eq', $uid);
        $data['updatetime'] = time();
        $data['email'] = $this->_post('email');
        $condition_email['email'] = array('eq', $data['email']);
        $condition_email['id'] = array('neq', $uid);
        //判断该邮箱是否存在
        $data_email = $m->where($condition_email)->find();
        if ($data_email) {
            $this->error('您要更改的邮箱已存在，请重新操作！');
            exit();
        }
        $data_one = $m->field('email')->where($condition)->find();
        if ($data_one['email'] != $data['email']) {
            $data['email_status'] = 10;
        } else {
            unset($data['email']);
        }
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/email');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * authEmail
     * 发送验证邮箱信息
     * @return display
     * @version dogocms 1.0
     * @todo 调用邮件接口
     */
    public function authEmail()
    {
        $array = array('status' => 1, 'msg' => 'ceshi');
        echo json_encode($array);
    }

    /**
     * doChangePwd
     * 更新密码
     * @return display
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function doChangePwd()
    {
        $m = new MembersModel();
        $uid = session('LOGIN_M_ID');
        $uname = session('LOGIN_M_NAME');
        $oldpwd = $this->_post('oldpwd'); //原密码
        $newpwd = $this->_post('newpwd'); //新密码1
        $newpwd2 = $this->_post('newpwd2'); //新密码2
        if (empty($oldpwd) || empty($newpwd) || empty($newpwd2)) {
            $this->error('密码项不能为空！');
            exit;
        }
        if ($newpwd != $newpwd2) {
            $this->error('两次新密码输入不正确！');
            exit;
        }
        $condition['id'] = array('eq', $uid);
        $data_find = $m->field('password')->where($condition)->find();
        $oldpwd = R('Api/News/getPwd', array($uname, $oldpwd));
        if ($oldpwd != $data_find['password']) {
            $this->error('原密码输入不正确，请重新输入！');
            exit;
        }
        $password = R('Api/News/getPwd', array($uname, $newpwd));
        $data['password'] = $password;
        $data['updatetime'] = time();
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/changePwd');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * addressInsert
     * 添加收货地址
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressInsert()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $count = $m->where($condition)->count();
        if ($count >= 5) {
            $this->error('最多可以设置5条收货地址！');
            exit;
        }
        if ($_POST['is_default']) {
            $m->where($condition)->setField('is_default', 10);
            $_POST['is_default'] = 20;
        }
        $_POST['addtime'] = time();
        $_POST['members_id'] = $uid;
        $_POST['updatetime'] = time();
        $rs = $m->data($_POST)->add();
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/addressList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * addressUpdate
     *  更新收货地址
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressUpdate()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        if ($_POST['is_default']) {
            $m->where($condition)->setField('is_default', 10);
            $_POST['is_default'] = 20;
        }
        $condition['id'] = array('eq', $this->_post('id'));
        $_POST['updatetime'] = time();
        $rs = $m->where($condition)->save($_POST);
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/addressList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * addressDelete
     *  删除收货地址
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function addressDelete()
    {
        $m = new MembersAddressModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $condition['id'] = array('eq', $this->_get('id'));
        $rs = $m->where($condition)->delete();
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/addressList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * apiInsert
     * 添加api
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiInsert()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $apitoken = $this->_post('apitoken');
        if(empty($apitoken)){
            $this->error('token信息不能为空！');
            exit;
        }
        $_POST['addtime'] = time();
        $_POST['members_id'] = $uid;
        $_POST['updatetime'] = time();
        $_POST['status'] = 10;
        $_POST['apitoken'] = $apitoken;//API用户名
        $secretkey = R('Api/News/guid');
        $signature = R('Api/News/guid');
        $_POST['secretkey'] = md5($secretkey);//API密钥（自动生成）
        $_POST['signature'] = md5(sha1($signature));//签名（自动生成）
        $_POST['domain'] = $this->_post('domain');
        $rs = $m->data($_POST)->add();
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/apiList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * apiUpdate
     *  更新api
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiUpdate()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $condition['id'] = array('eq', $this->_post('id'));
        $_POST['updatetime'] = time();
        $_POST['status'] = '10';
        $rs = $m->where($condition)->save($_POST);
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/apiList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

    /**
     * apiDelete
     *  删除api
     * @return display
     * @version dogocms 1.0
     * @todo 
     */
    public function apiDelete()
    {
        $m = new ApiListModel();
        $uid = session('LOGIN_M_ID');
        $condition['members_id'] = array('eq', $uid);
        $condition['id'] = array('eq', $this->_get('id'));
        $rs = $m->where($condition)->delete();
        if ($rs == true) {
            $this->success('操作成功', __GROUP__ . '/Index/addressList');
        } else {
            $this->error('操作失败，请重新操作！');
        }
    }

}
