<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/19 20:52
 */


namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Terminus extends Home
{
     public function lists()
     {
         return $this->fetch('index');
     }
    //页面初始化
    public function ini(Request $request){

        $map='';
        $info= session("admin_auth");

        if ($info['role_short'] == "kf" && isset($info['kid'])){
            $map['terminalm_pkfgl_id'] = ['eq',$info['kid']];
        }

        $status = $request->param('status','');
        if($status != -1  && $status != ''){
            $map['state']= ['eq',$status];
        }
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['name']=['like',"%".$keys."%"];
        }
        $page = $request->param('page');
        $rows = $request->param('rows');
        $start = ($page-1)*$rows;

        $data = Db::table('zst_terminalm')
                ->field("(case when state = '1' then '已启用' when state = '0' then '已停用'  end) state,number,username,name,phone,fixedphone,qq,email,FROM_UNIXTIME( create_time, '%Y-%m-%d' ) create_time,FROM_UNIXTIME( last_time, '%Y-%m-%d' ) last_time,id")
                ->where($map)
                ->order('last_time','desc')
                ->limit($start,$rows)
                ->select();

        $total = Db::name('terminalm')->where($map)->count();

        return json(array('total'=>$total,'rows'=>$data));
    }

    //新增
    public function terminalm_add(){



        $numbers = $this->getTableSn('ZDJL-','number','zst_terminalm');

        $this->assign([
            'numbers'	=> $numbers,
        ]);

        return $this->fetch('terminus_add');
    }

    //终端经理管理增加页保存
    public function add(){

        $data = Request::instance()->param();

        if (empty($data['number'])){
            json_encode(['error'=>0,'msg'=>'缺少编号']);
        }
        //验证类
        $rule = [
            'username'  => 'require|unique:terminalm',
            'name'  => 'require',
            'fixedphone'  => 'require',
            'qq'  => 'require',
            'email'  => 'require',
            'password'  => 'require',
            'phone'  => 'require',
        ];
        $msg = [
            'username.require' => '登录名必须填写',
            'username.unique'     => '登录名已存在',
            'phone.require' => '手机号必须填写',
            'name.require' => '姓名必须填写',
            'fixedphone.require' => '固定电话必须填写',
            'qq.require' => 'QQ号必须填写',
            'email.require' => '邮箱必须填写',
            'password.require' => '密码必须填写',

        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }

        //1.登录表
        $admin_pwd_salt=random(10);
        $user_data =[
            'admin_uid'=>UUID(),
            'admin_user'=>$data['username'],
            'admin_pwd_salt'=>$admin_pwd_salt,
            'admin_pass'=>encrypt_password($data['password'],$admin_pwd_salt),
        ];

        //管理员创建终端经理
         if (!empty($data['serviceID'])){
             $admin_id = $data['serviceID'];
          }else{
             $aid = session("admin_auth")['aid'];
             $pkgl = Db::table('zst_pkfgl')->field("id")->where('pkfgl_admin_id',$aid)->find();
             $admin_id = $pkgl['id'];
         }

        $time = time();
        Db::startTrans();
        try{
            $id = Db::name('account')->insertGetId($user_data);
            $zd_data = array(
                'state' => 1,
                'create_time' => $time,
                'last_time' => $time,
                'number' => $data['number'],
                'name' => $data['name'],
                'username' => $data['username'],
                'phone' => $data['phone'],
                'fixedphone' => $data['fixedphone'],
                'qq' => $data['qq'],
                'email' => $data['email'],
                'admin_id' => $id,
                'terminalm_pkfgl_id' => $admin_id
            );
            $accdata=array(
                'uid'=>$id,
                'group_id'=>8,
            );
            Db::name('auth_group_access')->insert($accdata);
            Db::name('terminalm')->insert($zd_data);
            Db::commit();
            return json_encode(['error'=>1,'msg'=> '保存成功!']);
        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['error'=>0,'msg'=> '保存失败!'.$e->getMessage()]);
        }
    }

    //终端经理修改回显
    public function edit(){

        $id = Request::instance()->param('id');
        $zd_data = Db::name('terminalm')->where('id',$id)->find();
        $kfid = $zd_data['terminalm_pkfgl_id'];
        $kf_data = Db::name('pkfgl')->where('id',$kfid)->find();

        $this->assign([
            'data'	=> $zd_data,
            'kf_data' =>$kf_data,
            'id'	=> $id
        ]);

        return $this->fetch('terminus_edit');
    }
    //终端经理修改按钮
    public function edit_button(){

        $data = Request::instance()->param();

        $last_time = time();
        $number = $data['number'];
        if ($data['password'] != '' || $data['password'] != NULL){
            $admin_id = $data['admin_id'];
            $admin_pwd_salt = random(10);
            $pass = encrypt_password($data['password'],$admin_pwd_salt);
            $data_a = array(
                'admin_pass' => $pass,
                'admin_pwd_salt' => $admin_pwd_salt
            );
        }
        //验证类
        $rule = [
            'name'  => 'require',
            'fixedphone'  => 'require',
            'qq'  => 'require',
            'email'  => 'require',
            'phone'  => 'require',
        ];
        $msg = [
            'phone.unique'     => '手机号已存在',
            'name.require' => '姓名必须填写',
            'fixedphone.require' => '固定电话必须填写',
            'qq.require' => 'QQ号必须填写',
            'email.require' => '邮箱必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }
        //管理员创建终端经理
        if (!empty($data['serviceID'])){
            $admin_id = $data['serviceID'];
        }else{
            $aid = session("admin_auth")['aid'];
            $pkgl = Db::table('zst_pkfgl')->field("id")->where('pkfgl_admin_id',$aid)->find();
            $admin_id = $pkgl['id'];
        }

        $zd_data = array(
            'state' => 1,
            'last_time' => $last_time,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'fixedphone' => $data['fixedphone'],
            'qq' => $data['qq'],
            'email' => $data['email'],
            'terminalm_pkfgl_id' => $admin_id,
        );

        Db::startTrans();
        try{
            if ($data['password'] != '' || $data['password'] != NULL){
                Db::name('account')->where('admin_id',$admin_id)->update($data_a);
            }
            Db::name('terminalm')->where('number',$number)->update($zd_data);
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

                Db::name('terminalm')->where('id',$v)->update(['state' => 1]);
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

                Db::name('terminalm')->where('id',$v)->update(['state' => 0]);
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
            $map['state'] = $status;
        }
        $field = "(case when state = '1' then '已启用' when state = '0' then '已停用'  end) state,number,username,name,phone,fixedphone,qq,email,FROM_UNIXTIME( create_time, '%Y-%m-%d' ) create_time,FROM_UNIXTIME( last_time, '%Y-%m-%d' ) last_time";
        if ($keyword != ''){
            $table = Db::name('terminalm')->field($field)->where($map)->where('name','LIKE',"%$keyword%")->buildSql();
        }else{
            $table = Db::name('terminalm')->field($field)->where($map)->where($maps)->buildSql();
        }


        $field_name = [
            'state'=>'状态',
            'number'=>'终端经理编码',
            'username'=>'终端经理登录用户名',
            'name'=>'姓名',
            'phone' => '手机号',
            'fixedphone' => '座机号',
            'qq'=>'QQ号',
            'email' => '邮箱',
            'create_time' => '创建时间',
            'last_time' => '最后修改时间',
        ];

        $fileName = '终端经理管理'.time();
        $sheetNmae = '终端经理管理';
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