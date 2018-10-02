<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 14:48
 */

namespace app\index\controller;


use app\index\Model\MedicineModel;
use app\index\Model\PurchaseModel;
use app\index\Model\StoreModel;
use app\index\Model\Supplier;
use app\index\Validate\Purcg;
use think\Controller;
use think\Db;
use think\Error;
use think\Request;

class Purchase extends Home
{
    protected $purchase;
    protected $supplyer;
    protected $med;
    protected $store;
    //protected $api;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->purchase = new PurchaseModel();
        $this->supplyer = new Supplier();
        $this->med = new MedicineModel();
        $this->store = new StoreModel();
        //$this->api = new Api();

        //$this->assign('yxq',$this->api->getEffect());
       // $this->assign('unit',$this->api->getUnit());
    }


    /**采购入库单列表
     * @param Request $request
     * @return mixed
     */
    public  function index(Request $request)
    {
        return $this->fetch();
    }

    /**采购入库单列表
     * @param Request $request
     * @return mixed
     */
    public  function lists(Request $request)
    {
        $map = [];
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['purnumbers|supplier_code|SupplyName|contactor|contactor_phone']=['like',$keys."%"];
        }

        $pay = $request->param('pay','');
        if($pay != ''){
            $map['isPay']= ['eq',$pay];
        }


        $page=$request->param('page/d','1');
        $limit=$request->param('rows/d','10');
        $page =$page-1;
        if($page>0){
            $page=$page*$limit;
        }

        $map['clinic_id']= ['eq',$this->zstUser['shop_id']];


        $count = $this->purchase->where($map)->count('pid');
        $list = $this->purchase->where($map)->order('daohuotime desc')->limit($page,$limit)->select();
        $result= [
            'rows'=>$list,
            'total'=>$count
        ];
        return $result;
    }

    /**采购入库添加页面
     * @return mixed
     */
    public function purAdd()
    {
        $this->assign('sn',$this->getTableSn('CG','purnumbers','zst_purchase'));
        return $this->fetch('addpur');
    }

    /**采购入库添加操作
     * @param Request $request
     * @return string
     */
    public function purInert(Request $request)
    {
         $data = $request->param();

          //验证数据

        $valdate = new Purcg();
        $val = $valdate->scene('add')->check($data);
        if(true !== $val){
            return json_encode(['code'=>0,'msg'=>$valdate->getError()]);
        }

        $store = json_decode($data['detail'],true);
        if(empty($store)){
            return json_encode(['code'=>0,'msg'=>'请填写入库药品明细单']);
        }else{
            //验证入库药品明细数据
            /*
            $val = $valdate->scene('store')->check($store);
            if(true !== $val){
                return json_encode(['code'=>0,'msg'=>$valdate->getError()]);
            }
            */
        }


       //exit;
         //生成采购订单数据
         $pur_data = [
             'puid'=>UUID(),
             'purnumbers'=>$data['purnumbers'],
             'create_time'=>strtotime($data['create_time']),
             'confirm_time'=>strtotime($data['confirm_time']),
             'supplier_code'=>$data['supplier_code'],
             'SupplyID'=>$data['SupplyID'],
             'purtotal'=>$data['purtotal'],
             'puryftotal'=>$data['puryftotal'],
             'daohuotime'=>strtotime($data['daohuotime']),
             'SHDH'=>$data['SHDH'],
             'contactor'=>$data['contacts_person'],
             'contactor_phone'=>$data['contacts_phone'],
             'info'=>$data['info'],
             'operater'=>$data['operater'],
             'SupplyName'=>$data['supplier_name'],
             'isqk'=>$data['isqk'],
             'clinic_id'=>$this->zstUser['shop_id'],
             'addtime'=>time(),
             'update_time'=>time(),
         ];

        //入库操作
       Db::startTrans();
       try{
           //采购
          $pid =  Db::name('purchase')->insertGetId($pur_data);

          if($pid && $data['isqk'] ==0){
              $this->runArrears($pid,$data['puryftotal'],$data['qkinfo']);
          }


           //生成采购单明细数据
           $stdata = [];
           foreach ($store as $v){
               $store_data = [
                   'stuid'=>UUID(),
                   'code'=>$v['code'],
                   'price'=>$v['price'],
                   'zl_status'=>$v['zl_status'],
                   'LastPrice'=>$v['LastPrice']>0?$v['LastPrice']:$v['price'],
                   'MoneyAccount'=>$v['MoneyAccount'],
                   'MoneyAccount2'=>$v['MoneyAccount2'],
                   'pnumber'=>$v['pnumber'],
                   'Piece'=>$v['Piece'],
                   'innumber'=>$v['innumber'],
                   'pihao'=>$v['pihao'],
                   'yxqz'=>strtotime($v['yxqz']),

                   'clinic_id'=>$this->zstUser['shop_id'],
                   'SupplyID'=>$data['SupplyID'],
                   'puid'=>$pid,
                   'purnumbers'=>$data['purnumbers'],
                   'medid'=>$v['id'],
                   'create_time'=>time(),
                   'update_time'=>time(),
               ];
               $stdata[] = $store_data;
           }
          $res = Db::name('store')->insertAll($stdata);

          if($pid && $res ){
              Db::commit();
              return  json_encode(['code'=>1,'msg'=>'保存采购入库成功','pid'=>$data['purnumbers']]);
          }

       }catch (\Exception $e){
           Db::rollback();
           return json_encode(['code'=>0,'msg'=>'保存采购入库出现问题，请查阅文档'.$e->getMessage()]);
       }
    }

    /**采购单修改
     * @param Request $request
     * @return mixed
     */
    public function purEdit(Request $request)
    {
        //获取采购数据
        $pid = $request->param('pid');
        $purData = $this->purchase->where(array('purnumbers'=>$pid))
                                  ->where('clinic_id',$this->zstUser['shop_id'])
                                  ->find();
        $qk = Db::name('arrears')->field('info')->where('doc_id',$purData['pid'])
                                       ->where('clinic_id',$this->zstUser['shop_id'])
                                       ->find();
        $this->assign('qkinfo',$qk);
        $this->assign('pur',$purData);
        return $this->fetch('purEdit');
    }

    /**修改采购单下的数据
     * @param Request $request
     */
    public function getStore(Request $request)
    {
        $pid = $request->param('pid');


        $count = $this->store->alias('a')
                                ->join('zst_medicine b','a.medid=b.id')
                                ->join('zst_yxq c','b.effect_id=c.yxq_id','LEFT')
                                ->join('zst_dwgl d','b.unit_id=d.id','LEFT')
                                ->join('zst_jxgl e','b.dosage_id=e.jxgl_id','LEFT')
                                ->where(array('purnumbers'=>$pid))
                                ->where('a.clinic_id',$this->zstUser['shop_id'])
                             ->count('store_id');

        $field = "a.pnumber,a.price,a.MoneyAccount,a.innumber,a.MoneyAccount2,a.pihao,b.code,b.uname,b.name,b.approval_number,b.specification,b.box_number,c.yxq_month,d.dwgl_name,e.jxgl_name,FROM_UNIXTIME(a.yxqz, '%Y-%m-%d') as yxqz,a.zl_status,a.LastPrice,a.Piece,a.store_id";
        $list = $this->store->alias('a')->field($field)
                            ->join('zst_medicine b','a.medid=b.id')
                            ->join('zst_yxq c','b.effect_id=c.yxq_id','LEFT')
                            ->join('zst_dwgl d','b.unit_id=d.id','LEFT')
                            ->join('zst_jxgl e','b.dosage_id=e.jxgl_id','LEFT')
                            ->where(array('purnumbers'=>$pid))
                            ->where('a.clinic_id',$this->zstUser['shop_id'])
                            ->select();
        $result= [
            'rows'=>$list,
            'total'=>$count
        ];
        return $result;


    }

    /**修改采购订单操作
     * @param Request $request
     * @return string
     */
    public function purUpdate(Request $request)
    {
        $data = $request->param();
        $store = json_decode($data['detail'],true);
        //验证数据

        $valdate = new Purcg();
        $val = $valdate->scene('edit')->check($data);
        if(true !== $val){
            return json_encode(['code'=>0,'msg'=>$valdate->getError()]);
        }

        $store = json_decode($data['detail'],true);
        if(empty($store)){
            return json_encode(['code'=>0,'msg'=>'请填写入库药品明细单']);
        }




        //生成采购订单数据
        $pur_data = [
            //'pid'=>$data['pid'],
            'puid'=>UUID(),
            'purnumbers'=>$data['purnumbers'],
            'create_time'=>strtotime($data['create_time']),
            'confirm_time'=>strtotime($data['confirm_time']),
            'supplier_code'=>$data['supplier_code'],
            'SupplyID'=>$data['SupplyID'],
            'purtotal'=>$data['purtotal'],
            'puryftotal'=>$data['puryftotal'],
            'daohuotime'=>strtotime($data['daohuotime']),
            'SHDH'=>$data['SHDH'],
            'contactor'=>$data['contacts_person'],
            'contactor_phone'=>$data['contacts_phone'],
            'info'=>$data['info'],
            'operater'=>$data['operater'],
            'SupplyName'=>$data['supplier_name'],
            'clinic_id'=>$this->zstUser['shop_id'],
            'addtime'=>time(),
            'update_time'=>time(),
        ];




        //入库操作
        Db::startTrans();
        try{
            //采购
            $res =  Db::name('purchase')->where('pid',$data['pid'])->update($pur_data);

            //添加新数据
            if(isset($data['insertinfo']) && !empty($data['insertinfo'])){
                $insert_data = json_decode($data['insertinfo'],true);
                $indata = [];
                foreach ($insert_data as $v){
                    $store_data = [
                        'stuid'=>UUID(),
                        'code'=>$v['code'],
                        'price'=>$v['price'],
                        'zl_status'=>$v['zl_status'],
                        'LastPrice'=>$v['LastPrice']>0?$v['LastPrice']:$v['price'],
                        'MoneyAccount'=>$v['MoneyAccount'],
                        'MoneyAccount2'=>$v['MoneyAccount2'],
                        'pnumber'=>$v['pnumber'],
                        'Piece'=>$v['Piece'],
                        'innumber'=>$v['innumber'],
                        'pihao'=>$v['pihao'],
                        'yxqz'=>strtotime($v['yxqz']),
                        'clinic_id'=>$this->zstUser['shop_id'],
                        'SupplyID'=>$data['SupplyID'],
                        'puid'=>$data['pid'],
                        'purnumbers'=>$data['purnumbers'],
                        'medid'=>$v['id'],
                        'create_time'=>time(),
                        'update_time'=>time(),
                    ];
                    $indata[] = $store_data;
                }
                //var_dump($indata);exit;
                $res2 = Db::name('store')->insertAll($indata);

            }

            //更新原有数据
            if(isset($data['updateinfo']) && !empty($data['updateinfo'])){
                $update_data = json_decode($data['updateinfo'],true);
                foreach ($update_data as $vt){
                    $store_data = [
                        'price'=>$vt['price'],
                        'zl_status'=>$vt['zl_status'],
                        'LastPrice'=>$vt['LastPrice']>0?$vt['LastPrice']:$vt['price'],
                        'MoneyAccount'=>$vt['MoneyAccount'],
                        'MoneyAccount2'=>$vt['MoneyAccount2'],
                        'pihao'=>$vt['pihao'],
                        'yxqz'=>strtotime($vt['yxqz']),
                        'pnumber'=>$vt['pnumber'],
                        'Piece'=>$vt['Piece'],
                        'innumber'=>$vt['innumber'],
                    ];
                    Db::name('store')->where('store_id',$vt['store_id'])->update($store_data);
                }
            }

            //删除数据

            if(isset($data['deleteinfo']) && !empty($data['deleteinfo'])){
                $update_data = json_decode($data['deleteinfo'],true);
                foreach ($update_data as $vp){
                    Db::name('store')->where('store_id',$vp['store_id'])->delete();
                }
            }



            if($res){
                Db::commit();
                return  json_encode(['code'=>1,'msg'=>'保存采购成功']);
            }

        }catch (\Exception $e){
            Db::rollback();
            return json_encode(['code'=>0,'msg'=>'保存采购出现问题,请查阅文档'.$e->getMessage()]);
        }
    }

    /**确认入库
     * @param Request $request
     */
    public function runConfirm(Request $request)
    {
         $purnumbers = $request->param('purnumbers');

         $isIn = Db::name('purchase')->where('purnumbers',$purnumbers)->value('purstatus');
         if($isIn ==1){
             return json_encode(['code'=>0,'msg'=>'该订单已入库,不要重复入库']);
         }
         Db::startTrans();
         try{
            $res = Db::name('purchase')->where('purnumbers',$purnumbers)
                                            ->where('clinic_id',$this->zstUser['shop_id'])
                                            ->setField('purstatus',1);

            $res2 = Db::name('store')->where('purnumbers',$purnumbers)
                                          ->where('clinic_id',$this->zstUser['shop_id'])
                                          ->setField('is_conform',1);


            Db::name('store')->where('purnumbers',$purnumbers)
                                  ->where('clinic_id',$this->zstUser['shop_id'])
                                  ->setField('intime',time());


            //入库到库存表

             //获取该单号下的明细
             $purlist = Db::name('store')->where('purnumbers',$purnumbers)->where('clinic_id',$this->zstUser['shop_id'])->select();

             //入库
            foreach ($purlist as $v){
                 $isExistYp = Db::name('warehouse')->where('code',$v['code'])->where('clinic_id',$this->zstUser['shop_id'])->find();

                 if($isExistYp){
                     //存在更新数量
                     $wareUpdateData = [
                         'price'=>$v['price'],
                         'innumber'=> Db::raw('innumber+'.$v['innumber']),
                         'MoneyAccount'=>Db::raw('MoneyAccount+'.$v['MoneyAccount']),
                         'Piece'=>Db::raw('Piece+'.$v['Piece']),
                         'box_number'=>$v['box_number'],
                         'pihao'=>$v['pihao'],
                         'yxqz'=>$v['yxqz'],
                         'update_time'=>time(),
                     ];

                     Db::name('warehouse')->where('code',$v['code'])->update($wareUpdateData);

                 }else{
                     //不存在添加
                     $wareData = [
                         'wareuid'=>UUID(),
                         //'code'=>$isExistYp['code'],
                         'zl_status'=>$v['zl_status'],
                         'code'=>$v['code'],
                         'price'=>$v['price'],
                         'innumber'=>$v['innumber'],
                         'pihao'=>$v['pihao'],
                         'yxqz'=>strtotime($v['yxqz']),
                         'MoneyAccount'=>$v['price'] * $v['innumber'],
                         'Piece'=>$v['Piece'],
                         'box_number'=>$v['box_number'],
                         'medid'=>$v['medid'],
                         'purnumbers'=>$v['purnumbers'],
                         'pihao'=>$v['pihao'],
                         'yxqz'=>$v['yxqz'],
                         'puid'=>$v['puid'],
                         'SupplyID'=>$v['SupplyID'],
                         'update_time'=>time(),
                         'create_time'=>time(),
                         'intime'=>time(),
                         'status'=>1,
                         'clinic_id'=>$v['clinic_id'],

                     ];
                     Db::name('warehouse')->insert($wareData);
                 }

            }

            if($res!==false  && $res2!==false){
                Db::commit();
                return  json_encode(['code'=>1,'msg'=>'入库成功']);
            }
         }catch (\Exception $e){
             Db::rollback();
             return json_encode(['code'=>0,'msg'=>'入库出现问题'.$e->getMessage()]);
         }
    }

    /**采购欠款记录
     * @param $oid
     * @param $arrears_money
     * @param $info
     */
    public function runArrears($oid,$arrears_money,$info)
    {
        //生产欠款记录
        $arrearsData=[
            'arrears_uid'=>UUID(),
            'arrears_type'=>2,
            'arrears_nums'=>$this->getTableSn('QK','arrears_nums','zst_arrears'),
            'status'=>0,
            'arrears_person'=>$this->zstUser['user_name'],
            'arrears_money'=>$arrears_money,
            'info'=>$info,
            'doc_id'=>$oid,
            'clinic_id'=>$this->zstUser['shop_id'],
            'arrears_time'=>time(),
            'create_time'=>time(),


        ];
        Db::name('arrears')->insert($arrearsData);
    }

    /**结算
     * @param Request $request
     * @return string
     */
    public function doAccount(Request $request)
    {
        $ids = $request->param('arr/a');
        if($ids){
            if(is_array($ids))
            {
                $where = 'pid in('.implode(',',$ids).')';
            }
            $where = ' and clinic_id = ' .$this->zstUser['shop_id'];
            $result = $this->purchase->where($where)->setField('isPay',1);
             $this->purchase->where($where)->setField('paytime',time());
            if($result!==false){
                return json_encode(['code'=>1,'msg'=>'结算成功']);
            }else{
                return json_encode(['code'=>0,'msg'=>'结算失败']);
            }
        }else{
            return json_encode(['code'=>0,'msg'=>'请选择数据']);
        }
    }

    /**采购新建--选择供应商弹窗
     * @param Request $request
     */
    public function SelSupply(Request $request)
    {
        echo  $this->fetch('SelSupply');
    }

    /**采购新建--选择供应商
     * @param Request $request
     */
    public function getYP(Request $request)
    {
        $map = [];
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['supplier_scode|supplier_name']=['like',$keys."%"];
        }


        $page=$request->param('page/d','1');
        $limit=$request->param('rows/d','10');
        $page =$page-1;
        if($page>0){
            $page=$page*$limit;
        }

        $count = $this->supplyer->where(array('supply_type'=>0))
                               ->where(array('check_type'=>1))
                                ->where(array('clinic_id'=>$this->zstUser['shop_id']))
                               ->where($map)

                               ->count('id');


        $list = $this->supplyer->where(array('supply_type'=>0))
                               ->where(array('check_type'=>1))
                               ->limit($page,$limit)
                                ->where($map)
                               ->where(array('clinic_id'=>$this->zstUser['shop_id']))
                               ->select();
        $result= [
            'rows'=>$list,
            'total'=>$count
        ];
        return $result;
    }

    /**采购新建--获取商品弹窗
     * @param Request $request
     */
    public function SelPro(Request $request)
    {
        echo  $this->fetch('SelProduct');
    }

    /**采购新建--获取商品信息
     * @param Request $request
     * @return mixed
     */
    public function getMed(Request $request)
    {
        $map = [];
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['code|uname']=['like',$keys."%"];
        }


        $page=$request->param('page/d','1');
        $limit=$request->param('rows/d','10');
        $page =$page-1;
        if($page>0){
            $page=$page*$limit;
        }


        $subQuery = Db::name('store')->field('medid,IFNULL(LastPrice,\'0.00\') as LastPrice')
                                               ->order('intime desc')
                                               ->where('clinic_id',$this->zstUser['shop_id'])
                                               ->limit(1)
                                               ->buildSql();

        $count = $this->med->field('a.*,IFNULL(`b`.`LastPrice`, \'0.00\') AS LastPrice,c.yxq_month as yxq_month,d.dwgl_name as dwgl_name')
            ->alias('a')
            ->join($subQuery.'as b','a.id=b.medid','LEFT')
            ->join('zst_yxq c','a.effect_id=c.yxq_id','LEFT')
            ->join('zst_dwgl d','a.unit_id=d.dwgl_id','LEFT')
            ->where(array('check_type'=>1))
            ->where(array('sales_type'=>0))
            ->where('a.clinic_id',$this->zstUser['shop_id'])
            ->where($map)
            ->count('a.id');


        //'a.*,IFNULL(`b`.`LastPrice`, \'0.00\') AS LastPrice,c.yxq_month as effect_id,d.dwgl_name as unit_id'
        $info = $this->med->field('a.*,IFNULL(`b`.`LastPrice`, \'0.00\') AS LastPrice,c.yxq_month as yxq_month,d.dwgl_name as dwgl_name')
                          ->alias('a')
                          ->join($subQuery.'as b','a.id=b.medid','LEFT')
                          ->join('zst_yxq c','a.effect_id=c.yxq_id','LEFT')
                          ->join('zst_dwgl d','a.unit_id=d.id','LEFT')
                          ->where(array('check_type'=>1))
                          ->where(array('sales_type'=>0))
                          ->where('a.clinic_id',$this->zstUser['shop_id'])
                          ->where($map)
                          ->limit($page,$limit)
                          ->select();

        $result= [
            'rows'=>$info,
            'total'=>$count
        ];
        return $result;
    }

    /**导出excel
     * @param Request $request
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function dataToExcel(Request $request)
    {
        $excel = new Api();
        $map = [];
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['purnumbers|supplier_code|SupplyName|contactor|contactor_phone']=['like',$keys."%"];
        }

        $pay = $request->param('pay','');
        if($pay != ''){
            $map['isPay']= ['eq',$pay];
        }

        $map['clinic_id']= ['eq',$this->zstUser['shop_id']];


        $field = "purnumbers,if(purstatus=1,'已入库','未入库') as purstatus,purtotal,puryftotal,FROM_UNIXTIME(create_time, '%Y-%m-%d') as create_time,FROM_UNIXTIME(daohuotime, '%Y-%m-%d') as daohuotime,SHDH,supplier_code,SupplyName,contactor,contactor_phone,operater,if(isqk=1,'已结算','未结算') as isqk,printor,FROM_UNIXTIME(print_time, '%Y-%m-%d') as print_time,info";
        $excel_table = Db::name('purchase')->field($field)
                                         ->where($map)
                                         ->order('daohuotime desc')
                                         ->buildSql();

        $excel_field = [
            'purnumbers'=>'采购单编号',
            'purstatus'=>'入库状态',
            'purtotal'=>'采购总金额',
            'puryftotal'=>'应付总金额',
            'create_time'=>'创建时间',
            'daohuotime'=>'到货时间',
            'SHDH'=>'随货同行单号',
            'supplier_code'=>'供应商编码',
            'SupplyName'=>'供应商名称',
            'contactor'=>'联系人',
            'contactor_phone'=>'联系电话',
            'operater'=>'operater',
            'isqk'=>'结算状态',
            'printor'=>'打印人',
            'print_time'=>'打印日期',
            'info'=>'备注',
        ];

        $fileName = '采购入库记录表_' . date('Ymd',time());
        $sheetNmae = '入库单记录';
        $excel->excelDown($excel_table,$excel_field,$map=[],$fileName,$sheetNmae,2);
    }


    /**采购编号
     * @return bool|string
     */
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

}