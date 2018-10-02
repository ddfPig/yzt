<?php
namespace app\index\controller;
use \think\Controller;
use	\think\Db;
use app\index\logic\ClerkLogic;
use	\think\Session;
use	\think\request;
use app\index\Validate\ClerkValidate;
header("Content-type:application/vnd.ms-excel"); 
 header("Content-Disposition:attachment;filename=export_data.xls"); 
class Clerkapi extends Home
// 职员管理api
{	
 
	//查询职员
	public  function clerk_list(){
		
		$admin_auth=Session::get('admin_auth') ;
		$page=input('post.page/d');
		$limit=input('post.rows/d');
	
		$type_code=input('post.type_code/d');
		$type_codes=input('post.type_codes/d');
		
		$page =$page-1;
	
		if($page>0){
			
			$page=$page*$limit;
			
		}    

		if(empty($type_code)){
			$type_code=null;
		}else{
			if($type_code==2){
				$type_code=0;
			}
			$type_code=['clerk_status'=>$type_code];
		
		}
		if(empty($type_codes)){
			$type_codes=null;
		}else{
			if($type_codes==2){
				$type_codes=0;
			}
			$type_codes=['clerk_office_type'=>$type_codes];
		
		}
	

		$Clerk =model('Clerk');
		$data=$Clerk->where('is_boss',0)->where($type_code)->where($type_codes)->where('shop_id',$admin_auth['shop_id'])->order('clerk_status desc,clerk_office_type desc')->limit($page,$limit)->select();
		
	
 		$count=$Clerk->where('is_boss',0)->where($type_code)->where($type_codes)->where('shop_id',$admin_auth['shop_id'])->count();

		$arr=[
			"rows"=>$data,
			"total"=>$count,
		];


			echo json_encode($arr);
     }
	
	//职员添加
	public  function clerk_add(){

		$admin_auth=Session::get('admin_auth') ;
		
		//验证
		$datavalidate=[
			'clerk_user'=>input("post.clerk_user"),
			'clerk_pass'=>input("post.clerk_pass"),
			"clerk_name"=> input("post.clerk_name"),
			"clerk_phone"=> input("post.clerk_phone"),
			"clerk_healthy_card"=> input("post.clerk_healthy_card"),
		];
		
		$validate =	validate('ClerkValidate');
		 if (!$validate->scene('Clerk_add')->check($datavalidate)) {
            exit(json_encode(array('status'=>-1,'msg'=>$validate->getError())));
         }
		 
		 
		$user_info=db('account')->where('admin_user',$datavalidate['clerk_user'])->find();
		if($user_info){
			die(json_encode(array('status'=>3,'msg'=>'用户名字已存在')));
		}

		$Account =model('AccountModel');
		
Db::startTrans();
try{	
        //验证  add 添加用户
        $admin_pwd_salt=random(10);
         $user_data =[
             'admin_uid'=>UUID(),
			 'shop_id'=>$admin_auth['shop_id'],
             'admin_user'=>$datavalidate['clerk_user'],
             'admin_pwd_salt'=>$admin_pwd_salt,
             'admin_pass'=>encrypt_password($datavalidate['clerk_pass'],$admin_pwd_salt)
         ];

		$info=$Account->save($user_data);
	
	
		db('auth_group_access')->insert(['uid'=>$Account->admin_id,'group_id'=>6]);	//权限



		$data=[
			"ks"=> input("post.ks"),
			"clerk_office_id"=> input("post.clerk_office_id"),
			"clerk_office_num"=> "ZST".time(),
			"clerk_name"=> input("post.clerk_name"),
			"clerk_sex"=> input("post.clerk_sex"),
			"clerk_phone"=> input("post.clerk_phone"),
			"clerk_place"=> input("post.clerk_place"),
			"clerk_card"=> input("post.clerk_card"),
			"clerk_birth_date"=> input("post.clerk_birth_date"),
			"clerk_education"=> input("post.clerk_education"),
			"clerk_specialty"=> input("post.clerk_specialty"),	
			"clerk_healthy"=> input("post.clerk_healthy"),
			"clerk_healthy_card"=> input("post.clerk_healthy_card"),
			"clerk_healthy_carddate"=> input("post.clerk_healthy_carddate"),
			"clerk_office_name"=> input("post.clerk_office_name"),	
			"clerk_office_type"=> input("post.clerk_office_type"),
			"clerk_text"=> input("post.clerk_text"),
			"admin_id"=>$Account->admin_id,
			'shop_id'=>$admin_auth['shop_id'],
			"creator"=> $admin_auth['user_name'],
			"creator_atime"=>time()//创建人时间
			
		];

		  $Clerk =model('Clerk');
		  $info=$Clerk->save($data);
			
				//	提交事务
				Db::commit();
				
		if($info!==false) 
			echo(json_encode(array('status'=>1,'msg'=>'成功')));
		else
			echo(json_encode(array('status'=>2,'msg'=>'添加失败请联系管理员')));


				
}	catch	(\Exception	$e)	{
				//	回滚事务
				Db::rollback();
}
		
    }
	//修改状态 开启
	public function clerk_upd_code(){
		
		$ids=input('post.ids/a');	
	
		if(empty($ids)){
			die();
		}
		foreach($ids as $k=>$v){
			db('clerk')->where('clerk_id',$v)->update(['clerk_status'=>1]);
		}
	}
	//修改状态 关闭
	public function clerk_upd_disable(){
		$ids=input('post.ids/a');	
	
		if(empty($ids)){
			die();
		}
		foreach($ids as $k=>$v){
			db('clerk')->where('clerk_id',$v)->update(['clerk_status'=>0]);
		}
	}
	//修改
	public function clerk_upd(){
		
		
		
		$admin_auth=Session::get('admin_auth') ;
		$ClerkLogic = new ClerkLogic;
		
		$clerk_id=input("post.clerk_id");	
		$user=input("post.clerk_user");
		$pass=input("post.clerk_pass");

		//user pass  判断
        $return = $ClerkLogic->username($clerk_id,$user,$pass);  
				
		if($return['status']==-3){
			die(json_encode(array('status'=>-1,'msg'=>'用户名字已存在')));
		}
			

//验证
		$datavalidate=[
			'clerk_user'=>input("post.clerk_user"),
			'clerk_pass'=>input("post.clerk_pass"),
			"clerk_name"=> input("post.clerk_name"),
			"clerk_phone"=> input("post.clerk_phone"),
			"clerk_healthy_card"=> input("post.clerk_healthy_card"),
		];
		
		$validate =	validate('ClerkValidate');
		 if (!$validate->scene('Clerk_add')->check($datavalidate)) {
            exit(json_encode(array('status'=>-1,'msg'=>$validate->getError())));
         }





			
		$data=[
			"clerk_office_id"=> input("post.clerk_office_id"),
			"is_code"=> input("post.is_code"),
			"clerk_office_num"=> input("post.clerk_office_num"),
			"clerk_name"=> input("post.clerk_name"),
			"clerk_sex"=> input("post.clerk_sex"),
			"clerk_phone"=> input("post.clerk_phone"),
			"clerk_place"=> input("post.clerk_place"),
			"clerk_card"=> input("post.clerk_card"),
			"clerk_birth_date"=> input("post.clerk_birth_date"),
			"clerk_education"=> input("post.clerk_education"),
			"clerk_specialty"=> input("post.clerk_specialty"),	
			"clerk_healthy"=> input("post.clerk_healthy"),
			"clerk_healthy_card"=> input("post.clerk_healthy_card"),
			"clerk_healthy_carddate"=> input("post.clerk_healthy_carddate"),
			"clerk_office_name"=> input("post.clerk_office_name"),	
			"ks"=> input("post.ks"),	
			"clerk_office_type"=> input("post.clerk_office_type"),
			"clerk_text"=> input("post.clerk_text"),
			"upd_man"=> $admin_auth['user_name'],//修改人 
			"upd_man_stime"=>time()//创建人修改时间	
		];
		
		
		  $Clerk =model('Clerk');
		
Db::startTrans();
try{
				  $info=$Clerk->save($data,['clerk_id'=>$clerk_id]);
				//	提交事务
				Db::commit();		
				echo(json_encode(array('status'=>1,'msg'=>'修改成功')));				
}	catch	(\Exception	$e)	{
				//	回滚事务
				Db::rollback();
				 echo(json_encode(array('status'=>2,'msg'=>'修改失败请联系管理者')));
}
		
	
	
			

		
	}
//导出
	public  function dataToExcel(Request $request){
	
	
        $excel = new Api();
        $map='';
        $type_codes = input('get.type_codes');
		$type_code = input('get.type_code');
		
		$admin_auth=Session::get('admin_auth') ;

        if(empty($type_code)){
			$type_code=null;
		}else{
			if($type_code==2){
				$type_code=0;
			}
			$type_code=['clerk_status'=>$type_code];
		
		}
		if(empty($type_codes)){
			$type_codes=null;
		}else{
			if($type_codes==2){
				$type_codes=0;
			}
			$type_codes=['clerk_office_type'=>$type_codes];
		
		}
		$admin_auth=Session::get('admin_auth') ;
		$Clerk =model('Clerk');
		$data_zylx=db('zylx')->where('clinic_id',$admin_auth['shop_id'])->select();
	
		$oldsql="";
		foreach($data_zylx as $k=>$v){
		
			$oldsql.='when '.$v['id'].' then '."'".$v['zylx_name']."'".' ';
		}
		$oldfield="case clerk_office_id ".$oldsql." else '保密' end as clerk_office_id,";
		
	
		$field ="
		case clerk_healthy when 1 then '健康' when 2 then '一般' when 3 then '较差' else '保密' end as clerk_healthy,
		case clerk_education when 1 then '小学' when 2 then '初中' when 3 then '高中' when 4 then '大专' when 5 then '本科' when 6 then '硕士研究生' when 7 then '博士研究生' else '保密' end as clerk_education,
		
		".$oldfield."
		if(clerk_status=1,'已启动','已停用') as clerk_status,	
		if(clerk_sex=1,'男','女') as clerk_sex,
		if(clerk_office_type=1,'在职','离职') as clerk_office_type,
		if(is_code=1,'是','否') as is_code,
		FROM_UNIXTIME(upd_man_stime, '%Y-%m-%d') as upd_man_stime,
		FROM_UNIXTIME(creator_atime, '%Y-%m-%d') as creator_atime,
		FROM_UNIXTIME(clerk_healthy_carddate, '%Y-%m-%d') as clerk_healthy_carddate,
		FROM_UNIXTIME(clerk_birth_date, '%Y-%m-%d') as clerk_birth_date,
		clerk_name,clerk_office_num,clerk_phone,clerk_place,clerk_card,clerk_specialty,clerk_healthy_card,
		clerk_office_name,clerk_text,creator,upd_man
		";
		
		$excel_table=db('Clerk')->field($field)
					->where($type_code)
					->where($type_codes)
					->where('shop_id',$admin_auth['shop_id'])
					->order('clerk_status desc,clerk_office_type desc')
					->buildSql();
	
        $excel_field = [
			"clerk_status"  => "状态",
			"clerk_name" =>  "名字",
			"clerk_office_id"  => "职业类型",
			"clerk_office_num"  => "职员编码",		
			"clerk_phone"  => "电话",
			"clerk_sex"  => "性别",
			"clerk_place"  => "籍贯",
			 "clerk_card"   =>"身份证号",
			 "clerk_birth_date"  => "出生日期",
			"clerk_education"  => "学历",
			 "clerk_specialty"  => "专业",
			 "clerk_healthy"  => "健康情况",
			  "clerk_healthy_card"  => "健康证号",
			  "clerk_healthy_carddate" =>  "发放日期",
			  "clerk_office_name"  => "职称",
			  "clerk_office_type"  => "在职/离职",
			  "is_code"  =>  "是否是诊疗人",
			  "clerk_text"  => "备注",
			  "creator"  => "创建人",
			  "creator_atime"   =>"创建时间",
			  "upd_man"  => "最后修改人",			
			 "upd_man_stime" =>  "最后修改人时间",

        ];

        $fileName = '职员管理表' . time();
        $sheetNmae = '职员管理表';
        $excel->excelDown($excel_table,$excel_field,$map=[],$fileName,$sheetNmae,2);
   
		
	}
	
	
	
	
	}
