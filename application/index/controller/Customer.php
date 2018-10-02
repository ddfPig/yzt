<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 15:41
 */

namespace app\index\controller;


use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//客服管理
class Customer extends Home
{
    public function index()
    {

        return $this->fetch('index');

    }

    //页面初始化
    public function ini(Request $request){

        $map='';
        $info= session("admin_auth");

        if ($info['role_short'] == "zd" && isset($info['tid'])){
            $cid = Db::name('terminalm')->where('id',$info['tid'])->find();

            $map['id'] = ['eq',$cid['terminalm_pkfgl_id']];
        }

        $status = $request->param('status','');
        if($status != -1  && $status != ''){
            $map['pkfgl_state']= ['eq',$status];
        }
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['pkfgl_name']=['like',"%".$keys."%"];
        }
        $page = $request->param('page');
        $rows = $request->param('rows');
        $start = ($page-1)*$rows;

        $data = Db::table('zst_pkfgl')
                ->field("(case when pkfgl_state = '1' then '已启用' when pkfgl_state = '0' then '已停用'  end) pkfgl_state,pkfgl_id,pkfgl_username,pkfgl_name,pkfgl_phone,pkfgl_landline,pkfgl_qq,pkfgl_email,FROM_UNIXTIME( pkfgl_create_time, '%Y-%m-%d' ) pkfgl_create_time,FROM_UNIXTIME( pkfgl_last_time, '%Y-%m-%d' ) pkfgl_last_time,id")
                ->order('pkfgl_last_time','desc')
                ->where($map)
                ->limit($start,$rows)
                ->select();

        $total = Db::name('pkfgl')->where($map)->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增
    public function customeradd(){

        $pkfgl_id = $this->getTableSn('KHGL-','pkfgl_id','zst_pkfgl');;

        $this->assign([
            'pkfgl_id'	=> $pkfgl_id,
        ]);

        return $this->fetch('customer_add');
    }

