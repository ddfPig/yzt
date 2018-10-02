<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/16 16:46
 */


namespace app\index\Model;


use think\Model;

class ArrearsModel extends Model
{
    protected $table = 'zst_arrears';

    //创建时间
    protected function getCreateTimeAttr($create_time)
    {
        return date('Y-m-d', $create_time);
    }

    //欠款时间
    protected function getArrearsTimeAttr($arrears_time)
    {
        return date('Y-m-d', $arrears_time);
    }

    //单据状态
    protected function getStatusAttr($value)
    {
        $text = [0 => '未结算', 1 => '已结算'];
        return $text[$value];
    }

    //欠款类型
    protected function getArrearsTypeAttr($value)
    {
        $text = [1 => '诊疗欠款', 2 => '采购欠款'];
        return $text[$value];
    }

}