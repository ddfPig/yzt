<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 9:42
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//常见病症
class Cdisease extends Home
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

        $data = Db::table('zst_cjbz')->field("(case when cjbz_state = '1' then '已启用' when cjbz_state = '0' then '已停用'  end) cjbz_state,cjbz_name,id,sort")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();

        $total = Db::name('cjbz')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增
    public function cdiseaseadd(){

        return $this->fetch('cdisease_add');
    }

    //新增保存按钮
    public function cdaddbutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['cjbz_create_time'] = $time;
        $data['cjbz_last_time'] = $time;
        $data['cjbz_create_name'] = $this->zstUser['user_name'];
        $data['cjbz_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['cjbz_state'] = 1;

        //验证类
        $rule = [
            'cjbz_name'  => 'require|unique:cjbz,clinic_id^cjbz_name',
        ];
        $msg = [
            'cjbz_name.require' => '名称必须填写',
            'cjbz_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('cjbz')->data($data)->insert();

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

                Db::name('cjbz')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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
        $data = Db::name('cjbz')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();
        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('cdisease_edit');
    }

    //修改按钮
    public function cdiedibutton(){

        $data = Request::instance()->param();
        $data['cjbz_state'] = $data['cjbz_state']=='已启用'?1:0;
        $id = $data['id'];
        $data['cjbz_create_time'] = strtotime($data['cjbz_create_time']);
        $data['cjbz_last_time'] = time();
        $data['cjbz_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'cjbz_name'  => 'require|unique:cjbz,clinic_id^cjbz_name',
        ];
        $msg = [
            'cjbz_name.require' => '名称必须填写',
            'cjbz_name.unique'     => '名称已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('cjbz')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
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

                Db::name('cjbz')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['cjbz_state' => 1]);
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

                Db::name('cjbz')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['cjbz_state' => 0]);
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
        $field = "(case when cjbz_state = '1' then '已启用' when cjbz_state = '0' then '已停用'  end) cjbz_state,cjbz_name,id,cjbz_create_name,FROM_UNIXTIME( cjbz_create_time, '%Y-%m-%d' ) cjbz_create_time,cjbz_last_name,FROM_UNIXTIME( cjbz_last_time, '%Y-%m-%d' ) cjbz_last_time";
        $table = Db::name('cjbz')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'cjbz_state'=>'状态',
            'cjbz_name'=>'常见病症名称',
            'cjbz_create_name'=>'创建人',
            'cjbz_create_time'=>'创建时间',
            'cjbz_last_name' => '最后修改人',
            'cjbz_last_time' => '最后修改时间'
        ];

        $fileName = '常见病症管理'.time();
        $sheetNmae = '常见病症管理';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

}