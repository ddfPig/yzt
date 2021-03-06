<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/18 19:39
 */


namespace app\index\controller;


use app\common\controller\Common;
use app\index\model\AuthRule;
use think\Request;
use think\Db;

class Home extends Common
{
    protected $zstUser;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        //检测登陆
        $c= $this->check_admin_login();
        if(empty($c)){
            $this->redirect('index/login/login');//未登录
        }

        $auth = new AuthRule();
        $id_curr=$auth->get_url_id();
        if(!$auth->check_auth($id_curr)){
            //$this->error('没有权限',url('index/Index/index'));
        }

        //获取有权限的菜单tree
        $menus=$auth->get_admin_menus();
        $this->assign('menus',$menus);

        //当前方法倒推到顶级菜单ids数组
        $menus_curr=$auth->get_admin_parents($id_curr);
        $this->assign('menus_curr',$menus_curr);

        //取当前操作菜单父节点下菜单 当前菜单id(仅显示状态)
        $menus_child=$auth->get_admin_parent_menus($id_curr);
        $this->assign('menus_child',$menus_child);
        $this->assign('id_curr',$id_curr);

        //面包屑导航
         $this->assign('navInfo',getNavList());
        // //当前导航
         $this->assign('nav',navNow()['title']);
        //设置系统用户
        $this->zstUser = session('admin_auth');
        $this->assign('uinfo',$this->zstUser);
		 //面包屑导航
		$arr=rule();
		$this->assign('arr',$arr);
		
    }

	 public function home_list(){ //add页
        //省
        $data=db('city')->where('type',0)->order('orderNo')->select();
        $this->assign('data',$data);
        return  view();    
    }
    public function save(){ //保存
        $all=input('post.');
        var_dump($all);
    }
   // 根据id 查询
    public  function  setvalue(){
        $id=input('post.provinceid'); 
		
        $data=db('city')->where('ParentID',$id)->order('orderNo')->select();
        echo  json_encode($data);

    }

    public function  home_upd(){
        $province="FB0DC917-8E38-4971-9814-16CDC35F5D4D"; //省id
        $city="47F5FA7F-1391-4D8F-AC31-92165838FAFC";//市id
        $area="391464E7-3678-471F-9B97-A31EE27B76AB";//区id
  
        $data=db('city')->where('type',0)->order('orderNo')->select();
        $this->assign([
            'data'=>$data,
            'province'=>$province,
            'city'=>$city,
            'area'=>$area,
        ]);
        return  view();
    }

    /**区域城市联动
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getValue(){
		$cityid=input('post.cityid');    
		$areaid=input('post.areaid');   

		$city=db('city')->where('ID',$cityid)->find();
		$citys=db('city')->where('ParentID',$city['ParentID'])->where('ID','neq',$cityid)->select();
		$area=db('city')->where('ID',$areaid)->find();
		$areas=db('city')->where('ParentID',$cityid)->where('ID','neq',$areaid)->select();
     echo  json_encode(['citys'=>$citys,'areas'=>$areas,'city'=>$city,'area'=>$area]);
	  
    }

    /**图片上传
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        $file = request()->file('imgFile');
        $validate = config('upload_validate');
        $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
        if($info){
            $img_url =   config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
            return json_encode(['error'=>0,'url'=>$img_url]);
        }else{
            echo json_encode(['error'=>$file->getError(),'url'=>'']);
        }
    }

    /**
     * 供应商信息导入
     */
    public function importGys()
    {
        $file = request()->file('file');
        //dump($file);exit;
        $validate = config('upload_validate');
        $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path').DS.'excel'. DS . date('Y-m-d'));
        if(empty($info)){
            echo json_encode(['error'=>$file->getError()]);
        }
        //获取文件路径
        $excel_url =  ROOT_PATH. config('upload_path').DS.'excel'. DS . date('Y-m-d') . '/' . $info->getFilename();
        //数据导入
        $excel = new Api();
        $data = $excel->excelTo($excel_url);
        Db::startTrans();
        try{
            //foreach 插入数据
            $field_list = $data[1];
            $infos = [];
            foreach ($data as $k=>$v){
                $gys_data = [];
                foreach ($field_list as $key=>$field){
                    $gys_data[$field] = $v[$key];
                    $gys_data['clinic_id'] = $this->zstUser['shop_id'];
                    $gys_data['supply_type'] = 0;
                    $gys_data['check_type'] = 1;

                }
                array_push($infos,$gys_data);
            }
            unset($infos[0]);
            unset($infos[1]);

            //echo "<pre>";
            //print_r($infos);

           Db::name('supplier')->insertAll($infos);
           Db::commit();
            echo json_encode(['code'=>1,'msg'=>'供应商信息导入成功']);
        }catch (\Exception $e){
            Db::rollback();
            echo json_encode(['code'=>0,'msg'=>'供应商信息导入有误,请重试']);
        }


    }

    public function impotrView()
    {
        echo $this->fetch('impotrView');
    }



}