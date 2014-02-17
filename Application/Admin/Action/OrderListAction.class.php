<?php

/**
 * OrderListAction.class.php
 * 订单管理
 * @author 正侠客 knight<lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-10-15 11:23
 * @package  Controller
 * @todo 信息各项操作
 */
namespace Admin\Action;
use Think\Action;
class OrderListAction extends BaseAction {

    /**
     * index
     * 信息列表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }

    /**
     * edit
     * 编辑信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function edit()
    {
        $m = D('Title');
        $id = I('get.id');
        $condition_id['t.id'] = array('eq', $id);
        $data = $m->field(array('t.*', 'c.content', 'ms.id' => 'msid', 'ms.emark' => 'msemaerk'))->Table(C('DB_PREFIX') . 'title t')->join(C('DB_PREFIX') . 'content c ON c.title_id = t.id ')
                        ->join(C('DB_PREFIX') . 'news_sort ns ON ns.id=t.sort_id')->join(C('DB_PREFIX') . 'model_sort ms ON ms.id=ns.model_id')
                        ->where($condition_id)->find();
        $am = M(ucfirst(C('DB_ADD_PREFIX')) . $data['msemaerk']);
        $condition_tid['title_id'] = array('eq', $id);
        $data_ms = $am->where($condition_tid)->find();
        $mf = new ModelFieldModel();
        $condition_sid['sort_id'] = array('eq', $data['msid']);
        $data_filed = $mf->where($condition_sid)->order('myorder asc,id asc')->select();
        foreach ($data_filed as $k => $v) {
            $exp = explode(',', $v['evalue']);
            if ($v['etype'] == 'radio') {
                $data_filed[$k]['opts'] = $exp;
            } elseif ($v['etype'] == 'checkbox') {
                $data_filed[$k]['opts'] = $exp;
            } elseif ($v['etype'] == 'select') {
                $data_filed[$k]['opts'] = $exp;
            }
        }
        $flag = array(
            'h' => ' 头条[h] ',
            'r' => ' 推荐[r] ',
            's' => ' 特荐[s] ',
            't' => ' 置顶[t] ',
            'p' => ' 图片[p] ',
            'j' => ' 跳转[j] '
        );
        $radios = array(
            '20' => ' 审核 ',
            '10' => ' 未审核 ',
            '11' => ' 未通过审核 '
        );
        $this->assign('data', $data);
        $this->assign('filed', $data_filed);
        $this->assign('datafiled', $data_ms);
        $this->assign('flag', $flag);
        $this->assign('radios', $radios);
        $this->assign('v_status', $data['status']);
        $this->display();
    }

    /**
     * update
     * 更新信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function update()
    {
        $t = D('Title');
        $c = D('Content');
        $ns = D('NewsSort');
        $id = I('post.id');
        $data['id'] = array('eq', $id);
        $cdata['title_id'] = array('eq', $id);
        $title = I('post.title');
        $sort_id = I('post.sort_id');
        if (empty($title)) {
            $this->dmsg('1', '文章标题不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择文档分类！', false, true);
        }
        $_POST['flag'] = implode(',', $_POST['flag']);
        $filed = array();
        foreach ($_POST['filed'] as $k => $v) {
            $filed[$k] = $v;
        }
        foreach ($_POST['filedtime'] as $k => $v) {
            $filed[$k] = strtotime($v);
        }
        foreach ($_POST['filedcheckbox'] as $k => $v) {
            $filed[$k] = implode(',', $v);
        }
        //通过取得的栏目id获得模型id，然后通过模型id获得模型的标识名（即表名），通过表名实例化相应的表信息
        $condition_ns['ns.id'] = array('eq', $sort_id);
        $model_rs = $ns->field('ms.emark')->Table(C('DB_PREFIX') . 'news_sort ns')
                        ->join(C('DB_PREFIX') . 'model_sort ms ON ms.id = ns.model_id ')
                        ->where($condition_ns)->find();
        $m = M(ucfirst(C('DB_ADD_PREFIX')) . $model_rs['emark']);
        $_POST['updatetime'] = time();
        $_POST['op_id'] = session('LOGIN_UID');
        $_POST['status'] = $_POST['status']['0'];
        $rs = $t->where($data)->save($_POST);
        $rsc = $c->where($cdata)->save($_POST);
        $rsm = $m->where($cdata)->save($filed);
        if ($rs == true || $rsc == true || $rsm == true) {
            $this->dmsg('2', '更新成功！', true);
        } else {
            $this->dmsg('1', '更新失败,或者未有更新！', false, true);
        }
    }

    /**
     * delete
     * 删除文档到回收站
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function delete()
    {
        $t = D('Title');
        $id = I('post.id');
        $data['id'] = array('in', $id);
        if (empty($data['id'])) {
            $this->dmsg('1', '未有id值，操作失败！', false, true);
        }
        $rs = $t->where($data)->setField('is_recycle', 'y');
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * listJsonId
     * 取得field信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function listJsonId()
    {
        $m = D('OrderList');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->where($condition)->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        $array = array();
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['status'] == '10') {
                    $data[$k]['status'] = '未联系';
                } elseif ($v['status'] == '11') {
                    $data[$k]['status'] = '联系可发货';
                } elseif ($v['status'] == '12') {
                    $data[$k]['status'] = '已发货';
                } elseif ($v['status'] == '13') {
                    $data[$k]['status'] = '已签收';
                } elseif ($v['status'] == '14') {
                    $data[$k]['status'] = '拒签';
                } elseif ($v['status'] == '15') {
                    $data[$k]['status'] = '取消订单';
                } elseif ($v['status'] == '16') {
                    $data[$k]['status'] = '联系失败';
                } elseif ($v['status'] == '17') {
                    $data[$k]['status'] = '作废订单';
                }
            }
        } else {
            $data = array();
        }
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

}
