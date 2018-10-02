<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 22:55
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//帮助文档管理
class Helpdocument extends Home
{
    public function index()
    {
        return $this->fetch('index');

    }

    //页面初始化
    public function ini(){

        $page = Request::instance()->param('page');
        $rows = Request::instance()->param('rows');
        $start = ($page-1)*$rows;

         $data = Db::name('bzwdgl')     
                ->field("bzwdgl_state,bzwdgl_title,FROM_UNIXTIME( bzwdgl_customize_time, '%Y-%m-%d' ) bzwdgl_customize_time,bzwdgl_contents,b.name,a.id")
                ->alias('a')
                ->join('zst_doctype b','a.bzwdgl_state = b.id','LEFT')
                ->order('bzwdgl_customize_time','desc')
                ->limit($start,$rows)->select();

        $total = Db::name('bzwdgl')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增回显
    public function documentadd(){

        $result = Db::table('zst_doctype')->find();
        if ($result == NULL){
            $data['name'] = "说明书";
            $data_a['name'] = "操作手册";
            Db::name('doctype')->data($data)->insert($data);
            Db::name('doctype')->data($data_a)->insert($data_a);
        }

        return $this->fetch('helpdocument_add');
    }

    //新增按钮
    public function adddobutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['bzwdgl_create_name'] = $this->zstUser['aid'];
        $data['bzwdgl_create_time'] = $time;
//        if ($data['bzwdgl_state'] == "请选择"){
//            return json_encode(['error'=>0,'msg'=>'请选择类型']);
//        }
        foreach($data as $k=>$v){
            if($k == "type"){
                unset($data[$k]);
            }
        }
        //验证类
        $rule = [
            'bzwdgl_title'  => 'require',
            'bzwdgl_contents'  => 'require',
            'bzwdgl_customize_time'  => 'require',
        ];
        $msg = [
            'bzwdgl_title.require' => '标题必须填写',
            'bzwdgl_contents.require' => '内容必须填写',
            'bzwdgl_customize_time.require' => '自定义时间必须填写',

        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $data['bzwdgl_customize_time'] = time($data['bzwdgl_customize_time']);


        $result = Db::name('bzwdgl')->data($data)->insert();

        if ($result == 1){
            return json_encode(['error'=>1,'msg'=>'保存成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'保存失败']);
        }
    }

    //回显下拉框
    public function type(){

        $id = Db::name('doctype')->field("name,id")->select();

        if ($id){

            return json($id);
        }
    }

    //修改回显
    public function edit(){

        $id = Request::instance()->param('id');

        $data = Db::name('bzwdgl')
            ->field("bzwdgl_state,bzwdgl_title,bzwdgl_customize_time,bzwdgl_contents,b.name")
            ->alias('a')
            ->join('zst_doctype b','a.bzwdgl_state = b.id','LEFT')
            ->where('a.id',$id)
            ->find();
        $this->assign([
            'id' => $id,
            'data'  => $data,
        ]);

        return $this->fetch('helpdocument_edit');
    }

    //修改按钮
    public function editdobutton(){

        $data = Request::instance()->param();
        $id = $data['id'];
        
        foreach($data as $k=>$v){
            if($k == "type"){
                unset($data[$k]);
            }
        }
        $data['bzwdgl_customize_time'] = strtotime($data['bzwdgl_customize_time']);
        $data['bzwdgl_create_time'] = time();
        $data['bzwdgl_create_name'] = $this->zstUser['aid'];
        //验证类
        $rule = [
            'bzwdgl_title'  => 'require',
            'bzwdgl_contents'  => 'require',
            'bzwdgl_customize_time'  => 'require',
        ];
        $msg = [
            'bzwdgl_title.require' => '标题必须填写',
            'bzwdgl_contents.require' => '内容必须填写',
            'bzwdgl_customize_time.require' => '自定义时间必须填写',

        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $id = Db::name('bzwdgl')->where('id',$id)->update($data);

        if($id !== false){
            return json_encode(['error'=>1,'msg'=>'修改成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'修改失败']);
        }
    }

    //删除按钮
    public function deldocbutton(){

        $id = input("id/a");

        $result = Db::name('bzwdgl')->where('id','in',$id)->delete();

        if ($result){
            return json(array('error' => 1));
        }
    }
}