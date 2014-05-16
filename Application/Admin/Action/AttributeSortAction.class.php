<?php

/**
 * AttributeSortAction.class.php
 * 商品属性分类
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-09-02 09:28
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class AttributeSortAction extends BaseAction {

    /**
     * index
     * 商品属性分类类型列表页
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }

    /**
     * add
     * 添加信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function add()
    {
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
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
        $m = D('AttributeSort');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => '可用',
            '10' => '禁用'
        );
        $this->assign('status', $status);
        $this->assign('data', $data);
        $this->assign('v_status', $data['status']);
        $this->display();
    }

    /**
     * insert
     * 插入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = D('AttributeSort');
        $data['cat_name'] = I('post.cat_name');
        if (empty($data['cat_name'])) {
            $this->dmsg('1', '商品类型名称不能为空！', false, true);
        }
        $data['status'] = I('post.status')['0'];
        $data['updatetime'] = time();
        $data['attr_group'] = I('post.attr_group');
        if ($m->create($data)) {
            $rs = $m->add();
            if ($rs == true) {
                $this->dmsg('2', ' 操作成功！', true);
            } else {
                $this->dmsg('1', '操作失败！', false, true);
            }
        } else {
            $this->dmsg('1', '根据表单提交的POST数据创建数据对象失败！', false, true);
        }
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
        $m = D('AttributeSort');
        $data['cat_name'] = I('post.cat_name');
        $condition['id'] = array('eq', I('post.id'));
        if (empty($data['cat_name'])) {
            $this->dmsg('1', '商品类型名称不能为空！', false, true);
        }
        $data['status'] = I('post.status')['0'];
        $data['updatetime'] = time();
        $data['attr_group'] = I('post.attr_group');
        $rs = $m->where($condition)->save($data);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * 留言删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = D('AttributeSort');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * jsonList
     * 取得列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonList()
    {
        $m = D('AttributeSort');
        $pageNumber = intval($_POST['page']);
        $pageRows = intval($_POST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        $array = array();
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['status'] == '20') {
                    $data[$k]['status'] = '启用';
                } elseif ($v['status'] == '10') {
                    $data[$k]['status'] = '禁用';
                }
            }
        } else {
            $data = array();
        }
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

    /**
     * jsonTree
     * 类型json树结构数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree()
    {
        $m = D('AttributeSort');
        $tree = $m->field('id,cat_name as text')->select();
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
    }

    /**
     * jsonSortTree
     * 分类树信息json数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonSortTree()
    {
        $m = D('AttributeSort');
        $tree = $m->field('id,cat_name as text')->select();
        $tree = array_merge(array(array('id' => 0, 'text' => '全部文档')), $tree);
        echo json_encode($tree);
    }

}

?>