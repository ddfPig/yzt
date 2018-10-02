<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/16 16:45
 */


namespace app\index\controller;


use app\index\Model\ArrearsModel;
use think\Controller;
use think\Db;
use think\Request;

class Debt extends Home
{
     protected $debts;
     public function _initialize()
     {
         parent::_initialize(); // TODO: Change the autogenerated stub
         $this->debts = new ArrearsModel();
     }

    /**欠款展示页面
     * @return mixed
     */
     public function index()
     {
         return $this->fetch();
     }

    /**欠款数据
     * @param Request $request
     */
     public function lists(Request $request)
     {
         $map = [];
         $keys = $request->param('keyword');
         if(!empty($keys)){
             $map['arrears_type|arrears_nums|arrears_days|arrears_money|arrears_person']=['like',$keys."%"];
         }
         //欠款类型
         $qktype = $request->param('qktype','');
         if($qktype != ''){
             $map['arrears_type']= ['eq',$qktype];
         }

         //单据状态
         $qkstatus = $request->param('qkstatus','');
         if($qkstatus != ''){
             $map['status']= ['eq',$qkstatus];
         }

         //时间格式过滤
         $startdate = $request->param('startdate');
         $enddate = $request->param('enddate');
         if($startdate && $enddate ){
             $arrdateone=strtotime($startdate);
             $arrdatetwo=strtotime($enddate.' 23:55:55');
             $map['arrears_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
         }


         $page=$request->param('page/d','1');
         $limit=$request->param('rows/d','10');
         $page =$page-1;
         if($page>0){
             $page=$page*$limit;
         }

         $map['clinic_id'] = ['eq',$this->zstUser['shop_id']];
         //timestampdiff(day,'2011-09-30','2015-05-04')
         $count = $this->debts->where($map)->count('arrears_id');
         $field = "if(arrears_type=1,'诊疗欠款','采购欠款') as arrears_type,arrears_nums,arrears_person,arrears_money,arrears_days,arrears_time,info,if(status=1,'已完成','未完成') as status,FROM_UNIXTIME(create_time, '%Y-%m-%d') as create_time,FROM_UNIXTIME(arrears_time, '%Y-%m-%d') as arrears_time,timestampdiff(day,FROM_UNIXTIME(arrears_time, '%Y-%m-%d'),now()) as arrears_days";
         $list = Db::name('arrears')->field($field)->where($map)->order('status asc,create_time desc')->limit($page,$limit)->select();
         $result= [
             'rows'=>$list,
             'total'=>$count
         ];
         return $result;
     }

    /**
     *   结算单处理
     */
     public function confirmDebt(Request $request)
     {

         $ids = $request->param('arr/a');
         if(is_array($ids))
         {
             $where = 'arrears_id in('.implode(',',$ids).')';
         }

         Db::startTrans();
         try{

             $result = $this->debts->where($where)
                                   ->where(array('clinic_id'=>$this->zstUser['shop_id']))
                                   ->setField('status',1);

             foreach ($ids as $v){
                 //欠款类型
                  $arrears_type = Db::name('arrears')->where('arrears_id',$v)->where('clinic_id',$this->zstUser['shop_id'])->value('arrears_type');
                  //单据ID
                  $docid = Db::name('arrears')->where('arrears_id',$v)->where('clinic_id',$this->zstUser['shop_id'])->value('doc_id');

                if($arrears_type ==1){
                    Db::name('purchase')->where('pid',$docid)->where('clinic_id',$this->zstUser['shop_id'])->setField('isqk',1);
                }else{
                    Db::name('acography')->where('id',$docid)->where('clinic_id',$this->zstUser['shop_id'])->setField('is_pay',1);
                }
                
             }

             if($result){
                  Db::commit();
                 return json_encode(['code'=>1,'msg'=>'已成功结算欠款单']);
             }

         }catch (\Exception $e){
              Db::rollback();
             return json_encode(['code'=>0,'msg'=>'欠款单结算失败,Error'.$e->getMessage()]);
         }

     }


    public function dataToExcel(Request $request)
    {
        $excel = new Api();
        $map = [];
        $keys = $request->param('keyword');
        if(!empty($keys)){
            $map['arrears_type|arrears_nums|arrears_days|arrears_money|arrears_person']=['like',$keys."%"];
        }
        //欠款类型
        $qktype = $request->param('qktype','');
        if($qktype != ''){
            $map['arrears_type']= ['eq',$qktype];
        }

        //单据状态
        $qkstatus = $request->param('qkstatus','');
        if($qkstatus != ''){
            $map['status']= ['eq',$qkstatus];
        }

        //时间格式过滤
        $startdate = $request->param('startdate');
        $enddate = $request->param('enddate');
        if($startdate && $enddate ){
            $arrdateone=strtotime($startdate);
            $arrdatetwo=strtotime($enddate.' 23:55:55');
            $map['arrears_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
        }

        $map['clinic_id'] = ['eq',$this->zstUser['shop_id']];

        $field = "if(arrears_type=1,'诊疗欠款','采购欠款') as arrears_type,arrears_nums,arrears_person,arrears_money,arrears_days,arrears_time,info,if(status=1,'已完成','未完成') as status,FROM_UNIXTIME(create_time, '%Y-%m-%d') as qcreate_time,FROM_UNIXTIME(arrears_time, '%Y-%m-%d') as qarrears_time,timestampdiff(day,FROM_UNIXTIME(arrears_time, '%Y-%m-%d'),now()) as qarrears_days";
        $excel_table = Db::name('arrears')->field($field)
                                        ->where($map)
                                        ->order('status asc,create_time desc')
                                        ->buildSql();

        $excel_field = [
            'arrears_type'=>'欠款类型',
            'arrears_nums'=>'欠款编号',
            'qcreate_time'=>'创建日期',
            'qarrears_time'=>'欠款日期',
            'qarrears_days'=>'欠款天数',
            'arrears_money'=>'欠款金额',
            'arrears_person'=>'欠款人',
            'info'=>'备注',
            //'doc_id'=>'关联单号',
        ];

        $fileName = '欠款数据记录表_' . date('Ymd',time());
        $sheetNmae = '欠款记录';
        $excel->excelDown($excel_table,$excel_field,$map=[],$fileName,$sheetNmae,2);
        header('location:'.getenv("HTTP_REFERER"));

    }




}