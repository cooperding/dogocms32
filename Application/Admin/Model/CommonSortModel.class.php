<?php

/**
 * CommonSortModel.class.php
 * 公共分类相关信息
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-12-3 20:22
 * @package  Controller
 * @todo
 */
namespace Admin\Model;
use Think\Model;
class CommonSortModel extends Model {

    /**
     * updatePath
     * 更新路径
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    function updatePath($sort_id, $sort_path, $tbname)
    {
        $m = M($tbname);
        $condition['path'] = array('like', '%,' . $sort_id . ',%');
        $condition['parent_id'] = array('eq', intval($sort_id));
        $condition['_logic'] = 'OR';
        $result = $m->field('id,path')->where($condition)->select();
        foreach ($result as $k => $v) {
            $data['path'] = $sort_path . substr($v['path'], strpos($v['path'], $sort_id . ','), strlen($v['path']));
            $m->where('id=' . intval($v['id']))->save($data);
        }
    }

    /**
     * updateRolePath
     * 更新角色节点的路径
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    function updateRolePath($sort_id, $sort_path, $tbname)
    {
        $m = M($tbname);
        $condition['path'] = array('like', '%,' . $sort_id . ',%');
        $condition['pid'] = array('eq', intval($sort_id));
        $condition['_logic'] = 'OR';
        $result = $m->field('id,path')->where($condition)->select();
        foreach ($result as $k => $v) {
            $data['path'] = $sort_path . substr($v['path'], strpos($v['path'], $sort_id . ','), strlen($v['path']));
            $m->where('id=' . intval($v['id']))->save($data);
        }
    }

}
