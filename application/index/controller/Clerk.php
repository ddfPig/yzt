<?php
namespace app\index\controller;
use \think\Controller;
use	\think\Db;
use	\think\Session;
use think\Request;
class Clerk extends Home
// 职员管理
{
     public function index()
    {
        return view();
    }
		
		
	public  function clerk_add(){
		$admin_auth=Session::get('admin_auth') ;
		$arr_type=db('zylx')->where('clinic_id',$admin_auth['shop_id'])->where('zylx_state',1)->select();
		$arr_ks=db('zlksgl')->where('clinic_id',$admin_auth['shop_id'])->where('zlksgl_state',1)->select();//科室
		
		$myconfig = \think\Config::get("myconfig");	
		$this->assign([
			'arr_type'		=>	$arr_type,
			'arr_education'	=>	$myconfig['ClerkEducation'],
			'arr_office_type'		=>	$myconfig['ClerkOfficeType'],
			'arr_healthy'	=>	$myconfig['ClerkHealthy'],
			'arr_sex'		=>	$myconfig['sex'],
			'arr_ks'		=>	$arr_ks,
		
		]);
	
		return view();
    }
	public  function clerk_upd(){

		$row=input('get.row');
		if(empty($row)){
			die();
		}
		$admin_auth=Session::get('admin_auth') ;
		$arr_type=db('zylx')->where('clinic_id',$admin_auth['shop_id'])->where('zylx_state',1)->select();
		$myconfig = \think\Config::get("myconfig");	
		
		$data=db('clerk')->where('clerk_id',$row)->find();
		$data['creator_atime']=date('Y-m-d', $data['creator_atime']); 	
		$data['clerk_birth_date']=date('Y-m-d', $data['clerk_birth_date']); 
		$data['clerk_healthy_carddate']=date('Y-m-d', $data['clerk_healthy_carddate']); 
		if($data['upd_man_stime']!=null){
			$data['upd_man_stime']=date('Y-m-d', $data['upd_man_stime']); 
		}
		$arr_ks=db('zlksgl')->where('clinic_id',$admin_auth['shop_id'])->where('zlksgl_state',1)->select();//科室
		$data_user=db('account')->where('admin_id',$data['admin_id'])->find();

		
		$this->assign([
			'arr_type'		=>	$arr_type,
			'arr_education'	=>	$myconfig['ClerkEducation'],
			'arr_office_type'		=>	$myconfig['ClerkOfficeType'],
			'arr_healthy'	=>	$myconfig['ClerkHealthy'],
			'arr_sex'		=>	$myconfig['sex'],
			'data'		=>	$data,
			'data_user'		=>	$data_user,
			'arr_ks'		=>	$arr_ks,
		]);
		
		return view();
	}
	
	
	
  
}
