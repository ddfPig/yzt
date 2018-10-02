<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 10:29
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//收款管理
class Receipt extends Home
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

        $data = Db::table('zst_skfs')->field("(case when skfs_state = '1' then '已启用' when skfs_state = '0' then '已停用'  end) skfs_state,skfs_name,id,sort,skfs_issystem")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();

        $total = Db::name('skfs')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增跳转
    public function receiptadd(){

        return $this->fetch('receipt_add');
    }

    //新增保存按钮
    public function raddbutton(){
        $time = time();
        $data = Request::instance()->param();
        $data['skfs_create_time'] = $time;
        $data['skfs_create_name'] = $this->zstUser['user_name'];
        $data['skfs_last_time'] = $time;
        $data['skfs_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['skfs_state'] = 1;

        //验证类
        $rule = [
            'skfs_name'  => 'require|unique:skfs,clinic_id^skfs_name',
        ];
        $msg = [
            'skfs_name.require' => '名称必须填写',
            'skfs_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('skfs')->data($data)->insert();

        if ($list == 1){
            return json_encode(['error'=>1,'msg'=>'添加成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'添加失败']);
        }
    }

    //排序
    public function sort(){

        $data = Request::instance()->param('updateinfo');

        $data_i = json_decode($data,true);

        Db::startTrans();
        try{
            foreach ($data_i as $v){

                Db::name('skfs')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
            }

            Db::commit();
            return json_encode(['code'=>1,'Msg'=> '更新排序成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['code'=>0,'Msg'=> '更新排序失败']);
        }
    }


    //修改回显
    public function edit(){

        $id = Request::instance()->param('id');
        $data = Db::name('skfs')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();

        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('receipt_edit');
    }

    //修改按钮
    public function reditbutton(){

        $data = Request::instance()->param();
        $data['skfs_state'] = $data['skfs_state']=='已启用'?1:0;
        $id = $data['id'];
        $data['skfs_create_time'] = strtotime($data['skfs_create_time']);
        $data['skfs_last_time'] = time();
        $data['skfs_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'skfs_name'  => 'require|unique:skfs,clinic_id^skfs_name',
        ];
        $msg = [
            'skfs_name.require' => '名称必须填写',
            'skfs_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('skfs')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
        if ($list){
            return json_encode(['error'=>1,'msg'=>'修改成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'修改失败']);
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

                Db::name('skfs')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['skfs_state' => 1]);
            }

            Db::commit();

            return json_encode(['error'=>1,'msg'=>'启用成功']);

        }catch (\Exception $e){
            Db::rollback();

            return json_encode(['error'=>0,'msg'=>'启用失败']);
        }
    }

    //停用按钮
    public function ty(){

        $ids = input('post.ids/a');
        $skfs_issystem = input('post.skfs_issystem/a');
        if(empty($ids)){
            return json_encode(['error'=>-1,'msg'=>'停用失败']);
        }

        foreach ($skfs_issystem as $v){
            if ($v == 1){
                return json_encode(['error'=>-2,'msg'=>'停用失败! 默认项:现金，余额，记账，无法被停用!']);
            }
        }

        Db::startTrans();
        try{
            foreach ($ids as $v){

                Db::name('skfs')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->where("skfs_issystem",0)->update(['skfs_state' => 0]);
            }

            Db::commit();
            return json_encode(['error'=>1,'msg'=>'停用成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['error'=>1,'msg'=>'停用失败']);
        }
    }

    //导出按钮
    public function daochu(){

        $a = new Api();
        $clinic_id = $this->zstUser['shop_id'];
        $where=["clinic_id"=>$clinic_id];
        $field = "(case when skfs_state = '1' then '已启用' when skfs_state = '0' then '已停用'  end) skfs_state,skfs_name,id,skfs_create_name,FROM_UNIXTIME( skfs_create_time, '%Y-%m-%d' ) skfs_create_time,skfs_last_name,FROM_UNIXTIME( skfs_last_time, '%Y-%m-%d' ) skfs_last_time";
        $table = Db::name('skfs')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'skfs_state'=>'状态',
            'skfs_name'=>'收款方式名称',
            'skfs_create_name'=>'创建人',
            'skfs_create_time'=>'创建时间',
            'skfs_last_name' => '最后修改人',
            'skfs_last_time' => '最后修改时间'
        ];

        $fileName = '收款方式'.time();
        $sheetNmae = '收款方式';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

}