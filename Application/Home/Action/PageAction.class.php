<?php

/**
 * PageAction.class.php
 * 前台首页
 * 前台单页文件
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 完善更多方法
 */
namespace Home\Action;
use Think\Action;
class PageAction extends BasehomeAction {

    public function index($id)
    {
        //取得本分类下的所有列表信息，并分页，如果需要还需要读取到模型内的字段
        if (!$id) {
            echo '错误'; //跳转到错误页面
            exit;
        }
        import('ORG.Util.Page'); // 导入分页类
        $t = M('Title');
        $ns = M('NewsSort');
        $condition['path'] = array('like', '%,' . $id . ',%');
        $condition['id'] = array('eq', $id);
        $condition['_logic'] = 'OR';
        $sort_data = $ns->where($condition)->select();
        $one_data = $ns->where('id=' . $id)->find();
        foreach ($sort_data as $k => $v) {
            $sort_id .= $v['id'] . ',';
        }
        $sort_id = rtrim($sort_id, ', ');
        $title['sort_id'] = array('in', $sort_id);
        $title['status'] = array('eq', 'true');
        $title['is_recycle'] = array('eq', 'false');
        //$data = $t->where($title)->select();
        $count = $t->where($title)->count();
        $Page = new Page($count, 5); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $t->where($title)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => $v) {
            $list_sort = $ns->field(array('ns.text', 'ns.en_name', 'ms.ename', 'ms.emark', 'ms.id' => 'mid'))
                            ->Table(C('DB_PREFIX') . 'news_sort  ns')
                            ->join(C('DB_PREFIX') . 'model_sort ms ON ms.id=ns.model_id')
                            ->where('ns.id=' . $v['sort_id'])->find();
            $list[$k]['sortname'] = $list_sort['text'];
            //取得内容
            $m = M(ucfirst(C('DB_ADD_PREFIX') . $list_sort['emark']));
            $mdata = $m->where('title_id=' . $v['id'])->find();
            if ($mdata) {
                $list[$k] = array_merge($list[$k], $mdata);
            }
        }
        $this->assign('dogocms', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('title', $one_data['text']);
        $this->assign('keywords', $one_data['keywords']);
        $this->assign('description', $one_data['description']);
        $this->display(':list');
        $this->theme($skin)->display(':page');
    }

}
