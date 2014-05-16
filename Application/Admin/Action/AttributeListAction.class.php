<?php

/**
 * AttributeListAction.class.php
 * 商品属性列表
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class AttributeListAction extends BaseAction {

    /**
     * index
     * 商品属性列表页
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }
    /**
     * newslist
     * 信息列表 在执行index之后进行的下一步操作
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function newslist() {
        $id = I('get.id');
        $this->assign('id', $id);
        $this->display('list');
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
        $id = I('get.id');
        $attr_index = array(
            '0' => ' 不需要检索 ',
            '1' => ' 关键字检索 ',
            '2' => ' 范围检索 '
        );
        $is_linked = array(
            '0' => ' 否 ',
            '1' => ' 是 '
        );
        $attr_type = array(
            '0' => ' 唯一属性 ',
            '1' => ' 单选属性 ',
            '2' => ' 复选属性 '
        );
        $attr_input_type = array(
            '0' => ' 手工录入 ',
            '1' => ' 从下面的列表中选择（一行代表一个可选值） ',
            '2' => ' 多行文本框 '
        );
        $this->assign('attr_index', $attr_index);
        $this->assign('is_linked', $is_linked);
        $this->assign('attr_input_type', $attr_input_type);
        $this->assign('attr_type', $attr_type);
        $this->assign('id', $id);
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
        $m = D('AttributeList');
        $id = I('get.id');
        $condition['id'] = array('eq',$id);
        $data = $m->where($condition)->find();
        $attr_index = array(
            '0' => ' 不需要检索 ',
            '1' => ' 关键字检索 ',
            '2' => ' 范围检索 '
        );
        $is_linked = array(
            '0' => ' 否 ',
            '1' => ' 是 '
        );
        $attr_type = array(
            '0' => ' 唯一属性 ',
            '1' => ' 单选属性 ',
            '2' => ' 复选属性 '
        );
        $attr_input_type = array(
            '0' => ' 手工录入 ',
            '1' => ' 从下面的列表中选择（一行代表一个可选值） ',
            '2' => ' 多行文本框 '
        );
        $this->assign('attr_index', $attr_index);
        $this->assign('is_linked', $is_linked);
        $this->assign('attr_input_type', $attr_input_type);
        $this->assign('attr_type', $attr_type);
        
        $this->assign('radio_attr_index', $data['attr_index']);
        $this->assign('radio_is_linked', $data['is_linked']);
        $this->assign('radio_attr_input_type', $data['attr_input_type']);
        $this->assign('radio_attr_type', $data['attr_type']);
        $this->assign('data', $data);
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
        $m = D('AttributeList');
        $data['attr_name'] = I('post.attr_name');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['attr_name'])) {
            $this->dmsg('1', '商品类型名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['attr_index'] = I('post.attr_index')['0'];
        $data['is_linked'] = I('post.is_linked')['0'];
        $data['attr_input_type'] = I('post.attr_input_type')['0'];
        $data['attr_type'] = I('post.attr_type')['0'];
        $data['updatetime'] = time();
        $data['myorder'] = I('post.myorder');
        $data['attr_values'] = I('post.attr_values');
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
        $m = D('AttributeList');
        $condition['id'] = array('eq', I('post.id'));
        $data['attr_name'] = I('post.attr_name');
        $data['sort_id'] = I('post.sort_id');
        if (empty($data['attr_name'])) {
            $this->dmsg('1', '商品类型名称不能为空！', false, true);
        }
        if ($data['sort_id'] == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $data['attr_index'] = I('post.attr_index')['0'];
        $data['is_linked'] = I('post.is_linked')['0'];
        $data['attr_input_type'] = I('post.attr_input_type')['0'];
        $data['attr_type'] = I('post.attr_type')['0'];
        $data['updatetime'] = time();
        $data['myorder'] = I('post.myorder');
        $data['attr_values'] = I('post.attr_values');
        
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
        $condition['id'] = array('eq',I('post.id'));
        $m = D('AttributeList');
        $del = $m->where($condition)->delete();
        if ($del == true) {
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
    public function listJsonId() {
        $m = D('AttributeList');
        $id = I('get.id');
        if ($id != 0) {//id为0时调用全部文档
            $condition['sort_id'] = $id;
        }
        $pageNumber = intval($_POST['page']);
        $pageRows = intval($_POST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->where($condition)->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        $array = array();
        if (!$data) {
            $data = array();
        }
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }
    
}

?>