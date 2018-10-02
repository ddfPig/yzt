<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\base\medicine.html";i:1537858456;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/base/medicine_add'); ?>">新建</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="update">修改</a>
                    <select style="height:24px;line-height:24px; width:auto" id="supply" onchange="javascript:search()">
                        <option value=-1>按供货状态筛选</option>
                        <option value=0>正常供货</option>
                        <option value=1>暂停供货</option>
                    </select>
					<select style="height:24px;line-height:24px; width:auto" id="check" onchange="javascript:search()">
                        <option value=-1>按审核状态筛选</option>
                        <option value=0>未审核</option>
                        <option value=1>已审核</option>
                    </select>
                    <select style="height:24px;line-height:24px; width:auto" id="classify" onchange="javascript:search()">
                        <option value=-1>按商品分类筛选</option>
                        <?php if(is_array($classify_id) || $classify_id instanceof \think\Collection || $classify_id instanceof \think\Paginator): $i = 0; $__LIST__ = $classify_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <input type="text" placeholder="请输入查询关键字" style="width:200px" id="keyword"/>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="search" href="javascript:search()">查询</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" onclick="javascript:Execl();">导出</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                </div>
                


<div style="width:100%;height:85%; "><!-- calc( 100% - 100px )-->
<div id="grid" style="width:100%;height:100%; ">

</div>
</div>
              
</div>
<script>
		//导出
    function Execl() {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var supply = $("#supply").val();
            var keyword = $("#keyword").val();
			var check = $("#check").val();
			var classify = $("#classify").val();
            if (r) {
                window.location.href = "/index/base/medicine_out.html?supply="+supply+"&keyword="+keyword+"&check="+check+"&classify="+classify;
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
			window.location.href = '/index.php/index/base/medicine_edit?id=' + getSelectedArr(grid);
        })

        //$("#search").click(function () {
		function search(){
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            queryParams.supply = $("#supply").val();
            queryParams.check = $("#check").val();
            queryParams.classify = $("#classify").val();
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
		}
        //})
	
	
		grid = $('#grid').datagrid({   
			url:'/index.php/index/base/medicine_search?ran=' + Math.random() + '&keyword=' + encodeURI($("#keyword").val()) + '&supply=' + $("#supply").val() + '&check=' + $("#check").val() + '&classify=' + $("#classify").val(),
            pageSize: 50,
			fit: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]], 
			columns:[[
                { field: 'classify_id', title: '商品分类' },
                { field: 'code', title: '编码' },
                { field: 'uname', title: '通用名称' },
                { field: 'name', title: '商品名' },
                { field: 'specification', title: '规格' },
                { field: 'dosage_id', title: '剂型' },
                { field: 'unit_id', title: '单位' },
                { field: 'manufacturer', title: '生产厂商' },
                { field: 'produce_place', title: '产地' },
                { field: 'approval_number', title: '批准文号' },
                { field: 'effect_id', title: '商品效期（月）' },
                { field: 'box_number', title: '整箱数量' },
                { field: 'bar_code', title: '条码' },
                { field: 'prescription', title: '处方药' },
                { field: 'special', title: '含特殊药品复方制剂' },
                { field: 'archival_information', title: '档案编号' },
			]],
            pagination: true,
            rownumbers: true,  
            fitColumns: true,
			onDblClickCell: function (index, field, value) {
                window.location.href = '/index.php/index/base/medicine_edit?id=' + grid.datagrid('getRows')[index]['id'];
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