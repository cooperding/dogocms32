<?php

/**
 * BlockListModel.class.php
 * 碎片列表信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-09-09 23:49
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class BlockListModel extends Model {

    protected $tableName = 'block_list';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'sort_id', 'title', 'description','field1','field2','field3','field4','field5','addtime','updatetime','title_img','status','url','myorder','_pk' => 'id'
    );

}

?>