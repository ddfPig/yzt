<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26
 * Time: 14:44
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;


//满充奖励管理
class Rechargereward extends Home
{
    public function index(){

        return $this->fetch('index');
    }

    //页面初始化
    public function ini(){

        $page = Request::instance()->param('page');
        $rows = Request::instance()->param('rows');
        $start = ($page-1)*$rows;

        $data = Db::table('zst_dic_reward')->field("(case when reward_state = '1' then '已启用' when reward_state = '0' then '已停用'  end) reward_state,reward_real,reward_handsel,id,sort")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();

        $total = Db::name('dic_reward')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增跳转
    public function rec_add(){

        return $this->fetch('rechargereward_add');
    }

    //新增保存按钮
    public function rec_add_button(){

        $time = time();
        $data = Request::instance()->param();
        $data['reward_create_time'] = $time;
        $data['reward_create_name'] = $this->zstUser['user_name'];
        $data['reward_last_time'] = $time;
        $data['reward_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['reward_state'] = 1;

        //验证类
        $rule = [
            'reward_real'  => 'require',
            'reward_handsel'  => 'require',
        ];
        $msg = [
            'reward_real.require' => '实际金额必须填写',
            'reward_handsel.require' => '奖励金额必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $list = Db::name('dic_reward')->data($data)->insert();

        if ($list == 1){
            return json_encode(['error'=>1,'msg'=>'添加成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'添加失败']);
        }
    }

    //修改回显
    public function edit(){

        $id = Request::instance()->param('id');
        $data = Db::name('dic_reward')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();

        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('rechargereward_edit');
    }

    //修改按钮
    public function rec_edit_button(){

        $data = Request::instance()->param();

        $data['reward_state'] = $data['reward_state']=='已启用'?1:0;
        $id = $data['id'];
        $data['reward_create_time'] = strtotime($data['reward_create_time']);
        $data['reward_last_time'] = time();
        $data['reward_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'reward_real'  => 'require',
            'reward_handsel'  => 'require',
        ];
        $msg = [
            'reward_real.require' => '实际金额必须填写',
            'reward_handsel.require' => '奖励金额必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $list = Db::name('dic_reward')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
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

                Db::name('dic_reward')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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

                Db::name('dic_reward')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['reward_state' => 1]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '启用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 0 ,
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

                Db::name('dic_reward')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['reward_state' => 0]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '停用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 0 ,
                'msg' => '停用失败',
            )) ;
        }
    }

    //导出按钮
    public function daochu(){

        $a = new Api();
        $clinic_id = $this->zstUser['shop_id'];
        $where=["clinic_id"=>$clinic_id];
        $field = "(case when reward_state = '1' then '已启用' when reward_state = '0' then '已停用'  end) reward_state,reward_real,reward_handsel,id,reward_create_name,FROM_UNIXTIME( reward_create_time, '%Y-%m-%d' ) reward_create_time,reward_last_name,FROM_UNIXTIME( reward_last_time, '%Y-%m-%d' ) reward_last_time";
        $table = Db::name('dic_reward')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'reward_state'=>'状态',
            'reward_real'=>'实际充值金额',
            'reward_handsel'=>'奖励金额',
            'reward_create_name'=>'创建人',
            'reward_create_time'=>'创建时间',
            'reward_last_name' => '最后修改人',
            'reward_last_time' => '最后修改时间'
        ];

        $fileName = '充值奖励管理'.time();
        $sheetNmae = '充值奖励管理';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }


}