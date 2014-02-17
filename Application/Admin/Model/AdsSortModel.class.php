<?php

/**
 * AdsSortModel.class.php
 * 广告分类信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-17 19:40
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class AdsSortModel extends Model {

    protected $tableName = 'ads_sort';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'ename', 'status','updatetime','_pk' => 'id'
    );

}

?>