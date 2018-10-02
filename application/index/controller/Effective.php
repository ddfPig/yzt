<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 9:53
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//有效期
class Effective extends Home
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

        $data = Db::table('zst_yxq')->field("(case when yxq_state = '1' then '已启用' when yxq_state = '0' then '已停用'  end) yxq_state,yxq_month,sort,id")->where('clinic_id',$this->zstUser['shop_id'])->order('sort','asc')->limit($start,$rows)->select();

        $total = Db::name('yxq')->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增回显
    public function effectiveadd(){

        return $this->fetch('effective_add');
    }

    //新增保存按钮
    public function effaddbutton(){

        $time = time();
        $data = Request::instance()->param();
        $data['yxq_create_time'] = $time;
        $data['yxq_last_time'] = $time;
        $data['yxq_create_name'] =$this->zstUser['user_name'];
        $data['yxq_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];
        $data['yxq_state'] = 1;

        //验证类
        $rule = [
            'yxq_month'  => 'require|unique:yxq,clinic_id^yxq_month',
        ];
        $msg = [
            'yxq_month.require' => '期限必须填写',
            'yxq_month.unique'     => '期限已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('yxq')->data($data)->insert();

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

                Db::name('yxq')->where('id',$v['id'])->where('clinic_id',$this->zstUser['shop_id'])->setField('sort',$v['sort']);
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
        $data = Db::name('yxq')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->find();

        $this->assign([
            'id' => $id,
            'data'	=> $data,
        ]);
        return $this->fetch('effective_edit');
    }

    //修改按钮
    public function effedibutton(){

        $data = Request::instance()->param();
        $data['yxq_state'] = $data['yxq_state']=='已启用'?1:0;
        $id = $data['id'];
        $data['yxq_create_time'] = strtotime($data['yxq_create_time']);
        $data['yxq_last_time'] = time();
        $data['yxq_last_name'] = $this->zstUser['user_name'];
        $data['clinic_id'] = $this->zstUser['shop_id'];

        //验证类
        $rule = [
            'yxq_month'  => 'require|unique:yxq,clinic_id^yxq_month',
        ];
        $msg = [
            'yxq_month.require' => '期限必须填写',
            'yxq_month.unique'     => '期限已存在',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $list = Db::name('yxq')->where('id',$id)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
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

                Db::name('yxq')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['yxq_state' => 1]);
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

                Db::name('yxq')->where('id',$v)->where('clinic_id',$this->zstUser['shop_id'])->update(['yxq_state' => 0]);
            }

            Db::commit();
            return json(array(
                'error' => 1 ,
                'msg' => '停用成功',
            )) ;
        }catch (\Exception $e){
            Db::rollback();
            return json(array(
                'error' => 1 ,
                'msg' => '停用失败',
            )) ;
        }
    }

    //导出按钮
    public function daochu(){
        $a = new Api();
        $clinic_id = $this->zstUser['shop_id'];
        $where=["clinic_id"=>$clinic_id];
        $field = "(case when yxq_state = '1' then '已启用' when yxq_state = '0' then '已停用'  end) yxq_state,yxq_month,id,yxq_create_name,FROM_UNIXTIME( yxq_create_time, '%Y-%m-%d' ) yxq_create_time,yxq_last_name,FROM_UNIXTIME( yxq_last_time, '%Y-%m-%d' ) yxq_last_time";
        $table = Db::name('yxq')->field($field)->where($where)->order('sort','asc')->buildSql();

        $field_name = [
            'id'=>'编号',
            'yxq_state'=>'状态',
            'yxq_month'=>'有效期月份',
            'yxq_create_name'=>'创建人',
            'yxq_create_time'=>'创建时间',
            'yxq_last_name' => '最后修改人',
            'yxq_last_time' => '最后修改时间'
        ];

        $fileName = '有效期管理'.time();
        $sheetNmae = '有效期管理';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

}