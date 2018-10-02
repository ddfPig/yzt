<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\common\controller\Author;
use think\Auth;
use think\Request;
// 应用公共文件

///*
// * 检查登录
// */
//function check_admin_login()
//{
//    $admin = session('admin_auth');
//
//    return session('admin_auth_sign') == data_signature($admin) ? $admin['auth_id'] : 0;
//}

/**
 * 数据签名
 * @param array $data 被认证的数据
 * @return string 签名
 */
function data_signature($data = [])
{
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data);
    $code = http_build_query($data);
    $sign = sha1($code);
    return $sign;
}
/*
* 所有用到密码的不可逆加密方式
* @author rainfer <81818832@qq.com>
 * @param string $password
* @param string $password_salt
* @return string
*/
function encrypt_password($password, $password_salt)
{
    return md5(md5($password) . md5($password_salt));
}

/**
 * 随机字符
 * @param int $length 长度
 * @param string $type 类型
 * @param int $convert 转换大小写 1大写 0小写
 * @return string
 */
function random($length=10, $type='letter', $convert=0)
{
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'letter';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

/**用户UID
 * @return string
 */
function UUID(){
    $uuid = '';
    if (function_exists('uuid_create') === true){
        $uuid = uuid_create(1);
    }else{
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $uuid =  vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    return $uuid;
}

/**获取当前菜单名称
 * @return array|false|PDOStatement|string|\think\Model
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function navNow()
{
    $navUrl = strtolower(request()->module()).'/'.request()->controller().'/'.request()->action();
    return  \think\Db::name('auth_rule')->field('id,title')->where('name',$navUrl)->where('status',1)->find();
}
/**
 * 获取当前地址在数据库中的ID
 * 首页<span class="divider">/</span>诊所通用户<span class="divider">/</span>用户管理
 *
 * @param unknown_type $catId
 */
function getNavList()
{
    $catId = navNow()['id'];
    $navPath = parentPath($catId);
    $count = count($navPath);
    $str = '首页';
    for($i=$count-1; $i>=0; $i--){
        $str .= "<span class='divider'>/</span>" . $navPath[$i]['title'];
    }
    return $str;
}



/**
 * 取出一个分类所有上级分类
 *
 *
 */
function parentPath($catId)
{
    static $ret = array();
    $info = \think\Db::name('auth_rule')->field('id,title,pid')->find($catId);
    $ret[] = $info;
    // 如果还有上级再取上级的信息
    if($info['pid'] > 0)
        parentPath($info['pid']);
    return $ret;
}
function  rule(){
		$request=Request::instance();
		$url_name=$request->module()."/".$request->controller()."/".$request->action();
		$rule=db('auth_rule')->where('name',$url_name)->find();
		$parent_rule=db('auth_rule')->where('id',$rule['pid'])->value('title');
		return ['parent_rule'=>$parent_rule,'rule'=>$rule['title'] ];
}