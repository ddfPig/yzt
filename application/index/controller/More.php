<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/19 21:25
 */


namespace app\index\controller;


class More extends Home
{
    public function index()
    {
        return $this->fetch();
    }

    public function scans()
    {
        return $this->fetch();
    }
}