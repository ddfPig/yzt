<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 18:02
 */

namespace app\index\controller;

use think\Controller;
use think\Db;

class Systemabout extends Home
{
    public function index(){

        $data = Db::table('zst_gxxtjj')->field("gxxtjj_name,gxxtjj_version,gxxtjj_phone,gxxtjj_qq,gxxtjj_more,gxxtjj_create_time")->where('id',1)->find();

        if ($data == NULL){
            $data['gxxtjj_name'] = '';
            $data['gxxtjj_version'] = '';
            $data['gxxtjj_phone'] = '';
            $data['gxxtjj_qq'] = '';
            $data['gxxtjj_more'] = '';
            $data['gxxtjj_create_time'] = time();
        }


        $this->assign([
            'data'	=> $data,
        ]);

        return $this->fetch('index');
    }
}