    //客服管理增加页保存
    public function add(){

        $data = Request::instance()->param();
        if (empty($data['pkfgl_id'])){
            return json(array(
                'error' => -1,
            ));
        }
        //验证类
        $rule = [
            'pkfgl_username'  => 'require|unique:pkfgl',
            'pkfgl_name' => 'require',
            'pkfgl_code' => 'require',
            'pkfgl_phone' => 'require',
            'pkfgl_landline' => 'require',
            'pkfgl_qq' => 'require',
            'pkfgl_email' => 'require',
        ];
        $msg = [
            'pkfgl_username.require' => '登录名必须填写',
            'pkfgl_username.unique'     => '登录名已存在',
            'pkfgl_name.require' => '姓名必须填写',
            'pkfgl_code.require' => '密码必须填写',
            'pkfgl_phone.require' => '手机号必须填写',
            'pkfgl_landline.require' => '固定电话必须填写',
            'pkfgl_qq.require' => 'QQ号必须填写',
            'pkfgl_email.require' => '邮箱必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }
        $time = time();
        //1.登录表
        $admin_pwd_salt=random(10);
        $user_data =[
            'admin_uid'=>UUID(),
            'admin_user'=>$data['pkfgl_username'],
            'admin_pwd_salt'=>$admin_pwd_salt,
            'admin_pass'=>encrypt_password($data['pkfgl_code'],$admin_pwd_salt),
        ];
        Db::startTrans();
        try{
            $id = Db::name('account')->insertGetId($user_data);
            $kf_data = array(
                'pkfgl_state' => 1,
                'pkfgl_create_time' => $time,
                'pkfgl_last_time' => $time,
                'pkfgl_id' => $data['pkfgl_id'],
                'pkfgl_name' => $data['pkfgl_name'],
                'pkfgl_username' => $data['pkfgl_username'],
                'pkfgl_phone' => $data['pkfgl_phone'],
                'pkfgl_landline' => $data['pkfgl_landline'],
                'pkfgl_qq' => $data['pkfgl_qq'],
                'pkfgl_email' => $data['pkfgl_email'],
                'pkfgl_admin_id' => $id
             );

            $accdata=array(
                'uid'=>$id,
                'group_id'=>7,
            );
            Db::name('auth_group_access')->insert($accdata);
            Db::name('pkfgl')->insert($kf_data);
            Db::commit();
            return json_encode(['error'=>1,'msg'=> '保存成功!']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['error'=>0,'msg'=> '保存失败!']);
        }
    }

    //客服修改回显
    public function edit(){

        $id = Request::instance()->param('id');

        $data = Db::name('pkfgl')->where('id',$id)->find();

        $this->assign([
            'data'	=> $data,
            'id'	=> $id
        ]);

        return $this->fetch('customer_edit');
    }
    //客服修改按钮
    public function editbutton(){

        $data = Request::instance()->param();

        $admin_id = $data['pkfgl_admin_id'];    
        $pkfgl_id = $data['pkfgl_id'];
        $last_time = time();

        if ($data['pkfgl_code'] != '' || $data['pkfgl_code'] != NULL){
            $admin_pwd_salt = random(10);
            $pass = encrypt_password($data['pkfgl_code'],$admin_pwd_salt);
            $data_a = array(
                'admin_pass' => $pass,
                'admin_pwd_salt' => $admin_pwd_salt
            );
        }
        //验证类
        $rule = [
            'pkfgl_name' => 'require',
            'pkfgl_phone' => 'require',
            'pkfgl_landline' => 'require',
            'pkfgl_qq' => 'require',
            'pkfgl_email' => 'require',
        ];
        $msg = [
            'pkfgl_name.require' => '姓名必须填写',
            'pkfgl_phone.require' => '手机号必须填写',
            'pkfgl_landline.require' => '固定电话必须填写',
            'pkfgl_qq.require' => 'QQ号必须填写',
            'pkfgl_email.require' => '邮箱必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        $kf_data = array(
            'pkfgl_state' => 1,
            'pkfgl_last_time' => $last_time,
            'pkfgl_id' => $data['pkfgl_id'],
            'pkfgl_name' => $data['pkfgl_name'],
            'pkfgl_phone' => $data['pkfgl_phone'],
            'pkfgl_landline' => $data['pkfgl_landline'],
            'pkfgl_qq' => $data['pkfgl_qq'],
            'pkfgl_email' => $data['pkfgl_email'],
        );

        Db::startTrans();
        try{

            if ($data['pkfgl_code'] != '' || $data['pkfgl_code'] != NULL){
                Db::name('account')->where('admin_id',$admin_id)->update($data_a);
            }

            Db::name('pkfgl')->where('pkfgl_id',$pkfgl_id)->update($kf_data);
            Db::commit();
            return json_encode(['error'=>1,'msg'=> '修改成功!']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['error'=>0,'msg'=> '修改失败!']);
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

                Db::name('pkfgl')->where('id',$v)->update(['pkfgl_state' => 1]);
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

                Db::name('pkfgl')->where('id',$v)->update(['pkfgl_state' => 0]);
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
        $status = input('get.status');
        $keyword = input('get.keyword');
        $map='';
        $maps = '';

        if ($status != -1){
            $map['pkfgl_state'] = $status;
        }
        $field = "(case when pkfgl_state = '1' then '已启用' when pkfgl_state = '0' then '已停用'  end) pkfgl_state,pkfgl_id,pkfgl_username,pkfgl_name,pkfgl_phone,pkfgl_landline,pkfgl_qq,pkfgl_email,FROM_UNIXTIME( pkfgl_create_time, '%Y-%m-%d' ) pkfgl_create_time,FROM_UNIXTIME( pkfgl_last_time, '%Y-%m-%d' ) pkfgl_last_time";
        if ($keyword != ''){
            $table = Db::name('pkfgl')->field($field)->where($map)->where('pkfgl_name','LIKE',"%$keyword%")->buildSql();
        }else{
            $table = Db::name('pkfgl')->field($field)->where($map)->where($maps)->buildSql();
        }


        $field_name = [
            'pkfgl_state'=>'状态',
            'pkfgl_id'=>'客服编码',
            'pkfgl_username'=>'客服登录用户名',
            'pkfgl_name'=>'姓名',
            'pkfgl_phone' => '手机号',
            'pkfgl_landline' => '座机号',
            'pkfgl_qq'=>'QQ号',
            'pkfgl_email' => '邮箱',
            'pkfgl_create_time' => '创建时间',
            'pkfgl_last_time' => '最后修改时间',
        ];

        $fileName = '客服管理'.time();
        $sheetNmae = '客服管理';
        $a->excelDown($table,$field_name,$where=[],$fileName,$sheetNmae,2);
    }

    /**用户编号
     * @return bool|string
     */
    public  function  getTableSn($prefix,$field,$table)
    {
        $res=Db::query("select count(DISTINCT $field) as count from  $table ");
        if($res){
            $sn=$prefix.str_pad($res[0]['count']+1,4,0,STR_PAD_LEFT);

        }else{
            $sn=$prefix.str_pad(1,4,0,STR_PAD_LEFT);
        }
        return $sn;
    }

}