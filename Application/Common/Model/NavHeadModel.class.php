<?php

/**
 * NavFootModel.class.php
 * 底部导航信息表模型
 * @author knight <qiuyuntech@foxmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-16 22:15
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class NavFootModel extends Model {

    protected $tableName = 'nav_head';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'parent_id', 'text', 'path', 'myorder', 'status', 'updatetime', 'url', '_pk' => 'id'
    );

}

?>