<?php

/**
 * GoodsListAction.class.php
 * 商品列表信息内容
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2013-09-02 19:23
 * @package  Controller
 * @todo 信息各项操作
 */
namespace Admin\Action;
use Think\Action;
class GoodsListAction extends BaseAction {

    /**
     * index
     * 信息列表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function index()
    {
        $this->display();
    }

    /**
     * newslist
     * 信息列表
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function newslist()
    {
        $id = I('get.id');
        $this->assign('id', $id);
        $this->display('newslist');
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
        $id = I('get.id');
        $status = array(
            '20' => ' 审核 ',
            '10' => ' 未审核 ',
            '11' => ' 未通过审核 '
        );
        $is_sale = array(
            '20' => ' 是 ',
            '10' => ' 否 '
        );
        $is_recycle = array(
            '20' => ' 是 ',
            '10' => ' 否 '
        );
        $this->assign('id', $id);
        $this->assign('status', $status);
        $this->assign('is_sale', $is_sale);
        $this->assign('is_recycle', $is_recycle);
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
        $m = D('GoodsList');
        $c = D('GoodsContent');
        $as = D('AttributeSort');
        $id = I('get.id');
        $condition_id['gl.id'] = array('eq', $id);
        $data = $m->field('gl.*,c.content')->Table(C('DB_PREFIX') . 'goods_list gl')
                        ->join(C('DB_PREFIX') . 'goods_content c ON c.goods_id=gl.id')
                        ->where($condition_id)->find();
        //获取分类id，然后取得属性分类ID，最后获取所有属性列表
        $condition_sort['gs.id'] = array('eq', $data['sort_id']);
        $condition_sort['ga.goods_id'] = array('eq', $id);
        $data_model = $as->Table(C('DB_PREFIX') . 'attribute_list al')
                        //->Table(C('DB_PREFIX') . 'goods_sort gs')
                        ->join(C('DB_PREFIX') . 'goods_attribute ga ON ga.attribute_id=al.id')
                        ->join(C('DB_PREFIX') . 'goods_sort gs ON al.sort_id=gs.model_id')
                        ->field('al.*,ga.values,ga.price,ga.goods_id')
                        ->where($condition_sort)->select();
        if ($data_model) {
            foreach ($data_model as $k => $v) {
                if ($v['attr_input_type'] == '1') {
                    $data_model[$k]['attr_values'] = str_replace("\r\n", ',', $v['attr_values']);
                }
            }
        }
        $status = array(
            '20' => ' 审核 ',
            '10' => ' 未审核 ',
            '11' => ' 未通过审核 '
        );
        $is_sale = array(
            '20' => ' 是 ',
            '10' => ' 否 '
        );
        $is_recycle = array(
            '20' => ' 是 ',
            '10' => ' 否 '
        );
        $this->assign('id', $id);
        $this->assign('status', $status);
        $this->assign('is_sale', $is_sale);
        $this->assign('is_recycle', $is_recycle);
        $this->assign('v_status', $data['v_status']);
        $this->assign('v_is_sale', $data['v_is_sale']);
        $this->assign('v_is_recycle', $data['v_is_recycle']);
        $this->assign('data', $data);
        $this->assign('data_model', $data_model);
        $this->display();
    }

    /**
     * insert
     * 写入信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function insert()
    {
        $m = D('GoodsList');
        $c = D('GoodsContent');
        $a = D('GoodsAttribute');
        $s = D('GoodsSort');
        $title = I('post.title');
        $sort_id = I('post.sort_id');
        if (empty($title)) {
            $this->dmsg('1', '文章标题不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择文档分类！', false, true);
        }
        $condition_sort['id'] = array('eq', $sort_id);
        $data_sort = $s->field('model_id')->where($condition_sort)->find();
        $_POST['addtime'] = time();
        $_POST['updatetime'] = time();
        $_POST['uid'] = session('LOGIN_UID');
        $_POST['status'] = $_POST['status']['0'];
        $_POST['is_sale'] = $_POST['is_sale']['0'];
        $_POST['is_recycle'] = $_POST['is_recycle']['0'];
        $attr_id_list = $_POST['attr_id_list'];
        $attr_value_list = $_POST['attr_value_list'];
        $attr_price_list = $_POST['attr_price_list'];
        if ($m->create($_POST)) {
            $rs = $m->add();
            $goods_id = $m->getLastInsID();
            if ($rs == true) {
                $content['goods_id'] = $goods_id;
                $content['content'] = $_POST['content'];
                $rsc = $c->data($content)->add();
                foreach ($attr_id_list as $k => $v) {
                    $data_attr['attribute_id'] = $v;
                    $data_attr['values'] = $attr_value_list[$k];
                    $data_attr['price'] = $attr_price_list[$k];
                    $data_attr['goods_id'] = $goods_id;
                    $data_attr['attr_sort_id'] = $data_sort['model_id'];
                    $a->data($data_attr)->add();
                }

                if ($rsc == true) {
                    $this->dmsg('2', ' 操作成功！', true);
                } else {
                    $this->dmsg('1', '操作失败！', false, true);
                }
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
        $m = D('GoodsList');
        $c = D('GoodsContent');
        $a = D('GoodsAttribute');
        $s = D('GoodsSort');
        $title = I('post.title');
        $sort_id = I('post.sort_id');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $condition_other['goods_id'] = array('eq', $id);
        if (empty($title)) {
            $this->dmsg('1', '文章标题不能为空！', false, true);
        }
        if ($sort_id == 0) {
            $this->dmsg('1', '请选择文档分类！', false, true);
        }
        $_POST['updatetime'] = time();
        $_POST['uid'] = session('LOGIN_UID');
        $_POST['status'] = $_POST['status']['0'];
        $_POST['is_sale'] = $_POST['is_sale']['0'];
        $_POST['is_recycle'] = $_POST['is_recycle']['0'];
        $value = $_POST['filed'];
        $content['content'] = $_POST['content'];
        $attr_id_list = $_POST['attr_id_list'];
        $attr_value_list = $_POST['attr_value_list'];
        $attr_price_list = $_POST['attr_price_list'];

        if ($attr_id_list) {
            $condition_attr['goods_id'] = array('eq', $id);
            $del = $a->where($condition)->delete();

            $condition_sort['id'] = array('eq', $sort_id);
            $data_sort = $s->field('model_id')->where($condition_sort)->find();
            foreach ($attr_id_list as $k => $v) {
                $data_attr['attribute_id'] = $v;
                $data_attr['values'] = $attr_value_list[$k];
                $data_attr['price'] = $attr_price_list[$k];
                $data_attr['goods_id'] = $id;
                $data_attr['attr_sort_id'] = $data_sort['model_id'];
                $rsa = $a->data($data_attr)->add();
            }
        }

        $rs = $m->where($condition)->save($_POST);
        $rsc = $c->where($condition_other)->save($content);
        //$sql = $m->getLastSql();
        //$this->dmsg('1', $sql, false, true);
        if ($rs == true || $rsc == true || $rsa == true || $del == true) {
            $this->dmsg('2', '更新成功！', true);
        } else {
            $this->dmsg('1', '更新失败,或者未有更新！', false, true);
        }
    }

    /**
     * delete
     * 删除文档到回收站
     * @access public
     * @return boolean
     * @version dogocms 1.0
     */
    public function delete()
    {
        $m = D('GoodsList');
        $id = I('post.id');
        $data['id'] = array('in', $id);
        if (empty($data['id'])) {
            $this->dmsg('1', '未有id值，操作失败！', false, true);
        }
        $rs = $m->where($data)->setField('is_recycle', '20');
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * gallery
     * 扩展图片集信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function gallery()
    {
        $m = D('GoodsGallery');
        $id = I('get.id');
        $condition['goods_id'] = array('eq', $id);
        $data = $m->where($condition)->select();
        $this->assign('data', $data);
        $this->assign('id', $id);
        $this->display();
    }

    /**
     * galleryUpdate
     * 扩展图片集更新信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function galleryUpdate()
    {
        $m = D('GoodsGallery');
        $goods_id = I('post.id');
        $gallery_id = I('post.gallery_id');
        $gallery_thumb = I('post.gallery_thumb');
        $gallery_title = I('post.gallery_title');
        //$gallery_thumb = array_filter($gallery_thumb);
        foreach ($gallery_thumb as $k => $v) {
            if (!empty($v)) {
                $data['goods_id'] = $goods_id;
                $data['title'] = $gallery_title[$k];
                $id = $gallery_id[$k];
                $data['img_url'] = $v;
                if ($id) {//存在时更新
                    $condition['id'] = array('eq', $id);
                    $rs = $m->where($condition)->save($data);
                } else {//不存在时写入
                    $rs = $m->data($data)->add();
                }
            }//if
        }
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败,或者未有更改！', false, true);
        }
    }

    /**
     * galleryRemove
     * 扩展图片集删除信息
     * @access public
     * @return array
     * @version dogocms 1.0
     * @todo 删除图片时同时将真是图片删除
     */
    public function galleryRemove()
    {
        $m = D('GoodsGallery');
        $id = I('post.id');
        $condition['id'] = array('eq', $id);
        $rs = $m->where($condition)->delete();
        if ($rs == true) {
            echo json_encode(array('status' => '2', 'msg' => 'ok'));
        } else {
            echo json_encode(array('status' => '1', 'msg' => '操作失败'));
        }//if
    }

    /**
     * tempmodel
     * 扩展属性信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function tempmodel()
    {
        $m = D('AttributeList');
        $id = I('post.id');
        $condition_sort['gs.id'] = array('eq', $id);
        $data = $m->field('al.*')->Table(C('DB_PREFIX') . 'goods_sort gs')
                        ->join(C('DB_PREFIX') . 'attribute_list al on al.sort_id=gs.model_id')
                        ->where($condition_sort)->order('al.id asc')->select();
        foreach ($data as $k => $v) {
            if ($v['attr_input_type'] == '1') {
                $data[$k]['attr_values'] = str_replace("\r\n", ',', $v['attr_values']);
            }
        }
        $this->assign('id', time());
        $this->assign('data_model', $data);
        $this->display();
    }

    /**
     * recycle
     * 回收站信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function recycle()
    {
        $this->display();
    }

    /**
     * recycleRevert
     * 回收站还原信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function recycleRevert()
    {
        $m = D('GoodsList');
        $id = I('post.id');
        $data['id'] = array('in', $id);
        if (empty($data['id'])) {
            $this->dmsg('1', '未有id值，操作失败！', false, true);
        }
        $rs = $m->where($data)->setField('is_recycle', '10');
        if ($rs == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * deleteRec
     * 从回收站彻底删除信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function deleteRec()
    {
        $m = D('GoodsList');
        $c = D('GoodsContent');
        $a = D('GoodsAttribute');
        $id = I('post.id');
        $condition['id'] = array('in', $id);
        $condition_other['goods_id'] = array('in', $id);

        $rsl = $m->where($condition)->delete();
        $rsc = $c->where($condition_other)->delete();
        $rsa = $a->where($condition_other)->delete();
        if ($rsl == true || $rsc == true || $rsa == true) {
            $this->dmsg('2', '操作成功！', true);
        } else {
            $this->dmsg('1', '操作失败！', false, true);
        }//if
    }

    /**
     * outStock
     * 缺货商品
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function outStock()
    {
        $this->display('outstock');
    }

    /**
     * listJsonId
     * 取得field信息
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function listJsonId()
    {
        $m = D('GoodsList');
        $s = D('GoodsSort');
        $id = I('get.id');
        if ($id != 0) {//id为0时调用全部文档
            $condition_sort['id'] = $id;
            $condition_sort['path'] = array('like', '%,' . $id . ',%');
            $condition_sort['_logic'] = 'OR';
            $data_sort = $s->field('id')->where($condition_sort)->select();
            $sort_id = '';
            foreach ($data_sort as $v) {
                $sort_id .= $v['id'] . ',';
            }
            $sort_id = rtrim($sort_id, ',');
            $condition['gl.sort_id'] = array('in', $sort_id);
        }
        $title = $_REQUEST['keywords'];
        if ($title) {
            $condition['gl.title'] = array('like', '%' . $title . '%');
        }
        $pageNumber = intval($_REQUEST['page']);
        $pageRows = intval($_REQUEST['rows']);
        $pageNumber = (($pageNumber == null || $pageNumber == 0) ? 1 : $pageNumber);
        $pageRows = (($pageRows == FALSE) ? 10 : $pageRows);
        if ($_GET['is_outstock'] == '0') {
            $condition['gl.stock'] = array('eq', '0');
        }
        $condition['gl.is_recycle'] = isset($_GET['is_recycle']) ? '20' : '10';
        $count = $m->table(C('DB_PREFIX') . 'goods_list gl')->where($condition)->count();
        new \Think\Page($count, $pageRows); // 导入分页类
        $firstRow = ($pageNumber - 1) * $pageRows;
        $data = $m->table(C('DB_PREFIX') . 'goods_sort gs')
                        ->join(C('DB_PREFIX') . 'goods_list gl on gs.id=gl.sort_id')
                        ->field('gl.id,gl.title,gl.addtime,gl.views,gl.addtime,gl.status,gl.stock,gs.text')
                        ->where($condition)->limit($firstRow . ',' . $pageRows)->order('gl.id desc')->select();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                if ($v['status'] == '20') {
                    $data[$k]['status'] = '已审核';
                } elseif ($v['status'] == '10') {
                    $data[$k]['status'] = '未审核';
                } elseif ($v['status'] == '11') {
                    $data[$k]['status'] = '<a href="javascript:void(0)" title="驳回" style="color:#F74343;">驳回审核</a>';
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

}
