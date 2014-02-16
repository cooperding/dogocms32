<?php

namespace Think\Template\TagLib;
use Think\Template\TagLib;
defined('THINK_PATH') or exit();
//自定义标签
class Dogocms extends TagLib {

    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        // 'test' => array("attr" => "attr1,attr2", level => 3),
        'nav' => array("attr" => "id,limit,type,order,name,key,mod", level => 3), //网站导航 type:top,son,all;name:head,foot
        'article' => array('attr' => 'id,typeid,type,tid,modeid,limit,flag,order,keywords,model_name', 'level' => 3), //文章内容
        'sort' => array("attr" => "id,limit,type,order,name,key,mod", level => 3), //栏目分类
        'message' => array("attr" => "attr1,attr2", level => 3), //咨询留言
        'comment' => array("attr" => "attr1,attr2", level => 3), //评论
        'list' => array("attr" => "attr1,attr2", level => 3), //列表页内容
        'pagelist' => array("attr" => "attr1,attr2", level => 3), //分页
        'ad' => array("attr" => "attr1,attr2", level => 3), //广告（包含幻灯）
        'page' => array("attr" => "attr1,attr2", level => 3), //广告（包含幻灯）
        'block' => array("attr" => "typeid,limit,order", level => 3), //碎片
        'member' => array("attr" => "attr1,attr2", level => 3), //会员信息(个人)
        'cfg' => array("attr" => "name", level => 3, 'close' => 0), //系统参数
        'links' => array("attr" => "typeid,limit,order", level => 3, 'close' => 1), //友情链接
    );

    //取得配置信息
    //之后存入缓存文件
    public function _cfg($attr, $content)
    {
        $tag = $this->parseXmlAttr($attr, 'cfg');
        $name = $tag['name'];
        $m = M('Setting');
        $condition['sys_name'] = $name;
        $data = $m->where($condition)->find();
        $parseStr = '';
        if ($data) {
            $parseStr = stripslashes($data['sys_value']);
        }
        return $parseStr;
    }

