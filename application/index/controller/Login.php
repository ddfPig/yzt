<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/12 21:54
 */


namespace app\index\controller;


use app\index\Model\AccountModel;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Login extends Controller
{
    /**
     * 登陆页面
     */
    public function login()
    {
        //if(check_admin_login()) $this->redirect('index/index/index');
        return $this->fetch();
    }

    public function runlogin(Request $request)
    {
        $admin_user=$request->param('loginName');
        $admin_pass=$request->param('password');

        if(empty($admin_user)){
            return json_encode(['code'=>2,'msg'=>'请输入用户']);
        }

        if(empty($admin_pass)){
            return json_encode(['code'=>3,'msg'=>'请输入密码']);
        }

        $map['admin_user']=$admin_user;
        $map['admin_status']=1;
        $user = Db::name('account')->where($map)->find();
        if(!$user){
            return json_encode(['code'=>2,'msg'=>'用户不存在或被禁用']);
        }else{
            if (encrypt_password($admin_pass,$user['admin_pwd_salt'])!==$user['admin_pass']) {
                return json_encode(['code'=>3,'msg'=>'密码输入错误']);
            } else {
                $aid = $user['admin_id'];
                // 更新登录信息
                $log['admin_last_ip']   = request()->ip();
                $log['admin_last_time'] = time();
                Db::name('account')->where('admin_id',$user['admin_id'])->update($log);

                //登录角色判断
                $field = "a.admin_id,a.admin_user,a.admin_last_time,f.title,f.short";
                $roleInfo = Db::name('account')->field($field)->alias('a')
                    ->join('zst_auth_group_access e','a.admin_id=e.uid','LEFT')
                    ->join('zst_auth_group f','e.group_id=f.id','LEFT')->where('a.admin_id',$user['admin_id'])
                    ->find();

                if($roleInfo['short'] == 'cj'){
                    $auth = array(
                        'aid'=> $roleInfo['admin_id'],
                        'role_name'=>$roleInfo['title'],
                        'role_short'=>$roleInfo['short'],
                        'admin_name' =>$user['admin_user'],
                        'admin_last_time'=> $user['admin_last_time']
                    );
                }elseif($roleInfo['short'] == 'kf'){
                    $field = "a.admin_id,a.admin_user,a.admin_last_time,f.title,f.short,b.id,b.pkfgl_name,b.pkfgl_phone,b.pkfgl_qq";
                    $kfinfo = Db::name('account')->field($field)->alias('a')
                        ->join('zst_auth_group_access e','a.admin_id=e.uid','LEFT')
                        ->join('zst_auth_group f','e.group_id=f.id','LEFT')
                        ->join('zst_pkfgl b','a.admin_id=b.pkfgl_admin_id','LEFT')
                        ->where('a.admin_id',$user['admin_id'])
                        ->find();
                    $auth = array(
                        'aid'=> $kfinfo['admin_id'],
                        'kid'=> $kfinfo['id'],
                        'user_name'=>$kfinfo['pkfgl_name'],
                        'role_name'=>$kfinfo['title'],
                        'role_short'=>$kfinfo['short'],
                        'admin_name' =>$user['admin_user'],
                        'admin_last_time'=> $user['admin_last_time']
                    );

                }elseif($roleInfo['short'] == 'zd'){
                    $field = "a.admin_id,a.admin_user,a.admin_last_time,f.title,f.short,b.id,b.name,b.phone,b.qq";
                    $zdinfo = Db::name('account')->field($field)->alias('a')
                        ->join('zst_auth_group_access e','a.admin_id=e.uid','LEFT')
                        ->join('zst_auth_group f','e.group_id=f.id','LEFT')
                        ->join('zst_terminalm b','a.admin_id=b.admin_id','LEFT')
                        ->where('a.admin_id',$user['admin_id'])
                        ->find();
                    $auth = array(
                        'aid'=> $zdinfo['admin_id'],
                        'tid'=> $zdinfo['id'],
                        'user_name'=>$zdinfo['name'],
                        'role_name'=>$zdinfo['title'],
                        'role_short'=>$zdinfo['short'],
                        'admin_name' =>$user['admin_user'],
                        'admin_last_time'=> $user['admin_last_time']
                    );
                }else{
                    //获取登陆用户信息
                    $field = "a.admin_id,a.admin_user,a.admin_last_time,f.title,f.short,b.shop_name,b.shop_number,b.shop_id,b.shop_type,b.leader,b.contactor,b.mobile,b.exoire_time,c.clerk_id,c.clerk_office_num,c.clerk_status,c.clerk_name,c.clerk_phone,g.zylx_name";
                    $info = Db::name('account')->field($field)->alias('a')
                            ->join('zst_auth_group_access e','a.admin_id=e.uid','LEFT')
                            ->join('zst_auth_group f','e.group_id=f.id','LEFT')
                            ->join('zst_shop b','a.shop_id=b.shop_id','LEFT')
                            ->join('zst_clerk c','a.admin_id=c.admin_id','LEFT')
                            ->join('zst_zylx g','c.clerk_office_id=g.id','LEFT')
                            ->where('a.admin_id',$user['admin_id'])
                            ->find();
                    $auth = array(
                         'aid'=> $info['admin_id'],
                        'user_name'=>$info['clerk_name']?$info['clerk_name']:$info['contactor'],
                        'role_name'=>$info['title'],
                        'role_short'=>$info['short'],
                        'shop_name'=>$info['shop_name'],
                        'ctype'=>$info['zylx_name'],
                        'shop_number'=>$info['shop_number'],
                        'shop_id'=>$info['shop_id'],
                        'shop_type'=>$info['shop_type'],
                        'leader'=>$info['leader'],
                        'exoire_time'=>$info['exoire_time'],
                        'clerk_id'=>$info['clerk_id'],
                        'clerk_office_num'=>$info['clerk_office_num'],
                        'clerk_status'=>$info['clerk_status'],
                        'clerk_phone'=>$info['clerk_phone'],
                        'admin_name' =>$user['admin_user'],
                        'admin_last_time'=> $user['admin_last_time']
                    );

                }

                $authMM = array(
                    'aid'=> $roleInfo['admin_id'],
                    'admin_name' =>$user['admin_user'],
                    'admin_last_time'=> $user['admin_last_time']
                );

                session('admin_auth', $auth);
                session('admin_auth_MM', $authMM);
                session('admin_auth_sign', data_signature($authMM));
               return  json_encode(['code'=>1,'msg'=>'登陆成功']);
            }
        }


    }



    /**
     * 退出登录
     */
    public function logout()
    {
        session('admin_auth',null);
        session('admin_auth_sign',null);
        //cookie('aid', null);
        //cookie('signin_token', null);
        $this->redirect('index/Login/login');
    }



}