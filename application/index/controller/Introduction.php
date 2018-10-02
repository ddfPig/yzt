<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 17:40
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

//更新系统简介
class Introduction extends Home
{
    public function index(){

        $data = Db::table('zst_gxxtjj')->field("gxxtjj_name,gxxtjj_version,gxxtjj_phone,gxxtjj_qq,gxxtjj_more")->where('id',1)->find();

        if ($data == NULL){
            $data['gxxtjj_name'] = '';
            $data['gxxtjj_version'] = '';
            $data['gxxtjj_phone'] = '';
            $data['gxxtjj_qq'] = '';
            $data['gxxtjj_more'] = '';
        }

        $this->assign([
            'data'	=> $data,
        ]);

        return $this->fetch('index');

    }


    //更新系统简介->保存
    public function intaddbutton(){

        $data = Request::instance()->param();
        $data['gxxtjj_create_name'] = $this->zstUser['aid'];
        $data['gxxtjj_create_time'] = time();

        //验证类
        $rule = [
            'gxxtjj_name'  => 'require',
            'gxxtjj_version'  => 'require',
            'gxxtjj_phone'  => 'require',
            'gxxtjj_qq'  => 'require',
            'gxxtjj_more'  => 'require',
        ];
        $msg = [
            'gxxtjj_name.require' => '系统名称必须填写',
            'gxxtjj_version.require' => '系统版本必须填写',
            'gxxtjj_phone.require' => '服务电话必须填写',
            'gxxtjj_qq.require' => '服务QQ必须填写',
            'gxxtjj_more.require' => '更多介绍必须填写',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return json_encode(['error'=>0,'msg'=>$validate->getError()]);
        }


        $list = Db::table('zst_gxxtjj')->where('id',1)->find();

        if ($list == NULL){
            $result = Db::name('gxxtjj')->data($data)->insert($data);
        }else{
            $result = Db::name('gxxtjj')->where('id',1)->update($data);
        }
        if ($result !== false){
            return json_encode(['error'=>1,'msg'=>'保存成功']);
        }else{
            return json_encode(['error'=>0,'msg'=>'保存失败']);
        }
    }
}