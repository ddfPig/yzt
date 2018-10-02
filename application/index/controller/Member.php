<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use app\index\Validate\MemberValidate;
//会员充值
class Member extends Home
{
	
	

    //充值记录
    public function recharge_list()
    { 
	
	
		$arr=rule();//$arr['parent_rule'] 父级  $arr['rule'] 
		$admin_auth=Session::get('admin_auth') ;
		$pay_type=db('skfs')->where('clinic_id',$admin_auth['shop_id'])->where('skfs_state',1)->order('sort')->select();
		$is_start=db('dic_cshsz')->where('clinic_id',$admin_auth['shop_id'])->value('cshsz_kqczjl');
        $this->assign([
            'pay_type'=>$pay_type,
			'is_start'=>$is_start,
			'arr'=>$arr
        ]);
		
       return view();
    }
    //充值添加
    public function recharge_add()
    {        
		$admin_auth=Session::get('admin_auth') ;
		$pay_type=db('skfs')->where('clinic_id',$admin_auth['shop_id'])->where('skfs_state',1)->order('sort')->select();//支付方式
        $is_start=db('dic_cshsz')->where('clinic_id',$admin_auth['shop_id'])->find();//充值活动是否开启
        $this->assign([
            'pay_type'=>$pay_type,
            'is_start'=>$is_start['cshsz_kqczjl'],
            'clinic_id'=>$admin_auth['shop_id'],
        ]);
		
        return view();
    }
    //充值添加 选择会员
    public function win_vip_add(){
        return view();
    }
 //充值添加 选择会员 API
    public function  win_vip_add_search(){
        	//诊所编号
		$admin_auth=Session::get('admin_auth') ;
        $clinic_id=$admin_auth['shop_id'];
      
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$start = ($page-1)*$rows;
		if($keyword){
			$data = Db::name('vip')->alias('a')
			->join('zst_patient	b','b.id=a.patient_id')
			->where('b.name|b.phone|a.vip_num|a.patient_number','like',"%$keyword%")			
			->where('b.clinic_id',$clinic_id)
			->order('b.id','desc')
			->limit($start,$rows)
			->select();
			$total = Db::name('vip')->alias('a')
			->join('zst_patient	b','b.id=	a.patient_id')
			->where('b.name|b.phone|a.vip_num|b.patient_number','like',"%$keyword%")		
			->where('b.clinic_id',$clinic_id)
			->count();
		}else{
			$data = Db::name('vip')->alias('a')
			->join('zst_patient	b','b.id=a.patient_id') 
			->field('*,a.id as vipid')
			->where('b.clinic_id',$clinic_id)
			->order('b.id','desc')->limit($start,$rows)->select();
			$total = Db::name('vip')->alias('a')
			->join('zst_patient	b','b.id=a.patient_id')
			->where('b.clinic_id',$clinic_id)
			->count();
		}
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
    }
//充值保存 API
    public  function recharge_save(){
		$admin_auth=Session::get('admin_auth') ;
		$clinic_id=$admin_auth['shop_id'];
      
        $vipid=input('post.vipid');
        $patient_number=input('post.patient_number'); 
        $data_patient=db('patient')->where('patient_number',$patient_number)->where('clinic_id',$clinic_id)->find();   
        $data=[
            "vip_num"=>input('post.vip_num'), 
            "money"=>input('post.money'),
            "storage_money"=>input('post.storage_money'),
            "true_money"=>input('post.true_money'),
            "bestowal_money"=>input('post.bestowal_money'),
            "pay_type"=>input('post.pay_type'),
            "remark"=>input('post.remark'), 
            "recharge_date"=>time(),
            "creation"=>$admin_auth['user_name'],
            'storage_num'=>"CZ".time().rand(100,999),
            "creation_date"=>time()           
        ];
		
			$validate =	validate('MemberValidate');
		   if (!$validate->scene('Clinicbase_upd')->check($data)) {
			   exit(json_encode(array('status'=>-1,'msg'=>$validate->getError())));
		   }   
	
	
        Db::startTrans();
        try{
          
			$info=  Db::name('vip')->where('id',$vipid)->setInc('money',$data['true_money']);	
            Db::name('vip_recharge')->insert($data);
            //	提交事务
            Db::commit();	
            exit(json_encode(array('status'=>1,'msg'=>'充值成功')));
        }	catch	(\Exception	$e)	{
            //	回滚事务
            Db::rollback();
            exit(json_encode(array('status'=>-2,'msg'=>'充值失败请联系管理员')));
        }

    }
    //赠送金额判断API
    public function  bestowal_money(){
        $storage_money=input('post.storage_money/f');
		$storage_money = sprintf("%1\$.2f",$storage_money);
        $clinic_id=input('post.clinic_id');
        $is_start=input('post.is_start');
        
        $ids=null;
        $kk=0;
		$y=0;
        if(empty($is_start)){ //关闭
            echo sprintf("%1\$.2f",$y);
        }else{//开启

            $data=db('dic_reward')->where('clinic_id',$clinic_id)->where('reward_state',1)->select();
			$datacount=count($data);
			if(empty($datacount)){
				die('0');
			}
	
            foreach($data as $k=>$v){		
                if(sprintf("%1\$.2f",$v['reward_real'])<=$storage_money){
                    $ids[$kk]['reward_real']=sprintf("%1\$.2f",$v['reward_real']);
					 $ids[$kk]['reward_handsel']=sprintf("%1\$.2f",$v['reward_handsel']);
					 $kk++;
                }
            }
		
			$count=count($ids);
			if(empty($count)){
				 echo sprintf("%1\$.2f",$y);
			}else{
				foreach($ids as $k=>$v){
				  if(sprintf("%1\$.2f",$v['reward_real'])>$y){
					  $y=sprintf("%1\$.2f",$v['reward_handsel']);	 
				  }
				}
				   echo sprintf("%1\$.2f",$y);
			}
			
			
            
        }

       
    }
    //查询所有充值记录
    public function recharge_list_api(){
		$admin_auth=Session::get('admin_auth') ;
		$clinic_id=$admin_auth['shop_id'];
        $start_date = Request::instance()->param('start_date');
        $end_date = Request::instance()->param('end_date');
        $pay_type = Request::instance()->param('pay_type');
        $keyword = Request::instance()->param('keyword');
        $page = Request::instance()->param('page');
        $rows = Request::instance()->param('rows');

        $start = ($page-1)*$rows;
        $VipRecharge =model('VipRecharge');
        $where=null;


        if($pay_type!=-1&&!empty($pay_type)){
            $where['pay_type']=$pay_type;
        }
       
        if(!empty($keyword)){
            $where['a.vip_num']= ['like',"%$keyword%"];
        }
        if(!empty($start_date)){
            $where['a.recharge_date'] = ['>=', strtotime($start_date)];
        }
        if(!empty($end_date)){
            $where['a.recharge_date'] = ['<=', strtotime($end_date)];
        }
        // buildSql

        $data=$VipRecharge
		->alias('a')
        ->join('zst_vip	b','b.vip_num=	a.vip_num')
		->join('zst_patient	c','b.patient_id=c.id')
        ->field('*,b.money as summoney,a.money as money,b.id as vipid,a.id as id')
		->where($where)
		->where('c.clinic_id',$clinic_id)
		->order('a.recharge_date desc,a.id desc')
		->limit($start,$rows)
		->select();
  
        $count=$VipRecharge
		->alias('a')
        ->join('zst_vip	b','b.vip_num=	a.vip_num')
		->join('zst_patient	c','b.patient_id=c.id')
        ->field('*,b.money as summoney,a.money as money')
		->where($where)
		->where('c.clinic_id',$clinic_id)
		->order('a.recharge_date desc,a.id desc')
		->limit($start,$rows)
		->count();
		
		foreach($data as $k=>$v){			
			 $data[$k]['summoney']=sprintf("%1\$.2f",$v['summoney']);    
		}
		
		
		
        $arr=[
			"rows"=>$data,
			"total"=>$count,
		];
          echo json_encode($arr);
    }
    //修改查询记录
    public function recharge_upd(){
		$admin_auth=Session::get('admin_auth') ;  	
        $id = Request::instance()->param('id');
	
        $VipRecharge =model('VipRecharge');
        $data=$VipRecharge
			->alias('a')
			->join('zst_vip	b','b.vip_num=	a.vip_num')
			->join('zst_patient	c','b.patient_id=c.id')
			->field('*,b.money as summoney,a.money as money,b.id as vipid,a.id as id')
			->where('a.id',$id)
			->find();     
   
       	$pay_type=db('skfs')->where('clinic_id',$admin_auth['shop_id'])->where('skfs_state',1)->order('sort')->select();  
        $this->assign([
          'data'=>$data,  
          'pay_type'=>$pay_type,
        ]);
       return view();
    }

    //修改 API
    public function recharge_edit(){
	
      $admin_auth=Session::get('admin_auth') ;
		
      
		
        $id = Request::instance()->param('vipid');
        $pay_type = Request::instance()->param('pay_type');
        $remark = Request::instance()->param('remark');
        $data=[
            'pay_type'=>$pay_type,
            'upd_people'=>$admin_auth['user_name'],
            'upd_people_date'=>time(),
            'remark'=>$remark,
        ];

        $VipRecharge =model('VipRecharge');
        $info=$VipRecharge->save($data,['id'=>$id]);

        if($info!==false){
            echo 1;
        }



    }
}