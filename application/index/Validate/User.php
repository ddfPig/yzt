<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 7:53
 */

namespace app\index\Validate;


use think\Validate;

class User extends  Validate
{
    protected $regex  = [
        'pass' => '^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$',
        'ismobile'=>'^1[34578]\d{9}$',
        'zn'=>'^[\x7f-\xff]+$'


        ];
     protected $rule = [
         'serviceID'=>'require',
         'zdID'=>'require',
         'shop_type'=>'require',
         'shop_number'=>'require',
         'shop_name'=>'require',
         'licence'=>'require',
         'shop_address'=>'require',
         'province'=>'require',
         'city'=>'require',
         'town'=>'require',
         'leader'=>'require|regex:zn',
         'contactor'=>'require|regex:zn',
         'mobile'=>'require|regex:ismobile',
         'open_time'=>'require',
         //'busniss_date'=>'requireIf:checkbox,0',

         'admin_user'=>'require|unique:account',
         'admin_pass'=>'require|length:6,20|regex:pass',

         'certificate_type'=>'require',
         'certificate_num'=>'require',
         'organization'=>'require',
         'certificate_atime'=>'require',
         'certificate_stime'=>'require',
         'scope'=>'require',
         'certificate_name'=>'require',
         'certificate_identity'=>'require',
         'certificate_contacts'=>'require',
         'imgurl'=>'require',
         'certificate_test'=>'require',

     ];

     protected $message = [
         'serviceID.require'=>'请为该店铺分配系统客服',
         'zdID.require'=>'请为该店铺分配终端',
         'shop_type.require'=>'请选择诊所类型',
         'shop_number.require'=>'请输入诊所编号',
         'shop_name.require'=>'请输入诊所名称',
         'licence.require'=>'请输入医疗机构执行许可证',
         'shop_address.require'=>'请输入诊所经营地址',
         'province.require'=>'请输入诊所所属省份',
         'city.require'=>'请输入诊所所属城市',
         'town.require'=>'请输入诊所所属市区',
         'leader.require'=>'请输入负责人姓名',
         'leader.regex'=>'负责人只能输入汉字',
         'contactor.require'=>'请输入联系人姓名',
         'contactor.regex'=>'联系人只能输入汉字',
         'mobile.require'=>'请输入联系人手机号',
         'mobile.regex'=>'手机号码格式错误',
         'open_time.require'=>'请设置开通时间',
         //'busniss_date.requireIf'=>'请设置营业期限',

         'admin_user.require'=>'请为诊所设置登录账号',
         'admin_user.unique'=>'已存在相同的登录名称',
         'admin_pass.require'=>'请输入登录密码',
         'admin_pass.length'=>'密码长度在6位到20位之间',
         'admin_pass.regex'=>'密码必须是字母加数字组成',

         'certificate_type.require'=>'请填写证书类型',
         'certificate_num.require'=>'请填写证书编号',
         'organization.require'=>'请填写发放机构',
         'certificate_atime.require'=>'请填写发证日期',
         'certificate_stime.require'=>'请填写到期日期',
         'scope.require'=>'请填写许可范围',
         'certificate_name.require'=>'请填写证书人名字',
         'certificate_identity.require'=>'请填写证书人身份证',
         'certificate_contacts.require'=>'请填写证书联系人',
         'imgurl.require'=>'请上传证书照片',
     ];

     protected $scene = [
         'add'  =>  ['serviceID','zdID','shop_type','shop_number','shop_name','licence','shop_address','province','city','town','leader','contactor','mobile','open_time','busniss_date','admin_user','admin_pass'],
         'pwd'=>['admin_pass'],
         'edit'  =>  [
             'serviceID',
             'zdID',
             'shop_type',
             'shop_number',
             'shop_name',
             'licence',
             'shop_address',
             'province',
             'city',
             'town',
             'leader',
             'contactor',
             'mobile',
             'open_time',
             'busniss_date',

             'admin_user',


//             'certificate_type',
//             'certificate_num',
//             'organization',
//             'certificate_atime',
//             'certificate_stime',
//             'scope',
//             'certificate_name',
//             'certificate_identity',
//             'certificate_contacts',
//             'imgurl'
         ],
		'Clinicbase_upd'=>['shop_name','licence','shop_address','contactor','mobile'],
     ];
}