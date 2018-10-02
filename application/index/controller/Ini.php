<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 14:50
 */
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

//初始化设置
class Ini extends Home
{
    public function index()
    {

        $data = Db::name('dic_cshsz')->field("cshsz_jxqts,cshsz_jxqxzts,cshsz_rkspxzts,cshsz_kqlqtx,cshsz_lqxzts,cshsz_kqyebz,cshsz_yexzje,cshsz_kqczjl")->where('clinic_id',$this->zstUser['shop_id'])->find();

        if ($data == NULL){
            $data['cshsz_jxqts'] = 0;
            $data['cshsz_jxqxzts'] = 0;
            $data['cshsz_rkspxzts'] = 0;
            $data['cshsz_kqlqtx'] = 0;
            $data['cshsz_lqxzts'] = 0;
            $data['cshsz_kqyebz'] = 0;
            $data['cshsz_yexzje'] = "0.00";
        }
        $this->assign([
            'data'	=> $data,
        ]);

        return $this->fetch('index');
    }

    public function add_ini()
    {

        //初始化设置->保存
        $data = Request::instance()->param();

        $data['clinic_id'] = $this->zstUser['shop_id'];

        $list = Db::name('dic_cshsz')->where('clinic_id',$this->zstUser['shop_id'])->find();
        if ($list == NULL){
            $result = Db::name('dic_cshsz')->data($data)->insert($data);
        }else{
            $result = Db::name('dic_cshsz')->data($data)->where('clinic_id',$this->zstUser['shop_id'])->update($data);
        }

        if ($result !== false) {
            return json_encode(['error'=>1,'msg'=>'保存成功']);
        } else {
            return json_encode(['error'=>0,'msg'=>'保存失败']);
        }
    }

    //开通储值奖励回显
    public function reward_list(){
        $shop_id = input('get.shop_id');
        $shop_name = input('get.shop_name');
        $shop_number = input('get.shop_number');
        $state = Db::name('dic_cshsz')->where('clinic_id',$shop_id)->find();
        $cshsz_kqczjl = $state['cshsz_kqczjl'];

        $this->assign([
            'shop_id'	=> $shop_id,
            'shop_name' => $shop_name,
            'shop_number' => $shop_number,
            'cshsz_kqczjl' => $cshsz_kqczjl,
        ]);
        return $this->fetch('reward_edit');

    }
    //储值奖励保存
    public function reward_add(){

        $data = Request::instance()->param();
        $clinic_id = $data['clinic_id'];
        $cshsz_kqczjl = array(
          'cshsz_kqczjl' => $data['cshsz_kqczjl'],
        );
        $result = Db::name('dic_cshsz')->where('clinic_id',$clinic_id)->update($cshsz_kqczjl);

        if ($result !== false){
            return json_encode(['error'=>1,'msg'=>'修改成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'修改失败']);
        }
    }

}

