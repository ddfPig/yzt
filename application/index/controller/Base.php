<?php
namespace app\index\controller;
use \think\Controller;
use think\Request;
use think\Db;
use think\Py;

class Base extends Home
{
	//基础管理-供应商管理
    public function supplier()
    {
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		$ver = Request::instance()->param('ver');
		
		//$business_type = array(array('id'=>1,'name'=>'生产企业'),array('id'=>2,'name'=>'经营企业'));
		//供货商类型
		$business_type = Db::name('ghss')->field('id,ghss_name as name')->where('clinic_id',$clinic_id)->where('ghss_state',1)->select();
		
		//有效期天数
		$expiretime = Db::name('dic_cshsz')->field('id,cshsz_jxqts as name')->where('clinic_id',$clinic_id)->find();
		if($expiretime){
			$expiretime['name'] = $expiretime['name']/86400;
		}
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'business_type'	=> $business_type,
			'expiretime'	=> $expiretime,
		
		]);
		return view();
    }
	
	
	//基础管理-供应商管理-搜索
	public function supplier_search()
	{
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$credentials = Request::instance()->param('credentials');
		$business = Request::instance()->param('business');
		$supply = Request::instance()->param('supply');
		
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$start = ($page-1)*$rows;
		
		$where['clinic_id'] = $clinic_id;
		if($keyword){
			$where['supplier_name'] = ['like',"%$keyword%"];
		}
		if($business>-1){
			$where['business_type'] = $business;
		}
		if($supply>-1){
			$where['supply_type'] = $supply;
		}
		$time = time();
		$expiretime = Db::name('dic_cshsz')->field('cshsz_jxqts')->where('clinic_id',$clinic_id)->find();
		$count = $time+$expiretime['cshsz_jxqts'];
		if($credentials>-2){//联合资质到期时间表查询
			switch($credentials){
				//未过期
				case -1:$where['expiretime'] = ['GT',$count];break;
				
				//已过期
				case 0:$where['expiretime'] = ['LT',$time];break;
				
				//根据id查询过期时间
				default: $where['expiretime'] = ['between',"$time,$count"];break;
				
			}
			//$where['check_type'] = $credentials;
		}
		//正常查询
		$data = Db::name('supplier')->where($where)->order('id','desc')->limit($start,$rows)->select();
		$total = Db::name('supplier')->where($where)->count();
		
		//供货商类型
		$business_data = Db::name('ghss')->field('id,ghss_name as name')->where('clinic_id',$clinic_id)->select();
		$business_type[0] = '未选择';
		foreach($business_data as $v){
			$business_type[$v['id']] = $v['name'];
		}
		
		
		if($data){
			$i = 0;
			foreach($data as $v){
				$data[$i]['province'] = $v['province_show'];
				$data[$i]['city'] = $v['city_show'];
				$data[$i]['area'] = $v['area_show'];
				$data[$i]['check_type'] = $v['check_type']==0?'已保存':'已提交';
				$data[$i]['supply_type'] = $v['supply_type']==0?'正常供货':'暂停供货';
				$data[$i]['business_type'] = $business_type[$v['business_type']];
				if($v['expiretime']>$count){
					$data[$i]['expiretime'] = '合格供货商';
				}elseif($v['expiretime']>=$time && $v['expiretime']<=$count){
					$data[$i]['expiretime'] = '资质即将过期';
				}else{
					$data[$i]['expiretime'] = '资质已过期';
				}
				$i++;
			}
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	//基础管理-供应商管理-新建供应商页面
    public function supplier_add()
    {
		$clinic_id = session('admin_auth.shop_id');
		//供货商类型
		$business_type = Db::name('ghss')->field('id,ghss_name as name')->where('clinic_id',$clinic_id)->where('ghss_state',1)->select();
		
		//$ver = Request::instance()->param('ver');
		
		//自动生成供货商编号
		$row1 = Db::name('supplier')->where('clinic_id',$clinic_id)->count();
		$supplier_id = 'S'.time().rand(1,9).sprintf('%04s',$row1+1);
		$file_id = 'F'.time().rand(1,9).sprintf('%04s',$row1+1);
		
		$provincelist=Db::name('city')->where('type',0)->order('orderNo')->select();
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'business_type'	=> $business_type,
			'supplier_id'	=> $supplier_id,
			'file_id'		=> $file_id,
			'provincelist'	=> $provincelist
		
		]);
		return view();
    }
	
	//基础管理-供应商管理-新建供应商接口
    public function supplier_add_api()
    {
		$data = Request::instance()->param();
		$details = $data['details'];
		unset($data['details']);
		$arr = json_decode($details,true);
		
		$data['clinic_id'] = session('admin_auth.shop_id');
		
		if(!$data['supplier_name']){
			return json(array('status'=>0,'message'=>'请填写供应商名称'));
		}
		
		if(!$data['business_type']){
			return json(array('status'=>0,'message'=>'请选择单位类型'));
		}
		
		//查询是否有重名
		$result = Db::name('supplier')->where('supplier_name',$data['supplier_name'])->find();
		if($result){
			return json(array('status'=>0,'message'=>'供应商名称重复'));
		}
		//查询省市区
		$province = Db::name('city')->field('Name')->where('ID',$data['province'])->find();
		$city = Db::name('city')->field('Name')->where('ID',$data['city'])->find();
		$area = Db::name('city')->field('Name')->where('ID',$data['area'])->find();
		
		$data['province_show'] = $province['Name'];
		$data['city_show'] = $city['Name'];
		$data['area_show'] = $area['Name'];
		
		$id = Db::name('supplier')->insertGetId($data);
		if($arr){
			$i = 0;
			foreach($arr as $v){
				$arr[$i]['supplierid'] = $id;
				if(isset($arr[$i]['certname'])){
					$arr[$i]['createtime'] = strtotime($v['createtime']);
					$arr[$i]['expiretime'] = strtotime($v['expiretime']);
					Db::name('suppliercert')->insert($arr[$i]);
				}
				if(isset($arr[$i]['FileUrl'])){
					$arr[$i]['file'] = $v['FileUrl'];
					unset($arr[$i]['FileUrl']);
				}
				$i++;
			}
		}
		//查询最近到期时间，更新到供应商表
		$cert = Db::name('suppliercert')->where(array('expiretime' => array('GT', 0)))->where('supplierid',$id)->order('id asc')->find();
		if($cert){
			Db::name('supplier')->where('id',$id)->update(['expiretime'=>$cert['expiretime']]);
		}
		
		if($id){
			return json(array('status'=>1,'id'=>$id));
		}else{
			return json(array('status'=>0,'message'=>'失败'));
		}
    }

	//基础管理-供应商管理-供应商接口
    public function supplier_commit_api()
    {
		$id = Request::instance()->param('id');
		$data['check_type'] = 1;
		$id = Db::name('supplier')->where('id',$id)->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
    }
	
	//基础管理-供应商管理-修改供应商接口
    public function supplier_supply_api()
    {
		$id = Request::instance()->param('id');
		$type = Request::instance()->param('type');
		$data['supply_type'] = $type;
		$id = Db::name('supplier')->where('id',$id)->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
    }
	
	//基础管理-供应商管理-编辑供应商页面
    public function supplier_edit()
    {
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		//供货商类型
		$business_type = Db::name('ghss')->field('id,ghss_name as name')->where('clinic_id',$clinic_id)->where('ghss_state',1)->select();
		
		
		$id = Request::instance()->param('id');
		$data = Db::name('supplier')->find($id);
		$certdata = Db::name('suppliercert')->where('supplierid',$id)->select();
		
		
		$provincelist=Db::name('city')->where('type',0)->order('orderNo')->select();
		$citylist=Db::name('city')->where('ParentID',$data['province'])->order('orderNo')->select();
		$arealist=Db::name('city')->where('ParentID',$data['city'])->order('orderNo')->select();
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'business_type'	=> $business_type,
			'data'			=> $data,
			'certdata'		=> $certdata,
			'id'			=> $id,
			'provincelist'	=> $provincelist,
			'citylist'		=> $citylist,
			'arealist'		=> $arealist
		
		]);
		return view();
    }
	
	//基础管理-供应商管理-修改供应商接口
    public function supplier_edit_api()
    {
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
		
		//查询省市区
		$province = Db::name('city')->field('Name')->where('ID',$data['province'])->find();
		$city = Db::name('city')->field('Name')->where('ID',$data['city'])->find();
		$area = Db::name('city')->field('Name')->where('ID',$data['area'])->find();
		
		$data['province_show'] = $province['Name'];
		$data['city_show'] = $city['Name'];
		$data['area_show'] = $area['Name'];
		
		$id = Db::name('supplier')->where('id',$data['id'])->update($data);
		
		//插入
		if($arr_ins){
			$i = 0;
			foreach($arr_ins as $v){
				if($v['certname']){
					unset($arr_ins[$i]['index']);
					unset($arr_ins[$i]['row']);
					$arr_ins[$i]['supplierid'] = $data['id'];
					if(isset($arr_ins[$i]['createtime'])){
						$arr_ins[$i]['createtime'] = strtotime($arr_ins[$i]['createtime']);
					}
					if(isset($arr_ins[$i]['expiretime'])){
						$arr_ins[$i]['expiretime'] = strtotime($arr_ins[$i]['expiretime']);
					}
					if(isset($arr_ins[$i]['FileUrl'])){
						$arr_ins[$i]['file'] = $arr_ins[$i]['FileUrl'];
					}
					unset($arr_ins[$i]['FileUrl']);
					Db::name('suppliercert')->insert($arr_ins[$i]);
				}
				$i++;
			}
		}
		//更新
		if($arr_upd){
			foreach($arr_upd as $v){
				if($v['createtime']){
					$v['createtime'] = strtotime($v['createtime']);
				}
				if($v['expiretime']){
					$v['expiretime'] = strtotime($v['expiretime']);
				}
				if($v['FileUrl']){
					$v['file'] = $v['FileUrl'];
					unset($v['FileUrl']);
				}
				$upd_info = Db::name('suppliercert')->find($v['id']);
				Db::name('suppliercert')->update($v);
				if($v['FileUrl']){
					if($upd_info['file'] != $v['file']){
						unlink('.'.$upd_info['file']);
					}
				}
			}
		}
		//删除
		if($arr_del){
			foreach($arr_del as $v){
				$del_info = Db::name('suppliercert')->find($v['id']);
				Db::name('suppliercert')->delete($v['id']);
				if($del_info['file']){
					unlink('.'.$del_info['file']);
				}
			}
		}
		
		//查询最近到期时间，更新到供应商表
		$cert = Db::name('suppliercert')->where(array('expiretime' => array('GT', 0)))->where('supplierid',$data['id'])->order('id asc')->find();
		if($cert){
			Db::name('supplier')->where('id',$data['id'])->update(['expiretime'=>$cert['expiretime']]);
		}
		if($id!==false){
			return json(array('status'=>1));
		}
    }

	//基础管理-供应商管理-编辑供应商页面证书接口
    public function supplier_cert_api()
    {
		$id = Request::instance()->param('id');
		$data = Db::name('suppliercert')->where('supplierid',$id)->select();
		$total = Db::name('suppliercert')->where('supplierid',$id)->count();
		
		$i = 0;
		foreach($data as $v){
			$data[$i]['createtime'] = $v['createtime']==0?'无':date('Y-m-d',$v['createtime']);
			$data[$i]['expiretime'] = $v['expiretime']==0?'无':date('Y-m-d',$v['expiretime']);
			$data[$i]['FileUrl'] = $v['file'];
			$i++;
		}
		if($data){
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
    }
	
	
	//基础管理-供货商管理-选择证书页面
    public function win_cert()
    {
		$ver = Request::instance()->param('ver');
		$this->assign([
			'ver'	=> 'zhensuo',
		
		]);
		return view();
    }
	
	//基础管理-供货商管理-选择证书接口
	public function supplier_cert(){
		
		$clinic_id = session('admin_auth.shop_id');
		//适用诊疗范围
		//$scope = array(array('id'=>1,'name'=>'内科'),array('id'=>2,'name'=>'外科'),array('id'=>2,'name'=>'骨科'),array('id'=>2,'name'=>'儿科'));
		$scope = Db::name('dic_zzgl')->field('id,zzgl_name as certname')->where('clinic_id',$clinic_id)->where('zzgl_state',1)->select();
		$total = Db::name('dic_zzgl')->field('id,zzgl_name as certname')->where('clinic_id',$clinic_id)->where('zzgl_state',1)->count();
		
		if($scope){
			return json(array('total'=>$total,'rows'=>$scope));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	
	//基础管理-供货商管理-导出
	public function supplier_out(){
			
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$credentials = Request::instance()->param('credentials');
		$business = Request::instance()->param('business');
		$supply = Request::instance()->param('supply');
		
		
		$where = array();
		$where['a.clinic_id'] = $clinic_id;
		
		if($keyword){
			$where['a.supplier_name'] = ['like',"%$keyword%"];
		}
		if($business>-1){
			$where['a.business_type'] = $business;
		}
		if($supply>-1){
			$where['a.supply_type'] = $supply;
		}
		$time = time();
		$expiretime = Db::name('dic_cshsz')->field('cshsz_jxqts')->where('clinic_id',$clinic_id)->find();
		$count = $time+$expiretime['cshsz_jxqts'];
		if($credentials>-2){//联合资质到期时间表查询
			switch($credentials){
				//未过期
				case -1:$where['a.expiretime'] = ['GT',$count];break;
				
				//已过期
				case 0:$where['a.expiretime'] = ['LT',$time];break;
				
				//根据id查询过期时间
				default: $where['a.expiretime'] = ['between',"$time,$count"];break;
				
			}
			
		}

		$a = new Api();
        $excel_field = [
            'check_type'=>'状态',
            'expiretime'=>'资质状态',
            'supply_type'=>'供货状态',
            'business_type'=>'单位类型',
            'supplier_code'=>'编码',
            'supplier_name'=>'名称',
            'business_license'=>'营业执照号',
            'province'=>'省份',
            'city'=>'县市',
            'area'=>'地区',
            'file_number'=>'档案编号',
        ];
		$field = "a.province_show as province,a.city_show as city,a.area_show as area,a.supplier_code,a.supplier_name,a.business_license,a.file_number,b.ghss_name as business_type,if(a.check_type=0,'已保存','已提交') as check_type,if(a.supply_type=0,'正常供货','暂停供货') as supply_type,case when a.expiretime>{$count} then '合格供货商' when a.expiretime between {$time} and {$count} then '资质即将过期' else '资质已过期' end as expiretime";
		$table = Db::name('supplier')->field($field)->alias('a')
                             ->join('zst_ghss b','a.business_type=b.id','LEFT')
                             ->where($where)
                             ->buildSql();


        $fileName = '供货商表_' . time();
        $sheetNmae = '供货商表';
        $a->excelDown($table,$excel_field,$where=[],$fileName,$sheetNmae,2);
	}
	
	
	
	
	
	

	//基础管理-药品管理
    public function medicine()
    {
		$clinic_id = session('admin_auth.shop_id');
		//药品分类
		//$classify_id = array(array('id'=>1,'name'=>'中药'),array('id'=>2,'name'=>'西药'));
		$classify_id = Db::name('spfl')->field('id,sp_name as name')->where('clinic_id',$clinic_id)->where('sp_state',1)->select();
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'classify_id'	=> $classify_id,
		]);
		return view();
    }
	
	//基础管理-药品管理-搜索
	public function medicine_search(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$classify = Request::instance()->param('classify');
		$check = Request::instance()->param('check');
		$supply = Request::instance()->param('supply');
		
		$page = Request::instance()->param('page');
		$rows = Request::instance()->param('rows');
		
		$start = ($page-1)*$rows;
		
		$where['clinic_id'] = $clinic_id;
		if($keyword){
			$where['uname|name|scode'] = ['like',"%$keyword%"];
		}
		if($classify>-1){
			$where['classify_id'] = $classify;
		}
		if($check>-1){
			$where['check_type'] = $check;
		}
		if($supply>-1){
			$where['sales_type'] = $supply;
		}
		$data = Db::name('medicine')->where($where)->order('id','desc')->limit($start,$rows)->select();
		$total = Db::name('medicine')->where($where)->count();
		
		//药品分类
		//$classify_id = array(1=>'中药',2=>'西药');
		$classify_data = Db::name('spfl')->field('id,sp_name as name')->where('clinic_id',$clinic_id)->select();
		$classify_id[0] = '未选择';
		foreach($classify_data as $v){
			$classify_id[$v['id']] = $v['name'];
		}
		//单位
		//$unit_id = array(1=>'盒',2=>'桶',3=>'袋');
		$unit_data = Db::name('dwgl')->field('id,dwgl_name as name')->where('clinic_id',$clinic_id)->select();
		$unit_id[0] = '未选择';
		foreach($unit_data as $v){
			$unit_id[$v['id']] = $v['name'];
		}
		//剂型
		//$dosage_id = array(1=>'片剂',2=>'胶囊剂');
		$dosage_data = Db::name('jxgl')->field('id,jxgl_name as name')->where('clinic_id',$clinic_id)->select();
		$dosage_id[0] = '未选择';
		foreach($dosage_data as $v){
			$dosage_id[$v['id']] = $v['name'];
		}
		//商品效期（月）
		//$effect_id = array(1=>12,2=>18,3=>24);
		$effect_data = Db::name('yxq')->field('id,yxq_month as name')->where('clinic_id',$clinic_id)->select();
		$effect_id[0] = '未选择';
		foreach($effect_data as $v){
			$effect_id[$v['id']] = $v['name'];
		}
		//处方药
		//$prescription = array(1=>'值1',2=>'值2');
		$prescription_data = Db::name('dic_cfy')->field('id,cfy_name as name')->where('clinic_id',$clinic_id)->select();
		$prescription[0] = '未选择';
		foreach($prescription_data as $v){
			$prescription[$v['id']] = $v['name'];
		}
		
		if($data){
			$i = 0;
			foreach($data as $v){
				$data[$i]['classify_id'] = $classify_id[$v['classify_id']];
				$data[$i]['unit_id'] = $unit_id[$v['unit_id']];
				$data[$i]['dosage_id'] = $dosage_id[$v['dosage_id']];
				$data[$i]['effect_id'] = $effect_id[$v['effect_id']];
				$data[$i]['prescription'] = $effect_id[$v['prescription']];
				$data[$i]['special'] = $v['special']==0?'否':'是';
				$i++;
			}
			return json(array('total'=>$total,'rows'=>$data));
		}else{
			return json(array('total'=>0,'rows'=>array()));
		}
	}
	
	
	//基础管理-药品管理-新建药品页面
    public function medicine_add()
    {
		$clinic_id = session('admin_auth.shop_id');
		
		//药品分类
		//$classify_id = array(array('id'=>1,'name'=>'中药'),array('id'=>2,'name'=>'西药'));
		$classify_id = Db::name('spfl')->field('id,sp_name as name')->where('clinic_id',$clinic_id)->where('sp_state',1)->select();
		//单位
		//$unit_id = array(array('id'=>1,'name'=>'盒'),array('id'=>2,'name'=>'桶'),array('id'=>2,'name'=>'袋'));
		$unit_id = Db::name('dwgl')->field('id,dwgl_name as name')->where('clinic_id',$clinic_id)->where('dwgl_state',1)->select();
		//剂型
		//$dosage_id = array(array('id'=>1,'name'=>'片剂'),array('id'=>2,'name'=>'胶囊剂'));
		$dosage_id = Db::name('jxgl')->field('id,jxgl_name as name')->where('clinic_id',$clinic_id)->where('jxgl_state',1)->select();
		//商品效期（月）
		//$effect_id = array(array('id'=>1,'name'=>'12'),array('id'=>2,'name'=>'18'),array('id'=>2,'name'=>'24'));
		$effect_id = Db::name('yxq')->field('id,yxq_month as name')->where('clinic_id',$clinic_id)->where('yxq_state',1)->select();
		//处方药
		//$prescription = array(array('id'=>1,'name'=>'值1'),array('id'=>2,'name'=>'值2'));
		$prescription = Db::name('dic_cfy')->field('id,cfy_name as name')->where('clinic_id',$clinic_id)->where('cfy_state',1)->select();
		//适用诊疗范围
		//$scope = array(array('id'=>1,'name'=>'内科'),array('id'=>2,'name'=>'外科'),array('id'=>2,'name'=>'骨科'),array('id'=>2,'name'=>'儿科'));
		$scope = Db::name('syzl')->field('id,syzl_name as name')->where('clinic_id',$clinic_id)->where('syzl_state',1)->select();
		
		
		//自动生成药品编号
		$row1 = Db::name('medicine')->where('clinic_id',$clinic_id)->count();
		$medicine_id = 'S'.time().rand(1,9).sprintf('%04s',$row1+1);
		$file_id = 'F'.time().rand(1,9).sprintf('%04s',$row1+1);
		
		//$ver = Request::instance()->param('ver');
		$this->assign([
			'ver'			=> 'zhensuo',
			'classify_id'	=> $classify_id,
			'unit_id'		=> $unit_id,
			'dosage_id'		=> $dosage_id,
			'effect_id'		=> $effect_id,
			'prescription'	=> $prescription,
			'scope'			=> $scope,
			'medicine_id'	=> $medicine_id,
			'file_id'		=> $file_id,
		
		]);
		return view();
    }
	
	//基础管理-药品管理-新建药品接口
    public function medicine_add_api()
    {
		$data = Request::instance()->param();
		$data['clinic_id'] = session('admin_auth.shop_id');
		$id = Db::name('medicine')->insertGetId($data);
		if($id){
			return json(array('status'=>1,'id'=>$id));
		}
    }
	
	//基础管理-药品管理-提交药品接口
    public function medicine_commit_api()
    {
		$id = Request::instance()->param('id');
		$data['check_type'] = session('admin_auth.shop_id');
		$id = Db::name('medicine')->where('id',$id)->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
    }
	
	//基础管理-药品管理-修改销售状态药品接口
    public function medicine_sales_api()
    {
		$id = Request::instance()->param('id');
		$type = Request::instance()->param('type');
		$data['sales_type'] = $type;
		$id = Db::name('medicine')->where('id',$id)->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
    }
	
	
	
	
	//基础管理-药品管理-诊疗范围页面
    public function win_scope()
    {
		$ver = Request::instance()->param('ver');
		$this->assign([
			'ver'	=> 'zhensuo',
		
		]);
		return view();
    }
	//基础管理-药品管理-返回诊疗范围接口
	public function medicine_scope_api(){
		
		$clinic_id = session('admin_auth.shop_id');
		//适用诊疗范围
		//$scope = array(array('id'=>1,'name'=>'内科'),array('id'=>2,'name'=>'外科'),array('id'=>2,'name'=>'骨科'),array('id'=>2,'name'=>'儿科'));
		$scope = Db::name('syzl')->field('id,syzl_name as name')->where('clinic_id',$clinic_id)->where('syzl_state',1)->select();
		$total = Db::name('syzl')->field('id,syzl_name as name')->where('clinic_id',$clinic_id)->where('syzl_state',1)->count();
		
		if($scope){
			return json(array('total'=>$total,'rows'=>$scope));
		}
	}
	
	
	
	//基础管理-药品管理-编辑药品页面
    public function medicine_edit()
    {
		
		$clinic_id = session('admin_auth.shop_id');
		//药品分类
		//$classify_id = array(array('id'=>1,'name'=>'中药'),array('id'=>2,'name'=>'西药'));
		$classify_id = Db::name('spfl')->field('id,sp_name as name')->where('clinic_id',$clinic_id)->where('sp_state',1)->select();
		//单位
		//$unit_id = array(array('id'=>1,'name'=>'盒'),array('id'=>2,'name'=>'桶'),array('id'=>2,'name'=>'袋'));
		$unit_id = Db::name('dwgl')->field('id,dwgl_name as name')->where('clinic_id',$clinic_id)->where('dwgl_state',1)->select();
		//剂型
		//$dosage_id = array(array('id'=>1,'name'=>'片剂'),array('id'=>2,'name'=>'胶囊剂'));
		$dosage_id = Db::name('jxgl')->field('id,jxgl_name as name')->where('clinic_id',$clinic_id)->where('jxgl_state',1)->select();
		//商品效期（月）
		//$effect_id = array(array('id'=>1,'name'=>'12'),array('id'=>2,'name'=>'18'),array('id'=>2,'name'=>'24'));
		$effect_id = Db::name('yxq')->field('id,yxq_month as name')->where('clinic_id',$clinic_id)->where('yxq_state',1)->select();
		//处方药
		//$prescription = array(array('id'=>1,'name'=>'值1'),array('id'=>2,'name'=>'值2'));
		$prescription = Db::name('dic_cfy')->field('id,cfy_name as name')->where('clinic_id',$clinic_id)->where('cfy_state',1)->select();
		//适用诊疗范围
		//$scope = array(array('id'=>1,'name'=>'内科'),array('id'=>2,'name'=>'外科'),array('id'=>2,'name'=>'骨科'),array('id'=>2,'name'=>'儿科'));
		$scope = Db::name('syzl')->field('id,syzl_name as name')->where('clinic_id',$clinic_id)->where('syzl_state',1)->select();
		
		
		$id = Request::instance()->param('id');
		$data = Db::name('medicine')->find($id);
		
		$this->assign([
			'ver'			=> 'zhensuo',
			'classify_id'	=> $classify_id,
			'unit_id'		=> $unit_id,
			'dosage_id'		=> $dosage_id,
			'effect_id'		=> $effect_id,
			'prescription'	=> $prescription,
			'scope'			=> $scope,
			'data'			=> $data,
			'id'			=> $id
		
		]);
		return view();
    }
	
	//基础管理-药品管理-修改药品接口
    public function medicine_edit_api()
    {
		$data = Request::instance()->param();
		
		$id = Db::name('medicine')->where('id',$data['id'])->update($data);
		if($id!==false){
			return json(array('status'=>1));
		}
    }
	
	//基础管理-药品管理-导出
	public function medicine_out(){
		
		//诊所编号
		$clinic_id = session('admin_auth.shop_id');
		
		//搜索关键词
		$keyword = Request::instance()->param('keyword');
		$classify = Request::instance()->param('classify');
		$check = Request::instance()->param('check');
		$supply = Request::instance()->param('supply');
		
		
		$where = array();
		$where['a.clinic_id'] = $clinic_id;
		
		if($keyword){
			$where['a.name'] = ['like',"%$keyword%"];
		}
		if($classify>-1){
			$where['a.classify_id'] = $classify;
		}
		if($check>-1){
			$where['a.check_type'] = $check;
		}
		if($supply>-1){
			$where['a.sales_type'] = $supply;
		}

		$a = new Api();
        $excel_field = [
            'classify_id'=>'商品分类',
            'code'=>'编码',
            'uname'=>'通用名称',
            'name'=>'商品名',
            'specification'=>'规格',
            'dosage_id'=>'剂型',
            'unit_id'=>'单位',
            'manufacturer'=>'生产厂商',
            'produce_place'=>'产地',
            'approval_number'=>'批准文号',
            'effect_id'=>'商品效期（月）',
            'box_number'=>'整箱数量',
            'bar_code'=>'条码',
            'prescription'=>'处方药',
            'special'=>'含特殊药品复方制剂',
            'archival_information'=>'档案编号',
        ];
		$field = "a.code,a.uname,a.name,a.specification,a.manufacturer,a.produce_place,a.approval_number,a.box_number,a.bar_code,a.archival_information,b.sp_name as classify_id,c.dwgl_name as unit_id,d.jxgl_name as dosage_id,e.yxq_month as effect_id,f.cfy_name as prescription,if(a.special=0,'否','是') as special";
		$table = Db::name('medicine')->field($field)->alias('a')
                             ->join('zst_spfl b','a.classify_id=b.id','LEFT')
                             ->join('zst_dwgl c','a.unit_id=c.id','LEFT')
                             ->join('zst_jxgl d','a.dosage_id=d.id','LEFT')
                             ->join('zst_yxq e','a.effect_id=e.id','LEFT')
                             ->join('zst_dic_cfy f','a.prescription=f.id','LEFT')
                             ->where($where)
                             ->buildSql();


        $fileName = '药品表_' . time();
        $sheetNmae = '药品表';
        $a->excelDown($table,$excel_field,$where=[],$fileName,$sheetNmae,2);
	}
	
	
	
	public function getpinyin(){
		//调用示例
		$py = new py();
		$str = Request::instance()->param('keyword');
		return json(array('str'=>$py->encode($str)));
	}
}
