<?php

namespace Home\Action;
use Think\Action;
class IndexAction extends BaseAction {

    public function index()
    {

        echo C('DB_NAME') . C('URL_MODEL');
        echo '<br/>';
        echo 'hello,world!' . time();
        $this->theme('default')->display(':menu');
    }

    public function hello()
    {
        echo ACTION_NAME;
        echo '<br/>';
        echo 'hello,world! hello' . time();
        $this->theme('default')->display(':hello');
    }

}
