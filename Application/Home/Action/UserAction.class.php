<?php

//UserController负责外部交互响应，通过URL请求响应，例如 http://serverName/User/index

namespace Home\Action;

use Think\Action;

class UserAction extends BaseAction {

    public function _empty()
    {
        echo 'no empty';
    }

    public function index()
    {
        echo C('DB_NAME') . C('URL_MODEL');
        echo '<br/>';
        echo 'hello,world! hello' . time();
    }

    public function hello()
    {
        echo ACTION_NAME;
        echo '<br/>===============';
        echo 'hello,world! hello' . time();
    }

}
