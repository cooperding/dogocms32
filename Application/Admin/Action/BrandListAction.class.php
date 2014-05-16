<?php

/**
 * BrandListAction.class.php
 * 品牌商家信息
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-09-04 15:00
 * @package  Controller
 * @todo
 */
namespace Admin\Action;
use Think\Action;
class BrandListAction extends BaseAction {

    /**
     * index
     * 品牌列表页
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
            '20' => ' 是 ',
            '10' => ' 否 '
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
        $m = D('BrandList');
        $id = I('get.id');
        $condition['id'] = array('eq', $id);
        $data = $m->where($condition)->find();
        $status = array(
            '20' => ' 是 ',
            '10' => ' 否 '
        );
        $this->assign('status', $status);
        $this->assign('data', $data);
        $this->assign('v_status', $data['status']);
        $this->display();
    }

    /**
     * sortinsert
     * 写入列表信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = D('BrandList');
        $data['name'] = I('post.name');
        if (empty($data['name'])) {
            $this->dmsg('1', '商家名称不能为空！', false, true);
        }
        $data['url'] = I('post.url');
        $data['brand_logo'] = I('post.brand_logo');
        $data['description'] = I('post.description');
        $data['myorder'] = I('post.myorder');
        
        $data['status'] = I('status')['0'];
        $data['updatetime'] = time();
        if ($m->create()) {
            $rs = $m->add($data);
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
    public function update()
    {
        $m = D('BrandList');
        $id = I('post.id');
        $data['name'] = I('post.name');
        $condition['id'] = array('eq', $id);
        if (empty($data['name'])) {
            $this->dmsg('1', '商家名称不能为空！', false, true);
        }
        $data['url'] = I('post.url');
        $data['brand_logo'] = I('post.brand_logo');
        $data['description'] = I('post.description');
        $data['myorder'] = I('post.myorder');
        
        $data['status'] = I('status')['0'];
        $data['updatetime'] = time();
        $rs = $m->where($condition)->save($data);
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
        $m = D('BrandList');
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
        $m = D('BrandList');
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        $title = $_REQUEST['keywords'];
        if ($title) {
            $condition['name|url'] = array('like', '%' . $title . '%');
        }
        $count = $m->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->where($condition)->limit($firstRow . ',' . $pageRows)->order('id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if ($v['status'] == '20') {
                    $data[$k]['status'] = '启用';
                } elseif ($v['status'] == '10') {
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

    /**
     * jsonTree
     * 分类json树结构数据
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function jsonTree()
    {
        $qiuyun = new \Org\Util\Qiuyun;
        $m = D('BrandList');
        $tree = $m->field('id,name as text')->select();
        $tree = array_merge(array(array('id' => 0, 'text' => L('sort_root_name'))), $tree);
        echo json_encode($tree);
    }

}

?>