<?php

/**
 * MembersAction.class.php
 * 前台会员控制器，用于他人查看当前会员信息
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Home\Action;
use Think\Action;
class UserAction extends BasehomeAction {

    public function index()
    {
        $uid = I('get.uid');
        $m = D('Members'); //实例化会员信息表
        $t = D('Title');
        $condition['t.status'] = array('eq', '12');
        $condition['t.members_id'] = array('eq', $uid);
        $count = $t->Table(C('DB_PREFIX') . 'title t')
                        ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                        ->where($condition)->count();
        $page = new \Org\Util\QiuyunPage($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('header', '条记录');
        $page->setConfig('theme', "%UP_PAGE% %FIRST% %LINK_PAGE% %DOWN_PAGE% %END% <li><span>%TOTAL_ROW% %HEADER% %NOW_PAGE%/%TOTAL_PAGE% 页</span></li>");
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $t->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                ->join(C('DB_PREFIX') . 'members m ON t.members_id = m.id ')
                ->where($condition)
                ->field('t.*,c.*,m.username')
                ->order('t.id desc')
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        $condition_members['id'] = array('eq', $uid);
        $data_members = $m->field('id,username,addtime')->where($condition_members)->find();
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', $data_members['username']);
        $this->assign('keywords', $data_members['username']);
        $this->assign('description', $data_members['username']);
        $this->assign('list', $list);
        $this->assign('members_data', $data_members);
        $this->theme($skin)->display(':user');
    }

}
