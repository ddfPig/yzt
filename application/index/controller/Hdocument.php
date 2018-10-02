<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 23:49
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Hdocument extends Home
{
    public function index()
    {
        return $this->fetch('index');

    }

    //页面初始化
    public function ini(){

//        //诊所编号
//        $clinic_id = 1;

        //搜索关键词
        $keyword = Request::instance()->param('keyword');


        $page = Request::instance()->param('page');
        $rows = Request::instance()->param('rows');

        $start = ($page-1)*$rows;

        if ($keyword){
            $data = Db::name('bzwdgl')
                ->field("bzwdgl_state,bzwdgl_title,bzwdgl_contents,b.name,a.id")
                ->alias('a')
                ->join('zst_doctype b','a.bzwdgl_state = b.id','LEFT')
                ->where('bzwdgl_title','like',"%$keyword%")->order('id','desc')->limit($start,$rows)->select();
            $total = Db::name('bzwdgl')->where('bzwdgl_title','like',"%$keyword%")->count();
        }else{
            $data = Db::table('zst_bzwdgl')
                 ->field("bzwdgl_state,bzwdgl_title,bzwdgl_contents,b.name,a.id")
                ->alias('a')
                ->join('zst_doctype b','a.bzwdgl_state = b.id','LEFT')
                ->order('id','desc')
                ->limit($start,$rows)
                ->select();
            $total = Db::name('bzwdgl')->count();
        }

        if($data){
            return json(array('total'=>$total,'rows'=>$data));
        }

    }

}