<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 16:32
 */

namespace app\index\Model;


use think\Model;

class StoreModel extends Model
{
   protected $table='zst_store';

    //
//    protected function getZlStatusAttr($value)
//   {
//        $text = [0 => '不合格', 1 => '合格',2 => '可疑'];
//        return $text[$value];
//    }

}