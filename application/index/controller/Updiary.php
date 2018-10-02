<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 22:41
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

//更新日记
class Updiary extends Home
{
    public function index(){

        return $this->fetch('index');
    }

    public function ini(){

        $page = Request::instance()->param('page');
        $rows = Request::instance()->param('rows');
        $start = ($page-1)*$rows;

        $data = Db::table('zst_gxrjgl')->field("FROM_UNIXTIME( gxrjgl_customize_time, '%Y-%m-%d' )  gxrjgl_customize_time,gxrjgl_title,gxrjgl_contents,id")->order('id','desc')->limit($start,$rows)->select();
        $total = Db::name('gxrjgl')->count();

        //返回json数据
        return json(array('total'=>$total,'rows'=>$data));
    }
}