//  头部和底部导航
    public function _nav($attr, $content)
    {
        $tag = $this->parseXmlAttr($attr, 'nav');
        $name = $tag['name'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        $type = $tag['type'];
        $id = $tag['id'];
        $tag['where'] = '(`status` = \'y\')';
        $tag['name'] = ucfirst($tag['name']);
        $sql = "M('Nav{$tag['name']}')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = !empty($id) ? $id : 'nav'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php Load("extend"); ';
        $parsestr .= '$_result=list_to_tree(' . $sql . ',"id", "parent_id", "children"); if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    public function _article($attr, $content)
    {
        $tag = $this->parseXmlAttr($attr, 'article');
        $typeid = trim($tag['typeid']); //分类id
        $type = strtoupper($tag['type']); //分类类型type:all
        $tid = $tag['tid']; //指定文档id
        $id = $tag['id'];
        $modeid = trim($tag['modeid']); //模型id
        $model_name = trim($tag['model_name']); //模型名称
        $limit = $tag['limit']; //显示信息数 默认10
        $flag = $tag['flag']; //信息属性
        $order = $tag['order']; //信息排序
        $keywords = $tag['keywords']; //包含关键词
        if (!$modeid) {
            if ($typeid) {
                if ($type == 'ALL') {//分类类型存在时，分类id一定存在，此处根据type获取所有子类id
                    $ns = M('NewsSort');
                    $typeid_arr = array();
                    foreach (explode(',', $typeid) as $k => $vid) {
                        $path .= ' (path `like` \'%,' . $vid . ',%\') or';
                        $path .= ' (`id` = ' . $vid . ') or';
                    }
                    $path = rtrim($path, 'or ');
                    $rs = $ns->field('t.id')->where($path)->select();
                    foreach ($rs as $v) {
                        $sort_id .= $v['id'] . ',';
                    }
                    $sort_id = rtrim($sort_id, ', ');
                    $tag['where'] = ' (t.`sort_id` in(' . $sort_id . '))';
                } else {
                    $tag['where'] = ' (t.`sort_id` in(' . $typeid . '))';
                }
            }//if
        } else {
            $ns = M('NewsSort');
            if ($typeid) {
                if ($type == 'ALL') {//分类类型存在时，分类id一定存在，此处根据type获取所有子类id
                    $typeid_arr = array();
                    foreach (explode(',', $typeid) as $k => $vid) {
                        $path .= ' (`path` like \'%,' . $vid . ',%\') or';
                        $path .= ' (`id` = ' . $vid . ') or';
                    }
                    $path = rtrim($path, 'or ');
                    $path .= ' and (`model_id` in(' . $modeid . ')) ';
                    $rs = $ns->field('id')->where($path)->select();
                    foreach ($rs as $v) {
                        $sort_id .= $v['id'] . ',';
                    }
                    $sort_id = rtrim($sort_id, ', ');
                    $tag['where'] = ' (t.`sort_id` in(' . $sort_id . '))';
                } else {
                    $path .= ' (`model_id` in(' . $modeid . ')) ';
                    $rs = $ns->field('id')->where($path)->select();
                    foreach ($rs as $k => $v) {
                        $modeid_arr[] = $v['id'];
                    }
                    $he_arr = array_intersect($modeid_arr, explode(',', $typeid));
                    //此处应该是两个数组取交集
                    $tag['where'] = ' (t.`sort_id` in(' . implode(',', $he_arr) . '))';
                }
            } else {//查出所有sort_id再组装语句
                $path .= ' (`model_id` in(' . $modeid . ')) ';
                $rs = $ns->field('id')->where($path)->select();
                foreach ($rs as $v) {
                    $sort_id .= $v['id'] . ',';
                }
                $sort_id = rtrim($sort_id, ', ');
                $tag['where'] = ' (t.`sort_id` in(' . $typeid . '))';
            }//if
        }
        if ($tid) {
            if ($tag['where']) {
                $tag['where'] .= ' and (t.`id` in(' . $tid . ')) ';
            } else {
                $tag['where'] = ' (t.`id` in(' . $tid . ')) ';
            }
        }//if
        if ($flag) {
            foreach (explode(',', $flag) as $k => $v) {
                $flag_like .= ' (t.`flag` like \'%' . $v . '%\') or ';
            }
            $flag_like = rtrim($flag_like, 'or ');
            if ($tag['where']) {
                $tag['where'] .= ' and (' . $flag_like . ') ';
            } else {
                $tag['where'] = ' (' . $flag_like . ') ';
            }
        }//if
        if ($keywords) {
            if ($tag['where']) {
                $tag['where'] .= ' and (t.`keywords` like \'%' . $keywords . '%\') ';
            } else {
                $tag['where'] = ' (t.`keywords` like \'%' . $keywords . '%\') ';
            }
        }//if
        $tag['field'] = ' \'t.*,ns.text as sortname\' ';
        $table = '\'' . C('DB_PREFIX') . 'title t\'';
        $join = 'join(\' ' . C('DB_PREFIX') . 'news_sort ns on ns.id=t.sort_id \')->';
        if ($model_name) {
            $tag['field'] = ' \'t.*,ns.text as sortname,ms.*\' ';
            $join .= 'join(\' ' . C('DB_PREFIX') . $model_name . ' ms on ms.title_id=t.id \')->';
        }
        if ($tag['where']) {
            $tag['where'] .= ' and (t.status=\'y\') and (t.is_recycle=\'n\') ';
        } else {
            $tag['where'] = ' (t.status=\'y\') and (t.is_recycle=\'n\') ';
        }
        $result = !empty($id) ? $id : 'article'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        $sql = "M('Title')->";
        $sql .= "table({$table})->";
        $sql .= $join;
        $sql .= ($tag['field']) ? "field({$tag['field']})->" : '';
        $sql .= ($order) ? "order(\"{$order}\")->" : 'order(\'t.id desc\')->';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= "select()";
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    //文档分类
    public function _sort($attr, $content)
    {
        $tag = $this->parseXmlAttr($attr, 'sort');
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        $type = $tag['type'];
        $id = $tag['id'];
        $tag['name'] = ucfirst($tag['name']);
        $sql = "M('NewsSort')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        //$sql .= ($tag['type']) ? "order({$tag['type']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = !empty($id) ? $id : 'sort'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php Load("extend"); ';
        $parsestr .= '$_result=list_to_tree(' . $sql . ',"id", "parent_id", "children"); if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

//  头部和底部导航
    public function _links($attr, $content)
    {//typeid,limit,type,order
        $tag = $this->parseXmlAttr($attr, 'links');
        $typeid = $tag['typeid'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        $tag['where'] = ' (`status`=\'y\') '; //限制显示条件
        if ($typeid) {
            $tag['where'] = ' and (`sort_id` in(' . $typeid . ')) ';
        }
        $sql = "M('Links')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = !empty($id) ? $id : 'links'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php Load("extend"); ';
        $parsestr .= '$_result=list_to_tree(' . $sql . ',"id", "parent_id", "children"); if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

    //  block碎片标签 typeid,limit,order
    public function _block($attr, $content)
    {
        $tag = $this->parseXmlAttr($attr, 'block');
        $typeid = $tag['typeid'];
        $limit = $tag['limit'];
        $order = $tag['order']; //字符串加引号
        if (empty($limit)) {
            $tag['limit'] = '0,4';
        }
        if (empty($order)) {
            $order = 'myorder asc';
        }
        $tag['where'] = ' (`status`=\'20\') ';
        if ($typeid) {
            $tag['where'] .= ' and (`sort_id` =' . $typeid . ') ';
        }
        $sql = "M('BlockList')->";
        $sql .= ($order) ? "order(\"{$order}\")->" : '';
        $sql .= ($tag['limit']) ? "limit({$tag['limit']})->" : '';
        $sql .= ($tag['where']) ? "where(\"{$tag['where']}\")->" : '';   //被重新处理过了
        $sql .= "select()";
        $result = 'block'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

}

?>