<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 9:31
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//适用诊疗
class Treatment extends Home
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

        $data = Db::table('zst_syzl')->field("(case when syzl_state = '1' then '已启用' when syzl_state = '0' then '已停用'  end) syzl_state,syzl_name,id,sort")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();
        $total = Db::name('syzl')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增回显
    public function treatmentadd(){

        return $this->fetch('treatment_add');
    }

    //新增按钮
    public function taddbutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['syzl_create_time'] = $time;
        $data['syzl_create_name'] = $this->zstUser['user_name'];
        $data['syzl_last_time'] = $time;
        $data['syzl_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['syzl_state'] = 1;

        //验证类
        $rule = [
            'syzl_name'  => 'require|unique:syzl,clinic_id^syzl_name',
        ];
        $msg = [
            'syzl_name.require' => '名称必须填写',
            'syzl_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('syzl')->data($data)->insert();

        if ($list == 1){
            return json_encode(['error'=>1,'msg'=>'添加成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'添加失败']);
        }
    }

    //修改回显
    public function edit(){

        $id = Request::instance()->param('id');
        $data = Db::name('syzl')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();

        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('treatment_edit');
    }

    //修改按钮
    public function teditbutton(){

        $data = Request::instance()->param();
        $id = $data['id'];
        $data['syzl_state'] = $data['syzl_state']=='已启用'?1:0;
        $data['syzl_create_time'] = strtotime($data['syzl_create_time']);
        $data['syzl_last_time'] = time();
        $data['syzl_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'syzl_name'  => 'require|unique:syzl,clinic_id^syzl_name',
        ];
        $msg = [
            'syzl_name.require' => '名称必须填写',
            'syzl_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $list = Db::name('syzl')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
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

                Db::name('syzl')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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

                Db::name('syzl')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['syzl_state' => 1]);
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

                Db::name('syzl')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['syzl_state' => 0]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '停用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 0,
                'msg' => '停用失败',
            )) ;
        }
    }
    //导出按钮
    public function daochu(){
        $a = new Api();
        $clinic_id = $this->zstUser['shop_id'];
        $where=["clinic_id"=>$clinic_id];
        $field = "(case when syzl_state = '1' then '已启用' when syzl_state = '0' then '已停用'  end) syzl_state,syzl_name,id,syzl_create_name,FROM_UNIXTIME( syzl_create_time, '%Y-%m-%d' ) syzl_create_time,syzl_last_name,FROM_UNIXTIME( syzl_last_time, '%Y-%m-%d' ) syzl_last_time";
        $table = Db::name('syzl')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'syzl_state'=>'状态',
            'syzl_name'=>'适用诊疗名称',
            'syzl_create_name'=>'创建人',
            'syzl_create_time'=>'创建时间',
            'syzl_last_name' => '最后修改人',
            'syzl_last_time' => '最后修改时间'
        ];

        $fileName = '适用诊疗'.time();
        $sheetNmae = '适用诊疗';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }
}