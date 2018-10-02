<?php
namespace app\index\validate;
use   think\Validate;
class ClerkValidate extends Validate
{
	protected	$rule	=	[	
		'clerk_name'		=>	'require',
		'clerk_phone'		=>	'require|regex:/^1[34578]\d{9}$/',
		'clerk_healthy_card'		=>	'require',
		'clerk_user'		=>	'require',
		'clerk_pass'		=>	'require|min:6|max:20',
		
	];
	protected	$message		=			[
		'clerk_name.require'	=>	'请填写名字',   
        'clerk_phone.require'		=>	'请填写手机号',
		'clerk_phone.regex'		=>	'请填写正确手机号',
		'clerk_healthy_card.require'		=>	'请填写健康证',
		'clerk_user.require'		=>	'请填写用户名',
		'clerk_pass.require'		=>	'请填写密码',
		'clerk_pass.min'		=>	'密码最少位数不能小于6位',
		'clerk_pass.max'		=>	'密码最多位数不能大于20位',
		'clerk_phone.require'		=>	'请填写手机号',
	
	];
	protected	$scene	=	[
		'Clerk_add'		=>		['clerk_name','clerk_phone','clerk_healthy_card','clerk_user','clerk_pass'],
		'Clerk_upd'		=>		['clerk_name','clerk_phone','clerk_healthy_card','clerk_user','clerk_pass'],
	];




}
