<?php

/**
 * ContentAction.class.php
 * 前台首页
 * 前台内容页面文件
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 过滤无用字段,筛选符合条件的信息（需要全站共同使用）
 */
namespace Home\Action;
use Think\Action;
class ContentAction extends BasehomeAction {

    public function index($id)
    {
        //接收到的是文档title id，通过该id查询取得相应的内容
        //$id = intval($id);
        $t = D('Title');
        $condition['t.id'] = array('eq', $id);
        $condition['t.status'] = array('eq', '12'); //12已审核
        $condition['t.is_recycle'] = array('eq', '10'); //10未加入回收站

        $data = $t->field(array('t.*', 'c.*'))
                ->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                ->join(C('DB_PREFIX') . 'members m ON t.members_id = m.id ')
                ->field('t.*,c.*,m.username')
                ->where($condition)
                ->find();
        if ($data) {
            //浏览量赋值+1
            $condition_id['id'] = array('eq', $id);
            $t->where($condition_id)->setInc('views', 1);

            //获取评论信息
            $c = D('Comment');
            $condition_comment['c.title_id'] = array('eq', $id);
            $condition_comment['c.status'] = array('eq', 20);
            $comment = $c->field(array('m.id as uid,m.username', 'c.*'))
                            ->Table(C('DB_PREFIX') . 'comment c')
                            ->join(C('DB_PREFIX') . 'members m ON m.id = c.members_id ')
                            ->where($condition_comment)->order('floor asc')->select();
        }
        $skin = $this->getSkin(); //获取前台主题皮肤名称
        $this->assign('data', $data);
        $this->assign('list_comment', $comment);
        $this->assign('title', $data['title']);
        $this->assign('keywords', $data['keywords']);
        $this->assign('description', $data['description']);
        $this->theme($skin)->display(':content');
    }

}
