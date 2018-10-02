<?php
/**
 * Created by PhpStorm.
 * User: 刘进义
 * Contact: ddf-128@163.com
 * Wechat:dd283681008
 * Date: 2018/9/15 21:05
 */


namespace app\index\controller;


use think\Controller;
use think\Db;

class Api extends Controller
{

    /**获取有效期
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEffect()
    {
        $ysq =  Db::name('yxq')->field('yxq_id,yxq_month')->where('yxq_state',1)
                                        //->where('clinic_id',1)
                                        ->select();
        return json_encode($ysq);
    }

    /**获取单位
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUnit()
    {
        $unit = Db::name('dwgl')->field('dwgl_id,dwgl_name')
                          ->where('dwgl_state',1)
                          ->where('clinic_id',1)
                          ->select();
        return json_encode($unit);
    }

    /** excel下载公用方法
     * @param $table                         //数据库表名
     * @param $field                         //数据字段
     * @param $where                         //查询条件
     * @param $fileName                      //生成excel名称
     * @param $sheetNmae                     //生成的sheet表名
     * @param $type                          //1 执行单个数据表数据查询，2执行多表构造子查询
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     */
    public function excelDown($table,$field,$where,$fileName,$sheetNmae,$type=1)
    {
         $excel=new \Excel();
         $excel->setExcelName($fileName)
               ->createSheet($sheetNmae,$table,$field,$where,$type)
               ->downloadExcel();
    }

    /**excel导入,读取excel,返回二维数据，第一个数组元素是excel表头信息
     * @param $path     文件路径
     * @return mixed
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     */
    public function excelTo($path)
    {
        $excel=new \Excel();
        $getExcelObject = $excel->loadExcel($path);
        $sheetName=$getExcelObject->getSheetNames();
        return  $getExcelObject->getSheetByName($sheetName[0])->toArray();
    }


}