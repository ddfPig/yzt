<?php
namespace app\index\controller;
use \think\Controller;
use	\think\Db;
use	\think\Session;
use think\Request;
use app\index\Validate\User;
class Clinicapi extends Home
// 诊所基础信息Api
{
    
    public function  upd(){ //修改基础信息
		//基本
       $data=[
        "shop_name"=>input("post.shop_name"),
        "licence"=>input("post.licence"),
        "shop_address"=>input("post.shop_address"),
        "leader"=>input("post.leader"),
        "create_time"=>input("post.create_time"),
        "busniss_date"=>input("post.busniss_date"),
        "bank_name"=>input("post.bank_name"),
        "bank_type"=>input("post.bank_type"),
        "bank_num"=>input("post.bank_num"),
        "contactor"=>input("post.contactor"),
        "mobile"=>input("post.mobile"),
        "shop_scope"=>input("post.shop_scope"),  
		"province"=>input("post.province"),
        "city"=>input("post.city"),
        "town"=>input("post.town"),  		
       ];
	   
	   $validate =	validate('User');
	   if (!$validate->scene('Clinicbase_upd')->check($data)) {
		   exit(json_encode(array('status'=>-1,'msg'=>$validate->getError())));
	   }   
	   
	
	   
	   
		$admin_auth=Session::get('admin_auth') ;
		$clinic_id=$admin_auth['shop_id'];
		if(!empty($data['busniss_date'])){
			$atime=strtotime($data['create_time']);
			$stime=strtotime($data['busniss_date']);
			if($atime>=$stime){
				 die(json_encode(array('status'=>-3,'msg'=>'营业期限请大于成立日期')));
			}
		}
		$arr=input('post.rows/a');	
		$count=count($arr);
		if(!empty($count)){
			foreach($arr as $k=>$v){
				if(empty($v['certificate_type'])){
					 die(json_encode(array('status'=>-2,'msg'=>'资质/证书不能为空')));
				}
				
				
				
				
				
				if($v['certificate_atime']&&$v['certificate_stime']){
					$atime=strtotime($v['certificate_atime']);
					$stime=strtotime($v['certificate_stime']);
					if($atime>=$stime){
						 die(json_encode(array('status'=>-4,'msg'=>'到期日期请大于发证日期')));
					}else{
						$arr[$k]['clinic_id']=$clinic_id;
						$arr[$k]['certificate_atime']=strtotime($v['certificate_atime']);
						$arr[$k]['certificate_stime']=strtotime($v['certificate_stime']);
					}
				}else{
					$arr[$k]['certificate_atime']=null;
					$arr[$k]['certificate_stime']=null;
				}
				
				
			}
		}
		
		$data['create_time']=strtotime($data['create_time']);
		
		
	$insertinfo=input("post.insertinfo");
	$deleteinfo=input("post.deleteinfo");
	$updateinfo=input("post.updateinfo");
	

 $info=db('shop')->where('shop_id',$clinic_id)->update($data);
	
		// Db::startTrans();
		// try{
			
			
			if(!empty($insertinfo)){
                $insert_data = json_decode($insertinfo,true);	
                $indata = [];
                foreach ($insert_data as $v){
					if(!isset($v['certificate_name'])){
						$v['certificate_name']=null;
					}
                    $sdata = [
                        'clinic_id' =>$clinic_id,
						
						'certificate_type' => $v['certificate_type'],
                        'certificate_num'=>$v['certificate_num'],
                        'organization'=>$v['organization'],
                        'certificate_atime'=>strtotime($v['certificate_atime']),                   
                        'certificate_stime'=>strtotime($v['certificate_stime']),
                        'scope'=>$v['scope'],
                        'certificate_name'=>$v['certificate_name'],
                        'certificate_identity'=>$v['certificate_identity'],
                        'certificate_contacts'=>$v['certificate_contacts'],
                        'imgurl'=>$v['imgurl'],
                        'certificate_test'=>$v['certificate_test'],
                    ];
                    $indata[] = $sdata;
                }
                $res = Db::name('clinic_certificate')->insertAll($indata);
				
            }
			
			
			

            //2更新原有数据
            if(!empty($updateinfo)){
                $update_data = json_decode($updateinfo,true);
                foreach ($update_data as $vt){
					if(!isset($v['certificate_name'])){
						$v['certificate_name']=null;
					}
					if(isset($vp['id'])){
						break;
					}
                    $store_data = [
                        'certificate_type'=>$vt['certificate_type'],
                        'certificate_num'=>$vt['certificate_num'],
                        'organization'=>$vt['organization'],
                        'certificate_atime'=>strtotime($vt['certificate_atime']),
                        'certificate_stime'=>strtotime($vt['certificate_stime']),
                        'scope'=>$vt['scope'],
                        'certificate_name'=>$vt['certificate_name'],
                        'certificate_identity'=>$vt['certificate_identity'],
                        'certificate_contacts'=>$vt['certificate_contacts'],
                        'imgurl'=>$vt['imgurl'],
                        'certificate_test'=>$vt['certificate_test'],
                    ];
                    Db::name('clinic_certificate')->where('id',$vt['id'])->update($store_data);
					
					
                }

            }

            //删除数据
            if(!empty($deleteinfo)){
                $update_data = json_decode($deleteinfo,true);
				
                foreach ($update_data as $vp){
					if(isset($vp['id'])){
						break;
					}
                    Db::name('clinic_certificate')->where('id',$vp['id'])->delete();
                }
				
            }
		
			
			die(json_encode(array('status'=>1,'msg'=>'更新成功')));
		
				
					
					
					
					
					
						// //	提交事务
						// Db::commit();				
		// }	catch	(\Exception	$e)	{
						// //	回滚事务
						// Db::rollback();
		// }
	
       
    }
	//资质查询
    public function Clinic_zz_list(){

   
        $admin_auth=Session::get('admin_auth') ;
        $clinic_id=$admin_auth['shop_id'];
        $data=db('clinic_certificate')->where('clinic_id',$clinic_id)->order('id desc')->select();
        foreach($data as $k=>$v){
			if($v['certificate_atime']==0){
				$data[$k]['certificate_atime']=null;
			}else{
				 $data[$k]['certificate_atime']=date('Y-m-d',$v['certificate_atime']);
			}
			if($v['certificate_stime']==0){
				$data[$k]['certificate_stime']=null;
			}else{
				 $data[$k]['certificate_stime']=date('Y-m-d',$v['certificate_stime']);
			}
            
        }

        echo json_encode($data);

    }

    
    public  function zz_lists(){//弹窗赋值 查询
	
		$admin_auth=Session::get('admin_auth') ;
		$keyword=input('get.keyword');
			
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
	
		$start = ($page-1)*$rows;
		$shop=$admin_auth['shop_id'];
		if($keyword){
			$where['zzgl_name']=['like',$keyword];
				
		}else{
			$where=null;
		}
		$where['clinic_id'] = $shop;
		$where['zzgl_state'] = 1;
        //资质
      
         $data=db('dic_zzgl')->where('clinic_id',$shop)->where('zzgl_state',1)->where($where)->limit($start,$rows)->select();
         $count=db('dic_zzgl')->where('clinic_id',$shop)->where('zzgl_state',1)->where($where)->limit($start,$rows)->count();
		
		
       	$arr=[
			"rows"=>$data,
			"total"=>$count,
		];
        echo json_encode($data);
    }
}
