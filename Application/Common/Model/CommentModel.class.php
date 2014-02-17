<?php

/**
 * CommentModel.class.php
 * 评论信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-18 11:20
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model {

    protected $tableName = 'comment';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'title_id', 'members_id', 'parent_id', 'addtime', 'ip','content','status',
        'updatetime','num_warn','floor','_pk' => 'id'
    );

}

?>