<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 20:29
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//更新日记管理
class Updatediary extends Home
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

        //新建跳转
        public function diaryadd(){

            return $this->fetch('updatediary_add');
        }

        //新建保存按钮
        public function adddiarybutton(){

            $time = time();
            $data = Request::instance()->param();
            $data['gxrjgl_customize_time'] = time($data['gxrjgl_customize_time']);
            $data['gxrjgl_create_name'] = $this->zstUser['aid'];
            $data['gxrjgl_create_time'] = $time;

            //验证类
            $rule = [
                'gxrjgl_title'  => 'require',
                'gxrjgl_contents'  => 'require',
                'gxrjgl_customize_time'  => 'require',
            ];
            $msg = [
                'gxrjgl_title.require' => '标题必须填写',
                'gxrjgl_contents.require' => '内容必须填写',
                'gxrjgl_customize_time.require' => '时间必须选择',
            ];
            $validate = new Validate($rule,$msg);
            $result   = $validate->check($data);
            if(!$result){
                return json_encode(['error'=>0,'msg'=>$validate->getError()]);
            }


            $result = Db::name('gxrjgl')->data($data)->insert();

            if ($result !== false){
                return json_encode(['error'=>1,'msg'=>'添加成功']);
            }else{
                return json_encode(['error'=>0,'msg'=>'添加失败']);
            }
        }
        //修改回显
        public function edit(){

            $id = Request::instance()->param('id');
            $data = Db::name('gxrjgl')->where('id',$id)->find();

            $this->assign([
                'id' => $id,
                'data'	=> $data,
            ]);

            return $this->fetch('updatediary_edit');
        }

        //修改按钮
        public function editdiarybutton(){

            $data = Request::instance()->param();
            $id = $data['id'];
            $data['gxrjgl_customize_time'] = strtotime($data['gxrjgl_customize_time']);
            $data['gxrjgl_create_time'] = time();
            $data['gxrjgl_create_name'] = "王大花";
            //验证类
            $rule = [
                'gxrjgl_title'  => 'require|unique:gxrjgl,gxrjgl_title',
                'gxrjgl_contents'  => 'require|unique:gxrjgl,gxrjgl_contents',
                'gxrjgl_customize_time'  => 'require|unique:gxrjgl,gxrjgl_customize_time',
            ];
            $msg = [
                'gxrjgl_title.require' => '标题必须填写',
                'gxrjgl_contents.require' => '内容必须填写',
                'gxrjgl_customize_time.require' => '时间必须选择',
            ];
            $validate = new Validate($rule,$msg);
            $result   = $validate->check($data);
            if(!$result){
                return json_encode(['error'=>0,'msg'=>$validate->getError()]);
            }

            $id = Db::name('gxrjgl')->where('id',$id)->update($data);

            if($id !== false){
                return json_encode(['error'=>1,'msg'=>'修改成功']);
            }else{
                return json_encode(['error'=>0,'msg'=>'修改成功']);
            }
        }

        //删除按钮
        public function deldiabutton(){

            $id = input("id/a");

            $result = Db::name('gxrjgl')->where('id','in',$id)->delete();

            if ($result){
                return json(array('error' => 1));
            }
        }
}