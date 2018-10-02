<?php
namespace app\index\controller;
use \think\Controller;
use	\think\Db;
class Clinicapi extends Controller
// 诊所基础信息Api
{
    public function  upd(){ //修改基础信息

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
       ];
       $Shop =model('ShopModel');
       $info=$Shop->save($data,['shop_id'=>1]);
       
       if($info) 
         echo(json_encode(array('status'=>1,'msg'=>'成功')));
        else
       echo(json_encode(array('status'=>2,'msg'=>'失败')));
    }
    public function Clinic_zz_list(){

        $id=1;//用户id
        $data=db('clinic_certificate')->where('id',$id)->find();
        echo json_encode($data);

    }
}
