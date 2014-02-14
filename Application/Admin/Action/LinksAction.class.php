<?php

/**
 * LinksAction.class.php
 * 友情链接
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:08
 * @package  Controller
 * @todo 上传图片的操作
 */
class LinksAction extends BaseAction {

    /**
     * index
     * 友情链接列表页
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
        $radios = array(
            'y' => '可用',
            'n' => '禁用'
        );
        $this->assign('radios', $radios);
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
        $m = new LinksModel();
        $id = $this->_get('id');
        $condition['id'] = array('eq', $id);
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
     * insert
     * 插入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = new LinksModel();
        $webname = $this->_post('webname');
        $sort_id = $this->_post('sort_id');
        if (empty($webname)) {
            $this->dmsg('1', '网站名不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $_POST['addtime'] = time();
        $_POST['updatetime'] = time();
        $_POST['status'] = $_POST['status']['0'];
        if ($m->create($_POST)) {
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
        $m = new LinksModel();
        $id = $this->_post('id');
        $webname = $this->_post('webname');
        $sort_id = $this->_post('sort_id');
        $data['id'] = array('eq', $id);
        if (empty($webname)) {
            $this->dmsg('1', '网站名不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择所属分类！', false, true);
        }
        $_POST['updatetime'] = time();
        $_POST['status'] = $_POST['status']['0'];
        $rs = $m->where($data)->save($_POST);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }

    /**
     * delete
     * 删除友情链接
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = new LinksModel();
        $id = $this->_post('id');
        $condition['id'] = array('eq', $id);
        $del = $m->where($condition)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * sort
     * 友情链接分类
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
     * 添加友情链接分类
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
     * 编辑友情链接分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortedit()
    {
        $m = new LinksSortModel();
        $id = $this->_get('id');
        $condition['id'] = array('eq', $id);
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
     * 写入友情链接分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortinsert()
    {
        $m = new LinksSortModel();
        $ename = $this->_post('ename');
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
     * 更新友情链接分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortupdate()
    {
        $m = new LinksSortModel();
        $id = $this->_post('id');
        $ename = $this->_post('ename');
        $condition['ename'] = array('eq', $ename);
        $condition['id'] = array('neq', $id);
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
     * 删除友情链接分类
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function sortdelete()
    {
        $m = new LinksSortModel();
        $l = new LinksModel();
        $id = $this->_post('id');
        $condition['sort_id'] = array('eq', $id);
        if ($l->field('id')->where($condition)->find()) {
            $this->dmsg('1', '列表中含有该分类的信息，不能删除！', false, true);
        }
        $condition_id['id'] = array('eq', $id);
        $del = $m->where($condition_id)->delete();
        if ($del == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
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
        $m = new LinksSortModel();
        $list = $m->select();
        $count = $m->count("id");
        $a = array();
        foreach ($list as $k => $v) {
            $a[$k] = $v;
            if ($v['status'] == 'y') {
                $a[$k]['status'] = '启用';
            } else {
                $a[$k]['status'] = '禁用';
            }
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
        $m = new LinksSortModel();
        $tree = $m->field(array('id', 'ename' => 'text'))->select();
        $tree = list_to_tree($tree, 'id', 'parent_id', 'children');
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
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
        $m = new LinksModel();
        import('ORG.Util.Page'); // 导入分页类
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $k = $_REQUEST['keywords'];
        if ($k) {
            $condition['webname|weburl'] = array('like', '%' . $k . '%');
        }
        $count = $m->where($condition)->count();
        $page = new Page($count, $pageRows);
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->limit($firstRow . ',' . $pageRows)->where($condition)->order('id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if ($v['status'] == 'y') {
                    $data[$k]['status'] = '启用';
                } else {
                    $data[$k]['status'] = '禁用';
                }
            }
        } else {
            $count = 0;
            $data = array();
        }
        $array = array();
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

}

?>