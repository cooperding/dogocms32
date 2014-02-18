<?php

/**
 * PassportAction.class.php
 * 后台登录页面
 * 后台核心文件，用于后台登录操作验证
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:20
 * @package  Controller
 */
namespace User\Action;
use Think\Action;
class PassportAction extends Action {

    //初始化
    function _initialize()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $navhead = R('Api/News/getNav', array('header')); //导航菜单
        $this->assign('navhead', $navhead);
        $this->assign('style', __PUBLIC__ . '/Skin/Member/' . $skin);
        $this->assign('style_cmomon', __PUBLIC__ . '/Common');
        $this->assign('header', './App/Tpl/Member/' . $skin . '/header.html');
        $this->assign('footer', './App/Tpl/Member/' . $skin . '/footer.html');
    }

    /**
     * index
     * 进入登录页面
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 权限验证
     */
    public function index()
    {

        //此处判断是否已经登录，如果登录跳转到后台首页否则跳转到登录页面
        $status = session('LOGIN_M_STATUS');
        if ($status == 'TRUE') {
            $this->redirect('..' . __GROUP__);
        } else {
            $this->login();
        }
    }

    /*
     * login 
     * 会员登录
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function login()
    {
        cookie('gobackurl', $_SERVER['HTTP_REFERER']);
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '会员登录');
        $this->theme($skin)->display(':login');
    }

    /*
     * signup 
     * 注册会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function signup()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', '会员注册');
        $this->display($skin . ':signup');
    }

    /*
     * resetPassword 
     * 注册会员
     * @access public
     * @return array
     * @version dogocms 1.0
     */

    public function resetPassword()
    {
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        
        $this->assign('title', '重置密码');
        $this->display($skin . ':resetpwd');
    }

    /**
     * checkLogin
     * 登录验证
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function checkLogin()
    {
        $m = new MembersModel();
        $v_code = $this->_post('v_code');
        $verify = session('verify_member');
        $type = $this->_post('type');
        if (empty($v_code) || md5($v_code) != $verify) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '验证码为空或者输入错误！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('验证码为空或者输入错误！');
                exit;
            }
        }
        $email = $this->_post('email'); //邮箱
        if (empty($email)) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '注册邮箱不能为空！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('注册邮箱不能为空！');
                exit;
            }
        }
        $pwd = $this->_post('pwd'); //密码
        if (empty($pwd)) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '密码不能为空！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('密码不能为空！');
                exit;
            }
        }
        $condition['email'] = array('eq', $email);
        $rs = $m->where($condition)->field('id,username,addtime,password,status')->find();
        if ($rs) {
            $uname = $rs['username'];
            $password = R('Api/News/getPwd', array($uname, $pwd));
            if ($password == $rs['password']) {//密码匹配
                if ($rs['status'] == '10') {//禁用账户，不可登录
                    if ($type == '10') {
                        $array = array('status' => 1, 'msg' => '您的账户被禁止登录！');
                        echo json_encode($array);
                        exit;
                    } else {
                        $this->error('您的账户被禁止登录！' , __ROOT__);
                        exit();
                    }
                } else {
                    session('LOGIN_M_STATUS', 'TRUE');
                    session('LOGIN_M_NAME', $rs['username']);
                    session('LOGIN_M_ID', $rs['id']);
                    session('LOGIN_M_ADDTIME', $rs['addtime']);
                    session('LOGIN_M_LOGINTIME', time());
                    if ($type == '10') {
                        $array = array('status' => 0, 'msg' => '登陆成功！');
                        echo json_encode($array);
                        exit;
                    } else {
                        $this->success('登陆成功！', __GROUP__);
                    }
                }
            } else {
                if ($type == '10') {
                    $array = array('status' => 1, 'msg' => '您的输入用户名或者密码错误！');
                    echo json_encode($array);
                    exit;
                } else {
                    $this->error('您的输入用户名或者密码错误！');
                }
            }
        } else {//未查询到数据
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '您的输入用户名或者密码错误！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('您的输入用户名或者密码错误！');
            }
        }
    }

    /**
     * getNewPwd
     * 找回新的密码
     * @access public
     * @return boolean
     * @version dogocms 1.0
     * @todo 完善密码找回操作，增加邮件发送功能
     */
    public function getNewPwd()
    {
        $m = new MembersModel();
        $v_code = $this->_post('v_code');
        $verify = session('verify_member');
        if (empty($v_code) || md5($v_code) != $verify) {
            $this->error('验证码为空或者输入错误！');
            exit;
        }
        $email = $this->_post('email'); //邮箱
        if (empty($email)) {
            $this->error('注册邮箱不能为空！');
            exit;
        }
        //先验证邮箱是否存在
        $condition['email'] = array('eq', $email);
        $rs = $m->where($condition)->field('id,username')->find();
        if (!$rs) {
            $this->error('注册邮箱不正确！');
            exit;
        }
        //随机生成密码，并发送到注册邮箱中
        $pwd = rand(100000, 999999);
        $uname = $rs['username'];
        $password = R('Api/News/getPwd', array($uname, $pwd));
        $data['password'] = $password;
        $data['updatetime'] = time();
        $condition_id['id'] = $rs['id'];
        $rs_pwd = $m->where($condition_id)->save($data);
        if ($rs_pwd == true) {
            $rs_email = R('Api/News/sendEmail', array($email, '找回密码', $pwd));
            if ($rs_email) {
                $this->success('重置密码成功，请登录邮箱查看！', __GROUP__);
            } else {
                $this->error('重置密码失败，请重新发送！');
            }
        } else {
            $this->error('重置密码失败！');
        }
    }

    /**
     * register
     * 注册会员
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function register()
    {
        $m = new MembersModel();
        $v_code = $this->_post('v_code');
        $verify = session('verify_member');
        $type = $this->_post('type');
        if (empty($v_code) || md5($v_code) != $verify) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '验证码为空或者输入错误！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('验证码为空或者输入错误！');
            }
        }
        $uname = $this->_post('uname'); //用户名
        $email = $this->_post('email'); //邮箱
        $pwd = $this->_post('pwd'); //密码
        $pwd2 = $this->_post('pwd2'); //密码2
        if (empty($uname)) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '用户名不能为空！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('用户名不能为空！');
            }
        }
        if (empty($email)) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '邮箱不能为空！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('邮箱不能为空！');
            }
        }
        if (empty($pwd) || empty($pwd2)) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '密码不能为空！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('密码不能为空！');
            }
        }
        if ($pwd != $pwd2) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '两次密码输入不一致！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('两次密码输入不一致！');
            }
        }
        $condition_uname['username'] = array('eq', $uname);
        $rs_uname = $m->where($condition_uname)->find();
        if ($rs_uname) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '用户名已经存在！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('用户名已经存在！');
            }
        }
        $condition_email['email'] = array('eq', $email);
        $rs_email = $m->where($condition_email)->find();
        if ($rs_uname) {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '邮箱已经存在！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('邮箱已经存在！');
            }
        }
        $password = R('Api/News/getPwd', array($uname, $pwd));
        $data['username'] = $uname;
        $data['password'] = $password;
        $data['addtime'] = time();
        $data['updatetime'] = time();
        $data['email'] = $email;
        $data['ip'] = get_client_ip();
        $rs = $m->data($data)->add();
        if ($rs == true) {
            if ($type == '10') {
                $array = array('status' => 0, 'msg' => '注册成功,请登录后操作！');
                echo json_encode($array);
                exit;
            } else {
                $this->success('注册成功,请登录后操作！', __GROUP__ . '/Passport/login');
            }
        } else {
            if ($type == '10') {
                $array = array('status' => 1, 'msg' => '注册失败，请联系管理员！');
                echo json_encode($array);
                exit;
            } else {
                $this->error('注册失败，请联系管理员！');
            }
        }
    }

    /**
     * logout
     * 退出登录，清除session
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function logout()
    {
        $type = $this->_post('type');
        session('[destroy]');
        if ($type == '10') {
            $array = array('status' => 0, 'msg' => '您已经成功退出会员系统！');
            echo json_encode($array);
            exit;
        } else {
            $this->success('您已经成功退出会员系统！', __ROOT__);
        }
    }

    /**
     * vercode
     * 生成验证码
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function vercode()
    {
        import("ORG.Util.Image");
        $length = 4; //验证码的长度
        $mode = 1; //0 字母 1 数字 2 大写字母 3 小写字母 4中文 5混合
        $type = 'png'; //验证码的图片类型，默认为png
        $width = 50; //验证码的宽度
        $height = 24; //验证码的高度
        $verifyName = 'verify_member'; //验证码的SESSION记录名称
        Image::buildImageVerify($length, $mode, $type, $width, $height, $verifyName);
    }

    /*
     * getSkin
     * 获取站点设置的会员中心主题名称
     * @todo 使用程序读取主题皮肤名称
     */

    public function getSkin()
    {
        $skin = R('Common/News/getCfg', array('cfg_member_skin'));
        if (!$skin) {
            $skin = 'default';
        }
        return $skin;
    }
    
}
