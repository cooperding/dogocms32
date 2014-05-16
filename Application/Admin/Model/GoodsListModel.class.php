<?php

/**
 * GoodsListModel.class.php
 * 商品列表信息表模型
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-08-31 22:10
 * @package  Controller
 * @todo 字段验证
 */
namespace Admin\Model;
use Think\Model;
class GoodsListModel extends Model {

    protected $tableName = 'goods_list';
    //_pk 表示主键字段名称 _autoinc 表示主键是否自动增长类型
    protected $fields = array(
        'id', 'title', 'subtitle', 'keywords', 'description','thumb','number','stock','stockalert','is_sale',
        'market_price','shop_price','addtime','selltime','soldouttime','updatetime','status','uid','brand_id','sort_id','views','is_recycle','_pk' => 'id'
    );

}

?>