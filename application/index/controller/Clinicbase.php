<?php
namespace app\index\controller;
use \think\Controller;
use	\think\Db;
use	\think\Session;
class Clinicbase extends Home
// 诊所基础信息
{
    public function  clinicbase_list(){ //查询基础信息

		$admin_auth=Session::get('admin_auth') ;		
		$shop =model('ShopModel');
		$data=$shop->where('shop_id',$admin_auth['shop_id'])->find();

		$provincelist=db('city')->where('type',0)->order('orderNo')->select();

		$this->assign('provincelist',$provincelist);
		$this->assign('data',$data);

		return view();
    
    }
	public function win_medicine(){
		return view();
	}
   
}
