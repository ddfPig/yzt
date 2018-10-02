<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 12:42
 */

namespace app\index\Validate;


use think\Validate;

class Purcg extends Validate
{
    protected $rule = [
        'purnumbers'=>'require',
        'create_time'=>'require|date',
        'confirm_time'=>'require|date',
        'supplier_code'=>'require',
        'purtotal'=>'require|float',
        'puryftotal'=>'require|float',
        'daohuotime'=>'require|date',
        'SHDH'=>'require',
        'contacts_person'=>'require',
        'contacts_phone'=>'require',
        'operater'=>'require',
        'isqk'=>'number',

        'code'=>'require',
        'price'=>'require|float',
        'zl_status'=>'require|number',
        'LastPrice'=>'require|float',
        'MoneyAccount'=>'require|float',
        'MoneyAccount2'=>'require|float',
        'pnumber'=>'require|float',
        'Piece'=>'require|float',
        'innumber'=>'require|float',
        'pihao'=>'require',
        'yxqz'=>'require|date',

    ];

    protected $message= [
        'purnumbers.require'=>'请输入采购单号',
        'create_time.require'=>'请输入创建日期',
        'create_time.date'=>'请正确输的创建日期格式',
        'confirm_time.require'=>'请输入确认日期',
        'confirm_time.date'=>'请正确输的确认日期格式',
        'supplier_code.require'=>'请输入供应商编码',
        'purtotal.require'=>'请输入总金额',
        'purtotal.float'=>'总金额不是货币格式',
        'puryftotal.require'=>'请输入应付总金额',
        'puryftotal.float'=>'应付总金额不是货币格式',
        'daohuotime.require'=>'请输入到货日期',
        'daohuotime.date'=>'请正确输的到货日期格式',
        'SHDH.require'=>'请输入随货同行单号',
        'contacts_person.require'=>'请输入联系人姓名',
        'contacts_phone.require'=>'请输入联系人手机号',
        'operater.require'=>'请输入单据操作人',
        'isqk.number'=>'欠款字段必须为数字',

        'code.require'=>'请输入药品编号',
        'price.require'=>'请输入药品单价',
        'price.float'=>'药品单价格式不正确',
        'zl_status.require'=>'请输入药品质量状态',
        'zl_status.number'=>'药品质量状态必须是数字',
        'LastPrice'=>'请输入上次采购价格',
        'LastPrice.float'=>'上次采购价格格式不正确',
        'MoneyAccount.require'=>'请输入总金额',
        'MoneyAccount.float'=>'总金额格式不正确',
        'MoneyAccount2.require'=>'请输入应付总金额',
        'MoneyAccount2.float'=>'应付总金额格式不正确',
        'pnumber.require'=>'请输入药品采购数量',
        'pnumber.float'=>'采购数量格式不正确',
        'Piece.require'=>'请输入采购件数',
        'Piece.float'=>'采购件数格式不正确',
        'innumber.require'=>'请输入实际入库数量',
        'innumber.float'=>'实际入库数量格式不正确',
        'pihao.require'=>'请输入药品批号',
        'yxqz.require'=>'请输入药品有效期日',
        'yxqz.date'=>'药品有效期日格式不正确',
    ];

    protected $scene = [
        'add'=>['purnumbers','create_time','confirm_time','supplier_code','purtotal','puryftotal','daohuotime','SHDH','contacts_person','contacts_phone','operater','isqk'],

        'edit'=>['purnumbers','create_time','confirm_time','supplier_code','purtotal','puryftotal','daohuotime','SHDH','contacts_person','contacts_phone','operater','isqk'],

        'store'=>['code','price','zl_status','LastPrice','MoneyAccount','MoneyAccount2','pnumber','Piece','innumber','pihao','yxqz'],
    ];
}