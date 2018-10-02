<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 8:41
 */

namespace app\index\Model;


use think\Model;

class WarehouseModel extends Model
{
   protected  $table = 'zst_warehouse';

//    protected function getZlStatusAttr($value)
//    {
//        $text = [0 => '不合格', 1 => '合格',2 => '可疑'];
//        return $text[$value];
//    }


}