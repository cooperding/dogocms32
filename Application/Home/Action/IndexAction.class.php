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
namespace Home\Action;
use Think\Action;
class IndexAction extends BasehomeAction {

    public function index()
    {
        $t = D('Title');
        import('ORG.Util.DingPage'); // 导入分页类
        $condition['t.status'] = array('eq', '12');
        $count = $t->Table(C('DB_PREFIX') . 'title t')
                        ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                        ->where($condition)->count();
        $page = new \Org\Util\QiuyunPage($count, 5);
        //$page = new DingPage($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('header', '条记录');
        $page->setConfig('theme', "%upPage% %downPage% %first% %prePage% %linkPage% %nextPage% %end% <li><span>%totalRow% %header% %nowPage%/%totalPage% 页</span></li>");
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

        $m = D('Setting');
        $title['sys_name'] = array('eq', 'cfg_title');
        $keywords['sys_name'] = array('eq', 'cfg_keywords');
        $description['sys_name'] = array('eq', 'cfg_description');
        $data_title = $m->where($title)->find();
        $data_keywords = $m->where($keywords)->find();
        $data_description = $m->where($description)->find();

        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('title', $data_title['sys_value']);
        $this->assign('keywords', $data_keywords['sys_value']);
        $this->assign('description', $data_description['sys_value']);
        $this->assign('list', $list);
        $this->assign('page', $show); // 赋值分页输出
        $this->theme($skin)->display(':index');
    }

}
