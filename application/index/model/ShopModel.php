<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 13:59
 */

namespace app\index\Model;


use think\Model;

class ShopModel extends Model
{
   protected $table="zst_shop";

    //开通时间
    protected function getOpenTimeAttr($open_time)
    {
        return date('Y-m-d', $open_time);
    }

    //到期时间
    protected function getExoireTimeAttr($exoire_time)
    {
        return date('Y-m-d', $exoire_time);
    }


    //启用状态
    protected function getShopStatusAttr($value)
    {
        $text = [0 => '已锁定', 1 => '已开通'];
        return $text[$value];
    }

    //用户类型
    protected function getShopTypeAttr($value)
    {
        $text = [1 => '连锁', 2 => '个体'];
        return $text[$value];
    }
    protected function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    protected function setCreateTimeAttr($value)
    {
        $value = strtotime($value);
        return $value;
    }
}