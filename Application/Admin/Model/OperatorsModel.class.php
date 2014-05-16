<?php

/**
 * OperatorsModel.class.php
 * 管理员信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-15 22:40
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class OperatorsModel extends Model {

    protected $tableName = 'operators';
    //_pk 表示主键字段名称 
    protected $fields = array(
        'id', 'username', 'password', 'creat_time', 'is_recycle', 'status','updatetime', '_pk' => 'id'
    );

    /*
      //* ThinkPHP的字段映射功能可以让你在表单中隐藏真正的数据表字段，而不用担心放弃自动创建表单对象的功能
      //* 字段映射还可以支持对主键的映射。
      protected $_map = array(
      'name' =>'username', // 把表单中name映射到数据表的username字段
      'mail'  =>'email', // 把表单中的mail映射到数据表的email字段
      );
     * 
     */
}

?>