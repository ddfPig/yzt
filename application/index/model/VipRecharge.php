<?php
namespace app\index\Model;
use think\Model;
use think\Session;
class VipRecharge extends Model
{	

	protected	$type	=	[
			
		'storage_money'	=>		'float',
		'money'	=>		'float',
		'true_money'	=>		'float',
		'bestowal_money'	=>		'float',

	];
    public	function	getPayTypeAttr($value) //收款方式
	{		
      	$admin_auth=Session::get('admin_auth') ;  
		$pay_type=db('skfs')->where('clinic_id',$admin_auth['shop_id'])->where('skfs_state',1)->order('sort')->select();  
		foreach ($pay_type as $k=>$v){
			if($v['id']==$value){
				  return $v['skfs_name'];
				 
			}
		}
    }
    public	function	getRechargeDateAttr($value) //充值日期
	{
        $value=date("Y-m-d",$value) ;		
		return	$value;   
    }
    public	function	getCreationDateAttr($value) //创建日期
	{
        $value=date("Y-m-d",$value) ;		
		return	$value;    
    }
    public	function	getUpdPeopleDateAttr($value) //修改日期
	{
        if(empty($value)){
            return null;
        }else{
            $value=date("Y-m-d",$value) ;		
            return	$value;
        } 
    }
    public	function getStorageMoneyAttr($value) //储值金额
	{  
      return sprintf("%1\$.2f",$value);    
    }
	public	function getMoneyAttr($value) //会员卡余额
	{  
      return sprintf("%1\$.2f",$value);    
    }
	public	function getTrueMoneyAttr($value) //实际储值金额
	{  
      return sprintf("%1\$.2f",$value);    
    }
	public	function getBestowalMoneyAttr($value) //实际储值金额
	{  
      return sprintf("%1\$.2f",$value);    
    }
	
	
}