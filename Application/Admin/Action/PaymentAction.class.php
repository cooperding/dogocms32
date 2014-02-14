<?php

/**
 * PaymentAction.class.php
 * 支付方式信息
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-09-19 19:46
 * @package  Controller
 * @todo
 */
class PaymentAction extends BaseAction {

    /**
     * index
     * 支付列表页
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index() {
        $this->display();
    }

    /**
     * add
     * 添加信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function add() {
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
    public function edit() {
        $m = new BlockListModel();
        $id = $this->_get('id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $this->assign('status', $status);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * insert
     * 写入碎片信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert() {
        $m = new BlockListModel();
        $title = $this->_post('title');
        if (empty($title)) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        $_POST['status'] = $_POST['status']['0'];
        $_POST['addtime'] = time();
        $_POST['updatetime'] = time();
        if ($m->create()) {
            $rs = $m->add($_POST);
            if ($rs) {//存在值
                $this->dmsg('2', '操作成功！', true);
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
    public function update() {
        $m = new BlockListModel();
        $id = $this->_post('id');
        $title = $this->_post('title');
        $sort_id = $this->_post('sort_id');
        $condition['id'] = array('eq', $id);
        if (empty($title)) {
            $this->dmsg('1', '网站名不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $_POST['updatetime'] = time();
        $_POST['status'] = $_POST['status']['0'];
        $rs = $m->where($condition)->save($_POST);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * delete删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete() {
        $m = new BlockListModel();
        $id = $this->_post('id');
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    

}

?>