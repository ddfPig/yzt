<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Cure extends Home
{
	//诊疗管理-患者档案管理
    public function patient()
    {
		$this->assign([
			'ver'	=> 'zhensuo',
		
		]);
		return view();
    }
	
	//诊疗管理-患者档案管理-搜索
	public function patient_search(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$start = ($page-1)*$rows;
		if($keyword){
			$data = Db::name('patient')->where('name','like',"%$keyword%")->where('clinic_id',$clinic_id)->order('id','desc')->limit($start,$rows)->select();
			$total = Db::name('patient')->where('name','like',"%$keyword%")->where('clinic_id',$clinic_id)->count();
		}else{
			$data = Db::name('patient')->where('clinic_id',$clinic_id)->order('id','desc')->limit($start,$rows)->select();
			$total = Db::name('patient')->where('clinic_id',$clinic_id)->count();
		}
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	
	//诊疗管理-患者档案管理-新建患者档案页面
    public function patient_add()
    {
		
		//$ver = Request::instance()->param('ver');
		$clinic_id = session('admin_auth.shop_id');
		
		//自动生成患者编号
		$row1 = Db::name('patient')->where('clinic_id',$clinic_id)->count();
		$patient_id = 'P'.time().rand(1,9).sprintf('%04s',$row1+1);
		$file_id = 'F'.time().rand(1,9).sprintf('%04s',$row1+1);
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'patient_id'	=> $patient_id,
			'file_id'		=> $file_id,
		
		]);
		return view();
    }
	
	//诊疗管理-患者档案管理-新建患者档案接口
    public function patient_add_api()
    {
		$data = Request::instance()->param();
		$data['clinic_id'] = 1;
		$data['createname'] = session('admin_auth.user_name');
		$data['createdate'] = date('Y-m-d');
		$card = $data['card'];
		unset($data['card']);
		$id = Db::name('patient')->insertGetId($data);
		if($id){
			//入诊疗卡与患者绑定表
			if($card){
				$da['vip_num'] = $card;
				$da['patient_id'] = $id;
				$da['patient_number'] = $data['patient_number'];
				$id = Db::name('vip')->insertGetId($da);
				if($id){
					//更新诊疗卡为已使用
					Db::name('cards')->where('card',$card)->update(['status'=>1]);
				}
			}
			return json(array('status'=>1));
		}
		
    }
	
	
	//诊疗管理-患者档案管理-编辑患者档案页面
    public function patient_edit()
    {
		$clinic_id = session('admin_auth.shop_id');
		
		$id = Request::instance()->param('id');
		
		$data = Db::name('patient')->find($id);
		$vip = Db::name('vip')->where('patient_id',$id)->find();
		if($vip){
			$data['card'] = $vip['vip_num'];
			$data['card_choose'] = 0;
		}else{
			$data['card'] = "";
			$data['card_choose'] = 1;
		}
		
		$this->assign([
			'ver'	=> 'zhensuo',
			'data'	=> $data,
			'id'	=> $id
		]);
		return view();
    }

	//诊疗管理-患者档案管理-编辑患者档案接口
    public function patient_edit_api()
    {
		$id = Request::instance()->param('id');
		$data = Request::instance()->param();
		
		$data['modifyname'] = session('admin_auth.user_name');
		$data['modifydate'] = date('Y-m-d');
		$card = $data['card'];
		unset($data['card']);
		$re = Db::name('patient')->where('id',$id)->update($data);
		if($re!==false){
			//入诊疗卡与患者绑定表
			if($card){
				$da['vip_num'] = $card;
				$da['patient_id'] = $id;
				$da['patient_number'] = $data['patient_number'];
				$id = Db::name('vip')->insertGetId($da);
				if($id){
					//更新诊疗卡为已使用
					Db::name('cards')->where('card',$card)->update(['status'=>1]);
				}
			}
			return json(array('status'=>1));
		}
		
    }

	
	//诊疗管理-患者档案管理-导出
	public function patient_out(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		
		$where = array();
		$where['clinic_id'] = $clinic_id;
		
		if($keyword){
			$where['name'] = ['like',"%$keyword%"];
		}

		$a = new Api();
        $excel_field = [
            'patient_number'=>'患者编号',
            'name'=>'姓名',
            'sex'=>'性别',
            'age'=>'年龄',
            'phone'=>'手机号',
            'inheritance'=>'遗传史',
            'chronic'=>'慢性病',
            'allergy'=>'过敏史',
            'serious'=>'大病史',
            'medical_insurance'=>'医保',
            'disease_situation'=>'病症情况',
            'first_cure'=>'首次诊疗日期',
            'last_cure'=>'最后诊疗日期',
            'createdate'=>'建档日期',
            'createname'=>'建档人',
            'modifydate'=>'最后修改日期',
            'modifyname'=>'最后修改人',
            'file_number'=>'档案编号',
            'remarks'=>'备注',
        ];
        $fileName = '患者档案表_' . time();
        $sheetNmae = '患者档案表';
        $a->excelDown('zst_patient',$excel_field,$where,$fileName,$sheetNmae,1);
	}
	
	//诊疗管理-患者档案管理-患者诊疗卡接口
	public function patient_card_api(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$where = array();
		$where['shop_id'] = $clinic_id;
		$where['status'] = 0;
		
		if($keyword){
			$where['card'] = ['like',"%$keyword%"];
		}
		$start = ($page-1)*$rows;
		
		$data = Db::name('cards')->where($where)->order('id','desc')->limit($start,$rows)->select();
		$total = Db::name('cards')->where($where)->count();
		
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
		
	}
	public function win_card(){
		return view();
	}
	
	
	
	
	
	
	
	
	//诊疗管理-诊疗记录管理
    public function acography()
    {
		$clinic_id = session('admin_auth.shop_id');
		
		//查询诊疗人列表
		$result = Db::name('clerk')->field('clerk_id as id,clerk_name as name')->where('shop_id',$clinic_id)->where('is_code',1)->select();
		$this->assign([
			'ver'	=> 'zhensuo',
			'result'=> $result
		
		]);
		return view();
    }
	
	//诊疗管理-诊疗记录管理-搜索
	public function acography_search(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$confirm_id = Request::instance()->param('confirm_id');
		$status = Request::instance()->param('status');
		$start_date = Request::instance()->param('start_date');
		$end_date = Request::instance()->param('end_date');
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$start = ($page-1)*$rows;
		
		$where = array();
		$where['clinic_id'] = $clinic_id;
		if($keyword){
			$where['name'] = ['like',"%$keyword%"];
		}
		if($confirm_id>-1){
			$where['confirm_id'] = $confirm_id;
		}
		if($status>-1){
			$where['status'] = $status;
		}
		if($start_date && !$end_date){
			$where['save_date'] = ['> time',$start_date];
		}
		if(!$start_date && $end_date){
			$where['save_date'] = ['< time',$end_date];
		}
		if($start_date && $end_date){
			$where['save_date'] = ['between time',[$start_date,$end_date]];
		}
		
		$data = Db::name('acography')->where($where)->order('id','desc')->limit($start,$rows)->select();
		$total = Db::name('acography')->where($where)->count();
		
		$status_arr = array('待确认','待收款','已完成');
		//$department_arr = array(1=>'中医内科',2=>'中医外科');
		//诊疗科室
		$department_id = Db::name('zlksgl')->field('id,zlksgl_name as name')->where('clinic_id',$clinic_id)->where('zlksgl_state',1)->select();
		$department_arr[0] = '未选择';
		foreach($department_id as $v){
			$department_arr[$v['id']] = $v['name'];
		}
		
		//诊疗结果
		$result_id = Db::name('dic_zljggl')->field('id,zljggl_name as name')->where('clinic_id',$clinic_id)->where('zljggl_state',1)->select();
		$result_arr[0] = '未选择';
		foreach($result_id as $v){
			$result_arr[$v['id']] = $v['name'];
		}
		
		//查询诊疗人列表
		$result = Db::name('clerk')->field('clerk_id as id,clerk_name as name')->where('shop_id',$clinic_id)->select();
		$confirm_arr = array();
		foreach($result as $v){
			$confirm_arr[$v['id']] = $v['name'];
		}
		$i = 0;
		foreach($data as $v){
			$data[$i]['status'] = $status_arr[$v['status']];
			$data[$i]['department_id'] = $department_arr[$v['department_id']];
			$data[$i]['result'] = $result_arr[$v['result']];
			if($v['confirm_id']){
				$data[$i]['confirm_name'] = $confirm_arr[$v['confirm_id']];
			}else{
				$data[$i]['confirm_name'] = '-';
				$data[$i]['confirm_date'] = '-';
			}
			
			
			$i++;
		}
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	
	//诊疗管理-诊疗记录管理-新建诊疗记录页面
    public function acography_add()
    {
		$clinic_id = session('admin_auth.shop_id');
		//诊疗科室
		$department_id = Db::name('zlksgl')->field('id,zlksgl_name as name')->where('clinic_id',$clinic_id)->where('zlksgl_state',1)->select();
		
		//诊疗结果
		$result_id = Db::name('dic_zljggl')->field('id,zljggl_name as name')->where('clinic_id',$clinic_id)->where('zljggl_state',1)->select();
		
		//自动生成诊疗记录编号
		$row = Db::name('acography')->where('clinic_id',$clinic_id)->count();
		$record_id = 'A'.time().rand(1,9).sprintf('%04s',$row+1);
		
		//自动生成患者编号
		$row1 = Db::name('patient')->where('clinic_id',$clinic_id)->count();
		$patient_id = 'P'.time().rand(1,9).sprintf('%04s',$row1+1);
		
		//$ver = Request::instance()->param('ver');
		$this->assign([
			'ver'			=> 'zhensuo',
			'department_id'	=> $department_id,
			'result_id'		=> $result_id,
			'record_id'		=> $record_id,
			'patient_id'	=> $patient_id,
		
		]);
		return view();
    }
	
	//诊疗管理-诊疗记录管理-新建诊疗记录接口
    public function acography_add_api()
    {
		$data = Request::instance()->param();
		$details = $data['details'];
		unset($data['details']);
		$arr = json_decode($details,true);
		
		
		$clinic_id = session('admin_auth.shop_id');
		$data['clinic_id'] = $clinic_id;
		
		//查询患者是否存在
		$result = Db::name('patient')->where('clinic_id',$clinic_id)->where('patient_number',$data['patient_number'])->find();
		if(!$result){
			$row1 = Db::name('patient')->where('clinic_id',$clinic_id)->count();
			$file_id = 'F'.time().rand(1,9).sprintf('%04s',$row1+1);
			//不存在则创建患者
			$arr_p['clinic_id'] = $data['clinic_id'];
			$arr_p['patient_number'] = $data['patient_number'];
			$arr_p['name'] = $data['name'];
			$arr_p['sex'] = $data['sex'];
			$arr_p['age'] = $data['age'];
			$arr_p['phone'] = $data['phone'];
			$arr_p['file_number'] = $file_id;
			$arr_p['createname'] = session('admin_auth.user_name');
			$arr_p['createdate'] = date('Y-m-d');
			$arr_p['first_cure'] = date('Y-m-d');
			$arr_p['last_cure'] = date('Y-m-d');
			Db::name('patient')->insertGetId($arr_p);
		}
		$data['save_id'] = session('admin_auth.clerk_id');
		$data['save_date'] = date('Y-m-d');
		$id = Db::name('acography')->insertGetId($data);
		if($id){
			//更新诊疗时间
			$arr_cure['last_cure'] = date('Y-m-d');
			if(!$result['first_cure']){
				$arr_cure['first_cure'] = date('Y-m-d');
			}
			Db::name('patient')->where('patient_number',$data['patient_number'])->update($arr_cure);
			
			if($arr[0]['classify_id']){
				$i = 0;
				foreach($arr as $v){
					$arr1['acography_id'] = $id;
					$arr1['medicine_id'] = $v['id'];
					$arr1['number'] = $v['count'];
					Db::name('acography_medicine')->insert($arr1);
					$i++;
				}
			}
			return json(array('status'=>1,'id'=>$id));
		}else{
			return json(array('status'=>0,'message'=>'失败'));
		}
		
    }
	
	
	//诊疗管理-诊疗记录管理-编辑诊疗记录页面
    public function acography_edit()
    {
		$clinic_id = session('admin_auth.shop_id');
		
		$id = Request::instance()->param('id');
		
		$data = Db::name('acography')->find($id);
		//诊疗科室
		$department_id = Db::name('zlksgl')->field('id,zlksgl_name as name')->where('clinic_id',$clinic_id)->where('zlksgl_state',1)->select();
		//诊疗结果
		$result_id = Db::name('dic_zljggl')->field('id,zljggl_name as name')->where('clinic_id',$clinic_id)->where('zljggl_state',1)->select();
		//查询诊疗人列表
		$result = Db::name('clerk')->field('clerk_id as id,clerk_name as name,clerk_office_id as role')->where('shop_id',$clinic_id)->select();
		//查询职位类型
		$type = Db::name('zylx')->field('id,zylx_name as name')->where('clinic_id',$clinic_id)->select();
		$clerk_arr = array();
		$type_arr = array();
		$type_arr[0] = '';
		foreach($type as $v){
			$type_arr[$v['id']] = $v['name'];
		}
		foreach($result as $v){
			$role = $v['role'];
			$clerk_arr[$v['id']] = $v['name'].'（'.$type_arr[$role].'）';
		}
		if($data['save_id']){
			$data['save_name'] = $clerk_arr[$data['save_id']];
		}
		if($data['confirm_id']){
			$data['confirm_name'] = $clerk_arr[$data['confirm_id']];
		}
		if($data['payee_id']){
			$data['payee_name'] = $clerk_arr[$data['payee_id']];
		}
		$this->assign([
			'ver'			=> 'zhensuo',
			'data'			=> $data,
			'id'			=> $id,
			'department_id'	=> $department_id,
			'result_id'		=> $result_id
		]);
		return view();
    }

	//诊疗管理-诊疗记录管理-编辑诊疗记录接口
    public function acography_edit_api()
    {
		$aid = Request::instance()->param('id');
		$data = Request::instance()->param();
		
		$deleteinfo = $data['deleteinfo'];
		$insertinfo = $data['insertinfo'];
		$updateinfo = $data['updateinfo'];
		unset($data['deleteinfo']);
		unset($data['insertinfo']);
		unset($data['updateinfo']);
		$arr_del = json_decode($deleteinfo,true);
		$arr_ins = json_decode($insertinfo,true);
		$arr_upd = json_decode($updateinfo,true);
		
		$id = Db::name('acography')->where('id',$aid)->update($data);
		
		/*
		
		$arr1['acography_id'] = $id;
				$arr1['medicine_id'] = $v['id'];
				$arr1['number'] = $v['count'];
				Db::name('acography_medicine')->insert($arr1);
				
				*/
		//插入
		if($arr_ins){
			$i = 0;
			foreach($arr_ins as $v){
				if(isset($v['sp_name'])){
					$arr_ins_new['acography_id'] = $aid;
					$arr_ins_new['medicine_id'] = $v['medicine_id'];
					$arr_ins_new['number'] = $v['number'];
					Db::name('acography_medicine')->insert($arr_ins_new);
				}
				$i++;
			}
		}
		//更新
		if($arr_upd){
			foreach($arr_upd as $v){
				$arr_ins_new['id'] = $v['id'];
				$arr_ins_new['medicine_id'] = $v['medicine_id'];
				$arr_ins_new['number'] = $v['number'];
				Db::name('acography_medicine')->update($arr_ins_new);
			}
		}
		//删除
		if($arr_del){
			foreach($arr_del as $v){
				Db::name('acography_medicine')->delete($v['id']);
			}
		}
		
		if($id!==false){
			return json(array('status'=>1));
		}
		
    }

	//诊疗管理-诊疗记录管理-诊疗记录药品接口
    public function acography_medicine_api()
    {
		$id = Request::instance()->param('id');
		//$data = Db::name('acography_medicine')->where('acography_id',$id)->select();
		$total = Db::name('acography_medicine')->where('acography_id',$id)->count();
		
		$field = 'a.number,b.*,a.id,a.medicine_id,c.sp_name,d.dwgl_name,e.jxgl_name';
		$data = Db::name('acography_medicine')->field($field)->alias('a')
				->join('zst_medicine b','a.medicine_id=b.id','lEFT')
				->join('zst_spfl c','c.id=b.classify_id','lEFT')
				->join('zst_dwgl d','d.id=b.unit_id','lEFT')
				->join('zst_jxgl e','e.id=b.dosage_id','lEFT')
                ->where('a.acography_id',$id)
                ->select();
		
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
    }
	
	//诊疗管理-诊疗记录管理-诊疗记录确认
	public function acography_confirm_api(){
		$id = Request::instance()->param('id');
		$data['confirm_id'] = session('admin_auth.clerk_id');
		$data['confirm_date'] = date('Y-m-d');
		$data['status'] = 1;
		$id = Db::name('acography')->where('id',$id)->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
	}
	
	
	//诊疗管理-诊疗记录管理-诊疗记录结算页面
    public function acography_pay()
    {
		$clinic_id = session('admin_auth.shop_id');
		
		$id = Request::instance()->param('id');
		
		$data = Db::name('acography')->find($id);
		//查询诊疗人列表
		$result = Db::name('clerk')->field('clerk_id as id,clerk_name as name,clerk_office_id as role')->where('shop_id',$clinic_id)->select();
		//查询职位类型
		$type = Db::name('zylx')->field('id,zylx_name as name')->where('clinic_id',$clinic_id)->select();
		//查询收款方式
		$payee = Db::name('skfs')->field('id,skfs_name as name')->where('clinic_id',$clinic_id)->where('skfs_state',1)->select();
		
		$clerk_arr = array();
		$type_arr = array();
		$type_arr[0] = '';
		
		foreach($type as $v){
			$type_arr[$v['id']] = $v['name'];
		}
		foreach($result as $v){
			$role = $v['role'];
			$clerk_arr[$v['id']] = $v['name'].'（'.$type_arr[$role].'）';
		}
		if($data['save_id']){
			$data['save_name'] = $clerk_arr[$data['save_id']];
		}
		if($data['confirm_id']){
			$data['confirm_name'] = $clerk_arr[$data['confirm_id']];
		}else{
			$data['confirm_name'] = '';
		}
		if($data['payee_id']){
			$data['payee_name'] = $clerk_arr[$data['payee_id']];
		}
		
		//查询药品价格计算总价
		$field = 'a.number,b.*,a.id as medicine_id,c.sp_name,d.dwgl_name,e.jxgl_name';
		$price_arr = Db::name('acography_medicine')->field($field)->alias('a')
				->join('zst_medicine b','a.medicine_id=b.id','lEFT')
				->join('zst_spfl c','c.id=b.classify_id','lEFT')
				->join('zst_dwgl d','d.id=b.unit_id','lEFT')
				->join('zst_jxgl e','e.id=b.dosage_id','lEFT')
                ->where('a.acography_id',$id)
                ->select();
		
		$total_price = 0;
		$i = 0;
		foreach($price_arr as $v){
			
			$total_price = $total_price+$v['number']*$v['price'];
			$price_arr[$i]['number'] = sprintf("%.2f",$v['number']);
			$price_arr[$i]['price'] = sprintf("%.2f",$v['price']);
			$i++;
			
		}
		if($data['status']!=2){
			$data['receive_able'] = sprintf("%.2f",$total_price);
			$data['receive_real'] = sprintf("%.2f",$total_price);
		}
		$date = date('Y-m-d');
		$this->assign([
			'ver'	=> 'zhensuo',
			'data'	=> $data,
			'id'	=> $id,
			'date'	=> $date,
			'payee'	=> $payee,
		]);
		return view();
    }
	//诊疗管理-诊疗记录管理-诊疗记录结算接口
    public function acography_pay_api()
    {
		$id = Request::instance()->param('id');
		$para = Request::instance()->param();
		$details = Request::instance()->param('details');
		$arr = json_decode($details,true);
		
		$data = Db::name('acography')->find($id);
		if($data['status']==1){
			//修改药品记录
			if($arr){
				foreach($arr as $v){
					$a['number'] = $v['number'];
					$a['price'] = $v['price'];
					$a['total'] = $v['total'];
					Db::name('acography_medicine')->where('id',$v['medicine_id'])->update($a);
				}
			}
			//修改记录
			$b['status'] = 2;
			$b['receive_able'] = $para['receive_able'];
			$b['receive_real'] = $para['receive_real'];
			$b['payee_type'] = $para['payee_type'];
			$b['payee_id'] = session('admin_auth.clerk_id');
			$b['payee_date'] = date('Y-m-d');
			
			//查询收款方式
			$pay_type = Db::name('skfs')->where('id',$para['payee_type'])->find();
			if($pay_type['skfs_name']!='记账'){
				$b['is_pay'] = 1;
			}
			if($pay_type['skfs_name']=='余额'){
				$vip = Db::name('vip')->where('patient_number',$data['patient_number'])->find();
				if(!$vip){
					return json(array('status'=>0,'message'=>'不可使用余额支付'));
				}
				if($vip['money']<$para['receive_real']){
					return json(array('status'=>0,'message'=>'余额不足'));
				}
			}
			$result = Db::name('acography')->where('id',$id)->update($b);
			if($result!==false){
				if($pay_type['skfs_name']=='记账'){
					//入欠款表
					$c['clinic_id'] = session('admin_auth.shop_id');
					$c['arrears_type'] = 1;
					$c['create_time'] = time();
					$c['arrears_time'] = time();
					$c['arrears_money'] = $para['receive_real'];
					$c['arrears_person'] = $para['name'];
					$c['doc_id'] = $id;
					$c['arrears_nums'] = $this->getTableSn('QK','arrears_nums','zst_arrears');
					Db::name('arrears')->insert($c);
				}
				if($pay_type['skfs_name']=='余额'){
					$up['money'] = $vip['money']-$para['receive_real'];
					Db::name('vip')->where('patient_number',$data['patient_number'])->update($up);
				}
				return json(array('status'=>1));
			}else{
				return json(array('status'=>0,'message'=>'失败'));
			}
		}else{
			return json(array('status'=>0,'message'=>'操作有误'));
		}
		
	}
	
	//诊疗管理-诊疗记录管理-诊疗卡余额查询
	public function acography_card_balance(){
		$id = Request::instance()->param('id');//患者编号
		if($id){
			$result = Db::name('vip')->where('patient_number',$id)->find();
			if($result){
				return json(array('status'=>1,'balance'=>sprintf("%.2f",$result['money'])));
			}
			return json(array('status'=>0,'message'=>'还未绑定诊疗卡'));
		}
		return json(array('status'=>0,'message'=>'操作有误'));
	}
	
	
	
	 public  function  getTableSn($prefix,$field,$table)
    {
        $res=Db::query("select count(DISTINCT $field) as count from  $table ");
        if($res){
            $sn=$prefix.date('Ymd').str_pad($res[0]['count']+1,5,0,STR_PAD_LEFT);

        }else{
            $sn=$prefix.date('Ymd').str_pad(1,5,0,STR_PAD_LEFT);
        }
        return $sn;
    }
	//诊疗管理-诊疗记录管理-收款页面药品列表
	public function acography_pay_search(){
		
		$clinic_id = session('admin_auth.shop_id');
		
		$id = Request::instance()->param('id');
		
		//查询药品价格计算总价
		$field = 'a.number,b.*,a.acography_id as id,a.id as medicine_id,c.sp_name,d.dwgl_name,e.jxgl_name';
		$data = Db::name('acography_medicine')->field($field)->alias('a')
				->join('zst_medicine b','a.medicine_id=b.id','lEFT')
                ->where('a.acography_id',$id)
				->join('zst_spfl c','c.id=b.classify_id','lEFT')
				->join('zst_dwgl d','d.id=b.unit_id','lEFT')
				->join('zst_jxgl e','e.id=b.dosage_id','lEFT')
                ->select();
		$total = Db::name('acography_medicine')->where('acography_id',$id)->count();
		//$total_price = 0;
		$i = 0;
		foreach($data as $v){
			
			$data[$i]['total'] = sprintf("%.2f",$v['number']*$v['price']);
			$data[$i]['number'] = sprintf("%.2f",$v['number']);
			$data[$i]['price'] = sprintf("%.2f",$v['price']);
			$i++;
			
		}
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	
	
	
	
	//诊疗管理-诊疗记录管理-选择患者页面
    public function win_patient()
    {
		return view();
    }
	//诊疗管理-诊疗记录管理-选择患者页面
    public function win_medicine()
    {
		return view();
    }
	
	
	
	//诊疗管理-诊疗记录管理-导出
	public function acography_out(){
		
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$confirm_id = Request::instance()->param('confirm_id');
		$status = Request::instance()->param('status');
		$start_date = Request::instance()->param('start_date');
		$end_date = Request::instance()->param('end_date');
		
		
		$where = array();
		if($keyword){
			$where['a.name'] = ['like',"%$keyword%"];
		}
		if($confirm_id>-1){
			$where['a.confirm_id'] = $confirm_id;
		}
		if($status>-1){
			$where['a.status'] = $status;
		}
		if($start_date && !$end_date){
			$where['a.save_date'] = ['> time',$start_date];
		}
		if(!$start_date && $end_date){
			$where['a.save_date'] = ['< time',$end_date];
		}
		if($start_date && $end_date){
			$where['a.save_date'] = ['between time',[$start_date,$end_date]];
		}
		
		$a = new Api();
        $excel_field = [
            'status'=>'诊疗状态',
            'department_id'=>'诊疗科室',
            'number'=>'诊疗记录编号',
            'patient_number'=>'患者编号',
            'name'=>'姓名',
            'sex'=>'性别',
            'age'=>'年龄',
            'disease'=>'基本病症',
            'content'=>'问诊内容',
            'result'=>'诊疗结果',
            'confirm_name'=>'诊疗人',
            'confirm_date'=>'诊疗时间',
            'remarks'=>'备注',
        ];
		$field = "a.number,a.patient_number,a.name,a.sex,a.age,a.disease,a.content,a.result,a.confirm_date,a.remarks,b.zlksgl_name as department_id,c.clerk_name as confirm_name,case a.status when 0 then '待确认' when 1 then '待收款' when 2 then '已完成' end as status";
		$table = Db::name('acography')->field($field)->alias('a')
                             ->join('zst_zlksgl b','a.department_id=b.id','LEFT')
                             ->join('zst_clerk c','a.confirm_id=c.clerk_id','LEFT')
                             ->where($where)
                             ->buildSql();


        $fileName = '诊疗记录表_' . time();
        $sheetNmae = '诊疗记录表';
        $a->excelDown($table,$excel_field,$where=[],$fileName,$sheetNmae,2);
	}
	
}
