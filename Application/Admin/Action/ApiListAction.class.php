<?php

/**
 * ApiListAction.class.php
 * apilist信息管理
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-09-04 15:00
 * @package  Controller
 * @todo
 */
class ApiListAction extends BaseAction {

    /**
     * index
     * api列表页
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index() {
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
        $m = new ApiListModel();
        $id = $this->_get('id');
        $condition['al.id'] = array('eq',$id);
        $data = $m->table(C('DB_PREFIX').'api_list al')
                ->join(C('DB_PREFIX').'members m on al.members_id=m.id')
                ->field('al.*,m.username,m.email')
                ->where($condition)->find();
        $status = array(
            '20' => ' 启用 ',
            '10' => ' 禁用 '
        );
        $this->assign('status', $status);
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
        $m = new ApiListModel();
        $id = $this->_post('id');
        $name = $this->_post('name');
        $condition['id'] = array('eq', $id);
        $_POST['status'] = $_POST['status']['0'];
        $_POST['updatetime'] = time();
        $rs = $m->where($condition)->save($_POST);
        if ($rs == true) {
            $this->dmsg('2', ' 操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }
    }
    /**
     * Flash
     * Flash删除
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = new ApiListModel();
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
     * jsonList
     * 取得列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonList()
    {
        $m = new ApiListModel();
        import('ORG.Util.Page'); // 导入分页类
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $count = $m->count();
        $page = new Page($count, $pageRows);
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->table(C('DB_PREFIX').'api_list al')
                ->join(C('DB_PREFIX').'members m on al.members_id=m.id')
                ->field('al.*,m.username,m.email')
                ->limit($firstRow . ',' . $pageRows)->order('al.id desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
            if($v['status']=='20'){
                $data[$k]['status'] = '启用';
            }elseif($v['status']=='10'){
                $data[$k]['status'] = '禁用';
            }
        }
        $array = array();
        $array['total'] = $count;
        $array['rows'] = $data;
        echo json_encode($array);
    }

}

?>