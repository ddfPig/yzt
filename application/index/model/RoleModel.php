<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/17 22:18
 */


namespace app\index\Model;


use think\Model;

class RoleModel extends Model
{
    protected $table ='zst_auth_group';

    //启用状态
    protected function getStatusAttr($value)
    {
        $text = [0 => '已停用', 1 => '已启用'];
        return $text[$value];
    }

    //添加时间
    protected function getAddTimeAttr($exoire_time)
    {
        return date('Y-m-d', $exoire_time);
    }
}