<?php
namespace app\index\validate;
use   think\Validate;
class MemberValidate extends Validate
{
	protected	$rule	=	[
		'pay_type'		=>	'require|gt:-1',
		
	];
	protected	$message		=			[
		'pay_type.require'	=>	'收款方式必须存在',   
        'pay_type.gt'		=>	'请选择收款方式',
      
	
	];
	protected	$scene	=	[
		'reachargr_add'		=>		['pay_type'],
		
	];




}
