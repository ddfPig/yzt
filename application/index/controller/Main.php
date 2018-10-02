<?php
namespace app\index\controller;
use \think\Request;

class Main extends Home
{
	//关于诊所通-更新系统简介
    public function updsystemintro()
    {
		$ver = Request::instance()->param('ver');
		return view('updsystemintro',['ver'=>$ver]);
    }
	
	//关于诊所通-更新日记管理
    public function upddiaryadmin()
    {
		$ver = Request::instance()->param('ver');
		return view('upddiaryadmin',['ver'=>$ver]);
    }
	
	//关于诊所通-帮助文档管理
    public function helpfileadmin()
    {
		$ver = Request::instance()->param('ver');
		return view('helpfileadmin',['ver'=>$ver]);
    }
	
	//诊所通用户-用户管理
    public function useradmin()
    {
		$ver = Request::instance()->param('ver');
		return view('useradmin',['ver'=>$ver]);
    }

}
