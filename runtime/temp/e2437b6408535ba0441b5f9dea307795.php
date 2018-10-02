<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\base\supplier.html";i:1537940820;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
<script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
<link rel="shortcut icon" href="/public/static/images/favicon.ico" />



</head>
<body>
<ul class="breadcrumb">
    首页<span class="divider">/</span>基础管理<span class="divider">/</span>药品管理
</ul>
<div class="title_right"><strong>药品管理</strong></div>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/base/supplier_add'); ?>">新建</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="update">修改</a>
                    <select style="height:24px;line-height:24px; width:auto" id="credentials" onchange="javascript:search()">
                        <option value=-2>按资质状态筛选</option>
                        <option value=-1>资质未过期</option>
						<?php if($expiretime['name'] > 0): ?>
                        <option value=<?php echo $expiretime['id']; ?>>资质近<?php echo $expiretime['name']; ?>天过期</option>
						<?php endif; ?>
                        <option value=0>资质已过期</option>
                    </select>
                    <select style="height:24px;line-height:24px; width:auto" id="supply" onchange="javascript:search()">
                        <option value=-1>按供货状态筛选</option>
                        <option value=0>正常供货</option>
                        <option value=1>暂停供货</option>
                    </select>
                    <select style="height:24px;line-height:24px; width:auto" id="business" onchange="javascript:search()">
                        <option value=-1>按单位类型筛选</option>
                        <?php if(is_array($business_type) || $business_type instanceof \think\Collection || $business_type instanceof \think\Paginator): $i = 0; $__LIST__ = $business_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <input type="text" placeholder="请输入查询关键字" style="width:200px" id="keyword" />
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" href="javascript:search()">查询</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" onclick="javascript:Execl();">导出</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-import'">导入</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                </div>

			<!--div id="divpadding" class="margin-top-5" style="clear:both"-->
			<div style="width:100%;height:85%;" >
            <div id="grid" style="width:100%;height:100%; ">

			</div>
            
  

   </div>
              
</div>
<script>
		//导出
    function Execl() {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var credentials = $("#credentials").val();
            var keyword = $("#keyword").val();
			var supply = $("#supply").val();
			var business = $("#business").val();
            if (r) {
                window.location.href = "/index/base/supplier_out.html?credentials="+credentials+"&keyword="+keyword+"&supply="+supply+"&business="+business;
            }
        });
    }
	var grid;
    //$(function () {
		$("#update").click(function () {

            if (getSelectedArr(grid).length > 1) {
				$.messager.alert('提示','只能选择一条数据进行修改','warning'); return;
            }
            if (getSelectedArr(grid).length < 1) {
				$.messager.alert('提示','您未选择修改数据','warning'); return;
            }
			window.location.href = '/index.php/index/base/supplier_edit?id=' + getSelectedArr(grid);
        })

        //$("#search").click(function () {
		function search(){
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            //queryParams.page = 1;
            queryParams.keyword = $("#keyword").val();
            queryParams.credentials = $("#credentials").val();
            queryParams.supply = $("#supply").val();
            queryParams.business = $("#business").val();
            grid.datagrid('options').queryParams = queryParams;
			console.log(grid.datagrid('options').page);
            grid.datagrid('reload');
		}
        //})
	
	
		grid = $('#grid').datagrid({   
			url:'/index.php/index/base/supplier_search?ran=' + Math.random() + '&keyword=' + encodeURI($("#keyword").val()) + '&credentials=' + $("#credentials").val() + '&supply=' + $("#supply").val() + '&business=' + $("#business").val(),
            pageSize: 50,
			fit: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]], 
			columns:[[
                { field: 'check_type', title: '状态' },
                { field: 'expiretime', title: '资质状态' },
                { field: 'supply_type', title: '供货状态' },
                { field: 'business_type', title: '单位类型' },
                { field: 'supplier_code', title: '编码' },
                { field: 'supplier_name', title: '名称' },
                { field: 'business_license', title: '营业执照号' },
                { field: 'province', title: '省份' },
                { field: 'city', title: '县市' },
                { field: 'area', title: '地区' },
                { field: 'file_number', title: '档案编号' },
			]],
            pagination: true,
            rownumbers: true,  
            fitColumns: true,
			onDblClickCell: function (index, field, value) {
                window.location.href = '/index.php/index/base/supplier_edit?id=' + grid.datagrid('getRows')[index]['id'];
            }

		});
    //});
	
	
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