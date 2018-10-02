<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\member\recharge_list.html";i:1538203507;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>诊所通V1.0.0</title>
<link rel="stylesheet" href="/public/static/css/bootstrap.css" />
<link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/public/static/css/css.css" />
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
<script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
<script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
<link rel="shortcut icon" href="/public/static/images/favicon.ico" />
<script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>


</head>
<body>
   <ul class="breadcrumb">
    首页<span class="divider">/</span><?php echo $arr['parent_rule']; ?><span class="divider">/</span><?php echo $arr['rule']; ?>
</ul>
<div class="title_right"><strong><?php echo $arr['rule']; ?></strong></div>
<div style="position:absolute; right:15px;top:4px;">
    <span id="span_current_userinfo">
         Hi,
                    <?php if($uinfo['role_short'] =='cj'): ?>
                         系统管理员 （<?php echo $uinfo['admin_name']; ?>）
                    <?php elseif($uinfo['role_short'] =='zd'): ?>
                         终端经理 <?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
                    <?php elseif($uinfo['role_short'] =='kf'): ?>
                         系统客服 <?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
                    <?php elseif($uinfo['role_short'] =='zs'): ?>
                          <?php echo $uinfo['shop_name']; ?> <?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
                    <?php else: ?>
                          <?php echo $uinfo['shop_name']; ?>（<?php echo $uinfo['ctype']; ?>）<?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
                    <?php endif; ?>
    </span>

<span id="span_currenttime"></span></div>
<script>
    window.onload = function () {
        //定时器每秒调用一次fnDate()
        setInterval(function () {
            $('#span_currenttime').html(fnDate());
        }, 1000);
    }
    //js 获取当前时间
    function fnDate() {
        var date = new Date();
        var year = date.getFullYear();//当前年份
        var month = date.getMonth();//当前月份
        var data = date.getDate();//天
        var hours = date.getHours();//小时
        var minute = date.getMinutes();//分
        var second = date.getSeconds();//秒
        var time = year + "年" + fnW((month + 1)) + "月" + fnW(data) + "日" + " " + fnW(hours) + ":" + fnW(minute) + ":" + fnW(second);
        return time;
    }
    //补位 当某个字段不是两位数时补0
    function fnW(str) {
        var num;
        str >= 10 ? num = str : num = "0" + str;
        return num;
    }

</script>
    <style>
        .breadcrumb {
            margin: 0px;
            padding: 5px;
        }
    </style>
				<div class="margin-bottom-5">
					<a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/member/recharge_add'); ?>">新建</a>
					<a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="update">修改</a>
					<?php if($is_start==1): ?>
					
						<a href="<?php echo url('index/rechargereward/index'); ?>" class="easyui-linkbutton" data-options="iconCls:'icon-man'" id="update">储值奖励管理</a>
				
					<?php endif; ?>
					<select style="width:150px" id="pay_type" onchange="is_searchtype()">
						<option value ="-1" >按照收款方式筛选</option>
						<?php foreach($pay_type as $k=>$v): ?>						
						<option value ="<?php echo $v['id']; ?>"><?php echo $v['skfs_name']; ?></option>						
						<?php endforeach; ?>
					</select>
					
					<input type="text" id="start_date" editable="fasle" class="easyui-datebox"  style="width:100px"   onchange="is_searchtype()"/>
					<input type="text" id="end_date" editable="fasle" class="easyui-datebox"  style="width:100px"   onchange="is_searchtype()"/>	
					<input type="text" placeholder="请输入查询关键字" style="width:200px" id="keyword" />
					<a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="search"  >查询</a>
					<a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
				</div>
				<!-- onclick="searchdata()" -->

<div style="width:100%;height:85%; "><!-- calc( 100% - 100px )-->
<div id="grid" style="width:100%;height:100%; ">

</div>
</div>


</div>
<script>


 function   is_searchtype(){
	$("#search").click();
 }

//导出
function Execl() {
	$.messager.confirm('确认', '您确认要导出吗？', function (r) {
		var keyword = $("#keyword").val();
		if (r) {
			window.location.href = "/index/cure/patient_out.html?keyword="+keyword;
		}
	});
}
var grid;
$(function () {
	$("#update").click(function () {

		if (getSelectedArr(grid).length > 1) {
			$.messager.alert('提示','只能选择一条数据进行修改','warning'); return;
		}
		if (getSelectedArr(grid).length < 1) {
			$.messager.alert('提示','您未选择修改数据','warning'); return;
		}
		 window.location.href = "<?php echo url('index/member/recharge_upd'); ?>?id=" + getSelectedArr(grid); 
	})

	 $("#search").click(function () {
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
			queryParams.pay_type = $("#pay_type").val();
			queryParams.start_date = $('#start_date').datebox('getValue');  
			queryParams.end_date = $('#end_date').datebox('getValue');  
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
        })


	grid = $('#grid').datagrid({   
		url:"<?php echo url('index/member/recharge_list_api'); ?>?ran=" + Math.random() + '&keyword=' + encodeURI($("#keyword").val()),
		pageSize: 50,
		fit: true,
		frozenColumns: [[
			{ field: 'ck', checkbox: true }
		]], 
		columns:[[
			{ field: 'recharge_date', title: '储蓄日期' },
			{ field: 'vip_num', title: '会员卡号' },
			{ field: 'name', title: '姓名' },
			{ field: 'phone', title: '手机号' },
			{ field: 'age', title: '年龄' },
			{ field: 'storage_money', title: '储值金额' },				
			{ field: 'bestowal_money', title: '活动赠送金额' },
			{ field: 'true_money', title: '实际充值金额' },
			{ field: 'money', title: '储值前余额' },
			{ field: 'summoney', title: '余额' },
			{ field: 'pay_type', title: '收款方式' },
			{ field: 'creation', title: '创建人' },
			{ field: 'creation_date', title: '创建日期' },
			{ field: 'upd_people', title: '最后修改人' },
			{ field: 'upd_people_date', title: '最后修改日期' },
			{ field: 'remark', title: '备注' },
			
		]],
		pagination: true,
		rownumbers: true,  
		fitColumns: true,
		onDblClickCell: function (index, field, value) {
	
			 window.location.href = "<?php echo url('index/member/recharge_upd'); ?>?id=" + grid.datagrid('getRows')[index]['id']; 
		}

	});
});


function getSelectedArr() {
	var ids = [];
	var rows = grid.datagrid('getSelections');
	for (var i = 0; i < rows.length; i++) {
		ids.push(rows[i].id);
	}
	return ids;
}

</script>

</body>
</html>