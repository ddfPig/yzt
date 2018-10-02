<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 16:10
 */

namespace app\index\Model;


use think\Model;

class PurchaseModel extends Model
{
   protected $table = 'zst_purchase';

    //创建时间时间
    protected function getCreateTimeAttr($create_time)
    {
        return date('Y-m-d', $create_time);
    }

    //确认时间
    protected function getConfirmTimeAttr($confirm_time)
    {
        return date('Y-m-d', $confirm_time);
    }

    //到货时间
    protected function getDaohuotimeAttr($daohuotime)
    {
        return date('Y-m-d', $daohuotime);
    }


    //结算
    protected function getisqkAttr($value)
    {
        $text = [0 => '未结算', 1 => '已结算'];
        return $text[$value];
    }

    //采购单状态
    protected function getPurstatusAttr($value)
    {
        $text = [0 => '未入库', 1 => '已入库'];
        return $text[$value];
    }

}