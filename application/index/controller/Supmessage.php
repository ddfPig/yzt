<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 9:06
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//供货商资料
class Supmessage extends Home
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

        $data = Db::table('zst_ghsz')
                ->field("(case when ghsz_state = '1' then '已启用' when ghsz_state = '0' then '已停用'  end) ghsz_state,b.ghss_name,a.ghsz_name,a.id,a.sort")
                ->alias('a')
                ->join('zst_ghss b','a.ghsz_type = b.id','LEFT')
                ->where('a.clinic_id',$this->zstUser['shop_id'])
                ->order('sort','asc')
                ->limit($start,$rows)->select();

        $total = Db::name('ghsz')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增回显
    public function supmessageadd(){

        return $this->fetch('supmessage_add');
    }

    //新增按钮
    public function supaddbutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['ghsz_create_time'] = $time;
        $data['ghsz_create_name'] = $this->zstUser['user_name'];
        $data['ghsz_last_time'] = $time;
        $data['ghsz_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['ghsz_state'] = 1;

        foreach($data as $k=>$v){
            if($k == "gyslx"){
                unset($data[$k]);
            }
        }

        //验证类
        $rule = [
            'ghsz_name'  => 'require|unique:ghsz,clinic_id^ghsz_name',
        ];
        $msg = [
            'ghsz_name.require' => '名称必须填写',
            'ghsz_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('ghsz')->data($data)->insert();

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

                Db::name('ghsz')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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
        $data = Db::name('ghsz')
            ->field("ghsz_state,a.ghsz_type,b.ghss_name,a.ghsz_name,a.id,a.ghsz_create_time,a.ghsz_create_name,a.ghsz_last_time,a.ghsz_last_name")
            ->alias('a')
            ->join('zst_ghss b','a.ghsz_type = b.id','LEFT')
            ->where('a.id',$id)
            ->where('a.clinic_id',$this->zstUser['shop_id'])
            ->find();
        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('supmessage_edit');
    }

    //修改按钮
    public function supedibutton(){

        $data = Request::instance()->param();

        foreach($data as $k=>$v){
            if($k == "gyslx"){
                unset($data[$k]);
            }
        }
        if ($data['ghsz_type'] == "请选择"){
            return json(array('error' => -1));
        }
        $id = $data['id'];
        $data['ghsz_state'] = $data['ghsz_state']=='已启用'?1:0;
        $data['ghsz_create_time'] = strtotime($data['ghsz_create_time']);
        $data['ghsz_last_time'] = time();
        $data['ghsz_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'ghsz_name'  => 'require|unique:ghsz,clinic_id^ghsz_name',
        ];
        $msg = [
            'ghsz_name.require' => '名称必须填写',
            'ghsz_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('ghsz')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
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

                Db::name('ghsz')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['ghsz_state' => 1]);
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

                Db::name('ghsz')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['ghsz_state' => 0]);
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
        $field = "(case when ghsz_state = '1' then '已启用' when ghsz_state = '0' then '已停用'  end) ghsz_state,a.ghsz_type,b.ghss_name,a.ghsz_name,a.id,a.ghsz_create_name,FROM_UNIXTIME( ghsz_create_time, '%Y-%m-%d' ) ghsz_create_time,a.ghsz_last_name,FROM_UNIXTIME( ghsz_last_time, '%Y-%m-%d' ) ghsz_last_time";
        $table = Db::name('ghsz')
            ->field($field)
            ->alias('a')
            ->join('zst_ghss b','a.ghsz_type = b.id','LEFT')
            ->where('a.clinic_id',$clinic_id)
            ->buildSql();

        $field_name = [
            'id'=>'编号',
            'ghsz_state'=>'状态',
            'ghss_name' => '类型',
            'ghsz_name'=>'供应商资料类型名称',
            'ghsz_create_name'=>'创建人',
            'ghsz_create_time'=>'创建时间',
            'ghsz_last_name' => '最后修改人',
            'ghsz_last_time' => '最后修改时间'
        ];

        $fileName = '供应商资料类型'.time();
        $sheetNmae = '供应商资料类型';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

}