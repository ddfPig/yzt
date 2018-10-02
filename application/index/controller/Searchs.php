<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/19 21:46
 */


namespace app\index\controller;


class Searchs extends Home
{
    public function searchStore()
    {
        return $this->fetch('searchStore');
    }

    public function searchPatient()
    {
        return $this->fetch('searchPatient');
    }


    public function searchTreatment()
    {
        return $this->fetch('searchTreatment');
    }
}