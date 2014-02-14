<?php

/**
 * MessageAction.class.php
 * 留言信息
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo
 */
class MessageAction extends BaseAction {

    /**
     * index
     * 列表页
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
        $m = new MessageModel();
        $id = $this->_get('id');
        $condition['id'] = array('eq',$id);
        $data = $m->where($condition)->find();
        $radios = array(
            'y' => '可用',
            'n' => '禁用'
        );
        $this->assign('radios', $radios);
        $this->assign('data', $data);
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
        $m = new MessageModel();
        $sort_id = $this->_post('sort_id');
        $id = $this->_post('id');
        $data['id'] = array('eq',$id);
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $_POST['replaytime'] = time();
        $_POST['status'] = $_POST['status']['0'];
        $_POST['updatetime'] = time();
        $rs = $m->where($data)->save($_POST);
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
        $m = new MessageModel();
        $id = $this->_post('id');
        $condition['id'] = array('eq',$id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sort
     * 留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sort()
    {
        $this->display();
    }

    /**
     * sortadd
     * 添加留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortadd()
    {
        $radios = array(
            'y' => '启用',
            'n' => '禁用'
        );
        $this->assign('radios', $radios);
        $this->display();
    }

    /**
     * sortedit
     * 编辑留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortedit()
    {
        $m = new MessageSortModel();
        $id = $this->_get('id');
        $condition['id'] = array('eq',$id);
        $data = $m->where($condition)->find();
        $radios = array(
            'y' => '启用',
            'n' => '禁用'
        );
        $this->assign('radios', $radios);
        $this->assign('v_status', $data['status']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * sortinsert
     * 写入留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortinsert()
    {
        $m = new MessageSortModel();
        $ename = $this->_post('ename');
        $condition['ename'] = array('eq',$ename);
        if (empty($ename)) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        $_POST['status'] = $_POST['status']['0'];
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
     * sortupdate
     * 更新留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortupdate()
    {
        $m = new MessageSortModel();
        $id = $this->_post('id');
        $ename = $this->_post('ename');
        $condition['id'] = array('neq', $id);
        $condition['ename'] = array('eq',$ename);
        if (empty($ename)) {
            $this->dmsg('1', '请将信息输入完整！', false, true);
        }
        if ($m->field('id')->where($condition)->find()) {
            $this->dmsg('1', '您输入的名称' . $ename . '已经存在！', false, true);
        }
        $_POST['status'] = $_POST['status']['0'];
        $_POST['updatetime'] = time();
        $rs = $m->save($_POST);
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sortdelete
     * 删除留言分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortdelete()
    {
        $m = new MessageSortModel();
        $l = new MessageModel();
        $id = $this->_post('id');
        $condition['sort_id'] = array('eq', $id);
        if ($l->field('id')->where($condition)->find()) {
            $this->dmsg('1', '列表中含有该分类的信息，不能删除！', false, true);
        }
        $del = $m->where('id=' . $id)->delete();
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
        $m = new MessageModel();
        import('ORG.Util.Page'); // 导入分页类
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->count();
        $page = new Page($count, $pageRows);
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->join(' join ' . C('DB_PREFIX') . 'message m')
                        ->join(C('DB_PREFIX') . 'message_sort ms ON ms.id=m.id')
                        ->limit($firstRow . ',' . $pageRows)->order('m.id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
            $data[$k]['replytime'] = date('Y-m-d H:i:s', $v['replytime']);
            if ($v['status'] = 'true') {
                $data[$k]['status'] = '可用';
            } else {
                $data[$k]['status'] = '禁用';
            }
        }
        $array = array();
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

    /**
     * sortJson
     * 返回sortjson模型分类数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortJson()
    {
        $m = new MessageSortModel();
        $list = $m->select();
        $count = $m->count("id");
        $a = array();
        foreach ($list as $k => $v) {
            $a[$k] = $v;
        }
        $array = array();
        $array['total'] = $count;
        $array['rows'] = $a;
        echo json_encode($array);
    }

    /**
     * jsonTree
     * 头部导航返回树形json数据
     * @access add edit
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree()
    {
        Load('extend');
        $m = new MessageSortModel();
        $tree = $m->field(array('id','ename' => 'text'))->select();
        $tree = list_to_tree($tree, 'id', 'parent_id', 'children');
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
    }

}

?>