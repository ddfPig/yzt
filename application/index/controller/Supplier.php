<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:25
 */
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//供货商类型
class Supplier extends Home
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

        $data = Db::table('zst_ghss')->field("(case when ghss_state = '1' then '已启用' when ghss_state = '0' then '已停用'  end) ghss_state,id,ghss_name,sort")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();
        $total = Db::name('ghss')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增跳转
    public function supadd(){
        return $this->fetch('sup_add');
    }

    //保存数据
    public function adddata(){

        $time = time();
        $data = Request::instance()->param();
        $data['ghss_create_time'] = $time;
        $data['ghss_create_name'] = $this->zstUser['user_name'];
        $data['ghss_last_time'] = $time;
        $data['ghss_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['ghss_state'] = 1;

        //验证类
        $rule = [
            'ghss_name'  => 'require|unique:ghss,clinic_id^ghss_name',
        ];
        $msg = [
            'ghss_name.require' => '名称必须填写',
            'ghss_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('ghss')->data($data)->insert();

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

                Db::name('ghss')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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
        $data = Db::name('ghss')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();

        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('sup_edit');
    }

    //修改按钮
    public function suppedit(){

        $data = Request::instance()->param();
        $id = $data['id'];
        $data['ghss_create_time'] = strtotime($data['ghss_create_time']);
        $data['ghss_last_time'] = time();
        $data['ghss_state'] = $data['ghss_state']=='已启用'?1:0;
        $data['ghss_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'ghss_name'  => 'require|unique:ghss,clinic_id^ghss_name',
        ];
        $msg = [
            'ghss_name.require' => '名称必须填写',
            'ghss_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('ghss')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
        if ($list){
            return json_encode(['error'=>1,'msg'=>'修改成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'修改失败']);
        }
    }

    //类型回显
    public function type(){

        $id = Db::name('ghss')->field("ghss_name,id")->where('ghss_state',1)->where('clinic_id',$this->zstUser['shop_id'])->select();

        if ($id){

            return json($id);
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

                Db::name('ghss')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['ghss_state' => 1]);
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

                Db::name('ghss')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['ghss_state' => 0]);
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
        $field = "(case when ghss_state = '1' then '已启用' when ghss_state = '0' then '已停用'  end) ghss_state,ghss_name,id,ghss_create_name,FROM_UNIXTIME( ghss_create_time, '%Y-%m-%d' ) ghss_create_time,ghss_last_name,FROM_UNIXTIME( ghss_last_time, '%Y-%m-%d' ) ghss_last_time";
        $table = Db::name('ghss')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'ghss_state'=>'状态',
            'ghss_name'=>'供应商类型名称',
            'ghss_create_name'=>'创建人',
            'ghss_create_time'=>'创建时间',
            'ghss_last_name' => '最后修改人',
            'ghss_last_time' => '最后修改时间'
        ];

        $fileName = '供应商类型'.time();
        $sheetNmae = '供应商类型';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

}
