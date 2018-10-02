<?php
namespace app\index\Model;
use think\Model;
use	\think\Session;
class Clerk extends Model
{
	// protected	$createTime	=	'create_at';
	// protected	$updateTime	=	'update_at';
	
	public	function	getClerkOfficeIdAttr($value) //职业类型
	{
		$admin_auth=Session::get('admin_auth') ;
		$data=db('zylx')->where('clinic_id',$admin_auth['shop_id'])->select();
		foreach ($data as $k=>$v){
			if($v['id']==$value){
				return $v['zylx_name'];
			}
		}
		return null;
	}

	public	function	getClerkSexAttr($value) //性别
	{
		if(empty($value)){
			return null;
		}else{
			$myconfig = \think\Config::get("myconfig");	
			return	$myconfig['sex'][$value];
		}
		
	}
	public	function	getClerkStatusAttr($value) //状态
	{
		$myconfig = \think\Config::get("myconfig");	
		return	$myconfig['ClerkStatus'][$value];
	}
	public	function	getClerkBirthDateAttr($value) //出生日期
	{
		if(empty($value)){
			return	null;
		}else{
			$value=date("Y-m-d",$value) ;		
			return	$value;
		}
	}
	 public	function	getClerkEducationAttr($value) //学历
	 {
		if(empty($value)){
			return	null;
		}else{
			$myconfig = \think\Config::get("myconfig");	
			return	$myconfig['ClerkEducation'][$value];
		}
		
	 }
	  public	function	getKsAttr($value) //科室
	 {
		if(empty($value)){
			return	null;
		}else{
			$admin_auth=Session::get('admin_auth') ;
			$data=db('zlksgl')->where('clinic_id',$admin_auth['shop_id'])->select();
			foreach($data as $k=>$v){
				if($v['id']==$value){
					return $v['zlksgl_name'];
				}
			}
			return null;
		}
		
	 }
	public	function	getClerkHealthyAttr($value) //健康状态
	{
		if(empty($value))
			return null;
		else
			$myconfig = \think\Config::get("myconfig");	
			return	$myconfig['ClerkHealthy'][$value];
	}
	public	function	getClerkHealthyCarddateAttr($value) //发证日期
	{
		if(empty($value))
			return null;
		else			
			$value=date("Y-m-d",$value) ;		
			return	$value;
	}
	public	function	getClerkOfficeTypeAttr($value) //是否在职
	{
		if($value==-1)
			return  null;
		else 
			$myconfig = \think\Config::get("myconfig");	
			return	$myconfig['ClerkOfficeType'][$value];	
	}
	
	
    public	function setClerkBirthDateAttr($value){
	
			$value = strtotime($value);
			return	$value;
    }
	public	function setClerkHealthyCarddateAttr($value){ 
	
			$value = strtotime($value);
			return	$value;
    }		
	
  
}



