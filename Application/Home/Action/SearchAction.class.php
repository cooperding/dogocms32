<?php

/**
 * SearchAction.class.php
 * 前台搜索文件
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-01-31 13:30
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Home\Action;
use Think\Action;
class SearchAction extends BasehomeAction {

    public function index()
    {
        //分析：根据提供的关键词查询title或者扩展内容表（暂定）,同时需要查询的表有分类表。
        $words = addslashes($_GET['words']);
        if (!$words) {
            echo '错误'; //跳转到错误页面
            exit;
        }
        import('ORG.Util.Page'); // 导入分页类
        $t = M('Title');
        $ns = M('NewsSort');
        $condition['t.title'] = array('like', '%' . $words . '%');
        $condition['t.keywords'] = array('like', '%' . $words . '%');
        $condition['_logic'] = 'OR';
        $count = $t->Table(C('DB_PREFIX') . 'title t')->where($condition)->count();
        $Page = new Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $t->field(array('t.*', 'ns.text' => 'sortname', 'ms.emark'))
                ->Table(C('DB_PREFIX') . 'title t')
                ->join(C('DB_PREFIX') . 'news_sort ns ON ns.id = t.sort_id')
                ->join(C('DB_PREFIX') . 'model_sort ms ON ms.id = ns.model_id')
                ->where($condition)->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        foreach ($list as $k => $v) {
            $m = M(ucfirst(C('DB_ADD_PREFIX') . $v['emark']));
            $mdata = $m->where('title_id=' . $v['id'])->find();
            if ($mdata) {
                $list[$k] = array_merge($list[$k], $mdata);
            }
        }
        $this->assign('dogocms', $list); // 赋值数据集
        $this->assign('words', $words);
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('title', $one_data['text']);
        $this->assign('keywords', $one_data['keywords']);
        $this->assign('description', $one_data['description']);
        $this->display(':search');
    }

}
