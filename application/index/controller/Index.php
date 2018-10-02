<?php
namespace app\index\controller;
use think\Db;
use \think\Request;
use think\Validate;

class Index extends Home
{
    public function index()
    {
		/*
		//接收用户权限参数
		$ver = Request::instance()->param('ver');
		if(!$ver)//登录
		{
			return view('login');
		}
		else//首页
		{
			return view('index',['ver'=>$ver]);
		}*/

		return view('index',['ver'=>'chaoji']);
    }

    /**修改密码页面
     * @return mixed
     */
    public function changePass()
    {
        $this->assign('admin_name',session('admin_auth.admin_name'));
        return $this->fetch('code');
    }

    /**修改密码操作
     * @param Request $request
     */
    public function runChangeCode(Request $request)
    {
        $params = $request->param();

        $map['admin_user']=['eq',$params['admin_user']];
        $info = Db::name('account')->where($map)->find();
        if(empty($info)){
            return  json_encode(['code'=>0,'msg'=>'用户不存在']);
        }

        if (encrypt_password($params['old_admin_pass'],$info['admin_pwd_salt'])!==$info['admin_pass']) {
            return json(['code'=>0,'msg'=>'原始密码输入错误！']);
        }

        if (encrypt_password($params['new_admin_pass'],$info['admin_pwd_salt'])==$info['admin_pass']) {
            return json(['code'=>0,'msg'=>'您输入的新密码与旧密码一致！']);
        }

        //验证
        $rule = [
            'old_admin_pass'=>'require|min:6',
            'admin_pass'=>'require|min:6',
            'new_admin_pass'=>'require|min:6|confirm:admin_pass'
        ];

        $msg = [
            'ols_admin_pass.require'=>'请输入原始密码',
            'ols_admin_pass.min'=>'密码长度最少6位',
            'admin_pass.require'=>'请输入新密码',
            'admin_pass.min'=>'密码长度最少6位',
            'new_admin_pass.require'=>'请再次输入新密码',
            'new_admin_pass.min'=>'密码长度最少6位',
            'new_admin_pass.confirm'=>'两次密码输入不一致',

        ];
        $valdate = new Validate($rule,$msg);
        if(!$valdate->check($params)){
            return  json_encode(['code'=>0,'msg'=>$valdate->getError()]);
        }

        //修改密码操作
        $admin_pwd_salt=random(10);
        $pass = [
            'admin_pwd_salt'=>$admin_pwd_salt,
            'admin_pass'=>encrypt_password($params['admin_pass'],$admin_pwd_salt),
        ];





        $res = Db::name('account')->where('admin_id',session('admin_auth.aid'))->update($pass);
        if($res){

            session('admin_auth',null);
            session('admin_auth_sign',null);
            return  json_encode(['code'=>1,'msg'=>'修改密码成功']);
        }else{
            return  json_encode(['code'=>0,'msg'=>'修改密码失败']);
        }

    }

    /** 导出单表数据示例
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     */
    public function daochu()
    {
        $a = new Api();
        $field = ['admin_id'=>'编号','admin_user'=>'姓名'];
        $where=["admin_status"=>1];
        $fileName = '0223';
        $sheetNmae = '123';
        $a->excelDown('zst_account',$field,$where,$fileName,$sheetNmae);
    }


    /** 导出多表查询
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     */
    public function daochu2()
    {
        $a = new Api();
        $excel_field = [
            'clerk_office_num'=>'职员编号',
            'zylx_name'=>'职员类型',
            'clerk_name'=>'职员姓名',
             'clerk_phone'=>'职员手机',
            'clerk_sex'=>'职员性别',
            'shop_name'=>'所属店铺',
            'creator_atime'=>'入职时间'
        ];
        $where=["a.clerk_office_type"=>1];
       $field = "a.clerk_office_num,a.clerk_name,a.clerk_phone,c.zylx_name,b.shop_name,a.clerk_office_type,if(a.clerk_sex=1,'男','女') as clerk_sex,FROM_UNIXTIME(a.creator_atime, '%Y-%m-%d %H:%i:%s') as creator_atime";

       $table = Db::name('clerk')->field($field)->alias('a')
                             ->join('zst_shop b','a.shop_id=b.shop_id')
                             ->join('zst_zylx c','a.clerk_office_id=c.id')
                             ->where($where)
                             ->buildSql();


        $fileName = '职员表_' . time();
        $sheetNmae = '职员表';
        $a->excelDown($table,$excel_field,$where,$fileName,$sheetNmae,2);
    }

    /**
     * excel 数据导入
     */
    public function daoru()
    {
        $excel = new Api();
        $path = 'excel/123.xls';
        $data = $excel->excelTo($path);
        dump($data);//返回二维数组，第一个是excel表头
        //foreach 插入数据

    }


}
