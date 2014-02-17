<?php

/**
 * UploadAction.class.php
 * 上传文件中心
 * @author 正侠客 <lookcms@gmail.com>
 * @copyright 2012- http://www.dingcms.com http://www.dogocms.com All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version dogocms 1.0 2012-11-5 11:23
 * @package  Controller
 * @todo 上传权限及各种安全过滤
 */
namespace Admin\Action;
use Think\Action;
class UploadAction extends BaseAction {

    /**
     * upload
     * 上传文件
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function uploadImg()
    {

        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $upload->savePath = './Public/Uploads/Images/'; // 设置附件上传目录
        $upload->autoSub = true;
        $upload->subType = 'date';
        $upload->dateFormat = 'Ymd';
        //$upload->allowTypes = array();//允许上传的文件类型
        //$dir = date('Ymd',time());
        //$upload->dateFormat = 'Y-m-d';
        $upload->saveRule = 'time';
        if (!$upload->upload()) {// 上传错误提示错误信息
            $msg = $upload->getErrorMsg();
            echo json_encode(array('error' => 1, 'message' => $msg));
            exit;
        } else {// 上传成功 获取上传文件信息
            $result_file = $upload->getUploadFileInfo();
            if (count($result_file) == 1) {
                $url = $result_file[0]['savepath'] . $result_file[0]['savename'];
                //$url = $result_file[0]['savepath'];
            }
            echo json_encode(array('error' => 0, 'url' => $url));
            //exit;
            //$info = $upload->getUploadFileInfo();
        }

        /*
         * echo '<pre>';
          echo count($result_file);
          echo '<br/>';
          print_r($result_file);
          exit;

         * //判断上传是否成功
          if(!$upload->upload()){
          $this->error($upload->getErrorMsg());
          }else{
          $info = $upload->getUploadFileInfo();   //获取图片的相关信息
          //dump($info); exit();    //可以输出看下$info类型
          }

          //保存表单数据 包括附件数据
          /*如果单个图片上传，把for循环去掉，$info["$i"]["savename"];改成$info[0]["savename"]; */
        /*
          for($i=0;$i<count($info);$i++){
          $File = D('File');
          $File->create();
          $File->filename = $info["$i"]["savename"];
          $File->add();
          }
          $this->success('Mysql success!');
          echo json_encode(array('error' => 1, 'message' => 'dddddddddddd'));
          exit;
          // 保存表单数据 包括附件数据
          $User = M('User'); // 实例化User对象
          $User->create(); // 创建数据对象
          $User->photo = $info[0][“savename”]; // 保存上传的照片根据需要自行组装
          $User->add(); // 写入用户数据到数据库
          $this->success(“数据保存成功！”);
          echo json_encode(array('error' => 1, 'message' => 'dddddddddddd'));
          exit;
         *
         */
    }

    /**
     * fileManagerJson
     * 罗列文件（文件浏览器）
     * @access public
     * @return array
     * @version dogocms 1.0
     */
    public function fileManagerJson()
    {
        echo '<script>alert("123");</script>';
        echo json_encode(array('error' => 1, 'message' => 'file1234567'));
        exit;
        $php_path = dirname(__FILE__) . '/';
        $php_url = dirname($_SERVER['PHP_SELF']) . '/';

//根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $root_path = $php_path . '../attached/';
//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $root_url = $php_url . '../attached/';
//图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

//目录名
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
            echo "Invalid Directory name.";
            exit;
        }
        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                mkdir($root_path);
            }
        }

//根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path = realpath($root_path) . '/' . $_GET['path'];
            $current_url = $root_url . $_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        echo realpath($root_path);
//排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

//不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit;
        }
//最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit;
        }
//目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit;
        }

//遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.')
                    continue;
                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }
        echo json_encode($file_list);
        echo json_encode(array('error' => 1, 'message' => 'file1234567'));
        exit;
    }

}

?>