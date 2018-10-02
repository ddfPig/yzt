<?php
namespace app\index\logic;
use think\Db;
use think\Session;

class ClerkLogic
{
	// 判断 用户  密码
	public  function username($id,$user,$pass){
		$code=0;
		$data=db('clerk')->where('clerk_id',$id)->find();
		$data2=db('account')->where('admin_id',$data['admin_id'])->find();
		
		
		if($data2['admin_user']===$user){
			
		}else{
			$data3=db('account')->where('admin_user',$user)->where('admin_id','neq',$data['admin_id'])->find();
			if($data3){
				 return  ['status' =>-3, 'msg' => "用户名已存在"];
			}else{
				$data3=db('account')->where('admin_id',$data['admin_id'])->update(['admin_user'=>$user]);
				if($data3){
					$code++;
				}
					
				
			}
				
		}
		
		if($pass==111111){
			return  ['status' => -2, 'msg' => "",'code'=>$code];
		}else{
			
			//修改密码
			 $admin_pwd_salt=random(10);   
             $pass=encrypt_password($pass,$admin_pwd_salt);
			 $info2=db('account')->where('admin_id',$data['admin_id'])->update(['admin_pass'=>$pass]);
			 if($info2){
				 $code++;
			 }	
			return  ['status' => -1, 'msg' => "",'code'=>$code];			 
		}
			
			
			
	}
	
	
	
   
}






