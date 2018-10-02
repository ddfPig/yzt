<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 10:18
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//单位类型
class Unit extends Home
{
    public function index()
    {

        return $this->fetch('index');

    }

    //页面初始化
    public function ini(Request $request){

        $page = $request->param('page');
        $rows = $request->param('rows');
        $start = ($page-1)*$rows;
        //->where('clinic_id',$this->zstUser['shop_id'])
        $data = Db::table('zst_dwgl')->field("(case when dwgl_state = '1' then '已启用' when dwgl_state = '0' then '已停用'  end) dwgl_state,dwgl_name,sort,id")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();
        $total = Db::name('dwgl')->count();

        //返回json数据
        return json(array('total'=>$total,'rows'=>$data));

    }

    //新增跳转
    public function unitadd(){

        return $this->fetch('unit_add');
    }

    //保存按钮
    public function uaddbutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['dwgl_create_time'] = $time;
        $data['dwgl_create_name'] = $this->zstUser['user_name'];
        $data['dwgl_last_time'] = $time;
        $data['dwgl_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['dwgl_state'] = 1;

        //验证类
        $rule = [
            'dwgl_name'  => 'require|unique:dwgl,clinic_id^dwgl_name',
        ];
        $msg = [
            'dwgl_name.require' => '名称必须填写',
            'dwgl_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
           return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('dwgl')->insert($data,true);

        if ($list == 1){
            return json_encode(['error'=>1,'msg'=>'添加成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'添加失败']);
        }
    }

    //修改回显
    public function edit(){

        $id = Request::instance()->param('id');
        $data = Db::name('dwgl')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();
        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);

        return $this->fetch('unit_edit');
    }

    //修改按钮
    public function ueditbutton(){

        $data = Request::instance()->param();
        $id = $data['id'];

        $data['dwgl_state'] = $data['dwgl_state']=='已启用'?1:0;
        $data['dwgl_last_name'] = $this->zstUser['user_name'];
        $data['dwgl_create_time'] = strtotime($data['dwgl_create_time']);
        $data['dwgl_last_time'] = time();
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'dwgl_name'  => 'require|unique:dwgl,clinic_id^dwgl_name',
        ];
        $msg = [
            'dwgl_name.require' => '名称必须填写',
            'dwgl_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $list = Db::name('dwgl')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);

        if ($list){
            return json_encode(['error'=>1,'msg'=>'修改成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'修改失败']);
        }
    }

    //排序
    public function sort(){

        $data = Request::instance()->param('updateinfo');

        $data_i = json_decode($data,true);

        Db::startTrans();
        try{
            foreach ($data_i as $v){

                Db::name('dwgl')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
            }

            Db::commit();
            return json_encode(['code'=>1,'Msg'=> '更新排序成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['code'=>0,'Msg'=> '更新排序失败']);
        }
    }

    //启用按钮
    public function qy(){

        $ids=input('post.ids/a');

        if(empty($ids)){
            return json(array(
                'error' => -1 ,
                'msg' => '启用失败',
            )) ;
        }
        Db::startTrans();
        try{
            foreach ($ids as $v){

                Db::name('dwgl')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['dwgl_state' => 1]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '启用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 0,
                'msg' => '启用失败',
            )) ;
        }

    }

    //停用按钮
    public function ty(){

        $ids=input('post.ids/a');

        if(empty($ids)){
            return json(array(
                'error' => -1 ,
                'msg' => '停用失败',
            )) ;
        }
        Db::startTrans();
        try{
            foreach ($ids as $v){

                Db::name('dwgl')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['dwgl_state' => 0]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '停用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 0..$e->getMessage(),
                'msg' => '停用失败',
            )) ;
        }

    }


    //导出按钮
    public function daochu(){
        $a = new Api();
        $clinic_id = $this->zstUser['shop_id'];
        $where=["clinic_id"=>$clinic_id];
        $field = "(case when dwgl_state = '1' then '已启用' when dwgl_state = '0' then '已停用'  end) dwgl_state,dwgl_name,id,dwgl_create_name,FROM_UNIXTIME( dwgl_create_time, '%Y-%m-%d' ) dwgl_create_time,dwgl_last_name,FROM_UNIXTIME( dwgl_last_time, '%Y-%m-%d' ) dwgl_last_time";
        $table = Db::name('dwgl')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'dwgl_state'=>'状态',
            'dwgl_name'=>'单位名称',
            'dwgl_create_name'=>'创建人',
            'dwgl_create_time'=>'创建时间',
            'dwgl_last_name' => '最后修改人',
            'dwgl_last_time' => '最后修改时间'
        ];

        $fileName = '单位管理'.time();
        $sheetNmae = '单位管理';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }
}