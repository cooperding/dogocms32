<?php

/**
 * SettingModel.class.php
 * 站点配置信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-16 22:15
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class SettingModel extends Model {

    protected $tableName = 'setting';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'sys_name', 'sys_value', 'sys_info', 'sys_gid', 'sys_type', 'updatetime', 'myorder', '_pk' => 'id'
    );

}

?>