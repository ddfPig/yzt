<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 12:02
 */

namespace app\index\Model;


use think\Controller;
use think\Model;

class PurbackModel extends Model
{
    protected $table = 'zst_purback';

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

    //出库状态
    protected function getBackpurStatusAttr($value)
    {
        $text = [0 => '未出库', 1 => '已出库'];
        return $text[$value];
    }



}