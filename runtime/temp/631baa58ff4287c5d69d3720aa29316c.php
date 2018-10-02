<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\patient.html";i:1537859581;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    首页<span class="divider">/</span>诊疗管理<span class="divider">/</span>患者档案管理
</ul>
<div class="title_right"><strong>患者档案管理</strong></div>
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
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/cure/patient_add'); ?>">新建</a>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="update">修改</a>
						<a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
                        <input type="text" placeholder="请输入查询关键字" style="width:200px" id="keyword"/>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="search">查询</a>
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
			window.location.href = '/index.php/index/cure/patient_edit?id=' + getSelectedArr(grid);
        })

        $("#search").click(function () {
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
        })
	
	
		grid = $('#grid').datagrid({   
			url:'/index.php/index/cure/patient_search?ran=' + Math.random() + '&keyword=' + encodeURI($("#keyword").val()),
            pageSize: 50,
			fit: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]], 
			columns:[[
                { field: 'patient_number', title: '患者编号' },
                { field: 'name', title: '姓名' },
                { field: 'sex', title: '性别' },
                { field: 'age', title: '年龄' },
                { field: 'phone', title: '手机号' },
                { field: 'inheritance', title: '遗传史' },
                { field: 'chronic', title: '慢性病' },
                { field: 'allergy', title: '过敏史' },
                { field: 'serious', title: '大病史' },
                { field: 'medical_insurance', title: '医保' },
                { field: 'disease_situation', title: '病症情况' },
                { field: 'first_cure', title: '首次诊疗日期' },
                { field: 'last_cure', title: '最后诊疗日期' },
                { field: 'createdate', title: '建档日期' },
                { field: 'createname', title: '建档人' },
                { field: 'modifydate', title: '最后修改日期' },
                { field: 'modifyname', title: '最后修改人' },
                { field: 'file_number', title: '档案编号' },
                { field: 'remarks', title: '备注' },
			]],
            pagination: true,
            rownumbers: true,  
            fitColumns: true,
			onDblClickCell: function (index, field, value) {
                window.location.href = '/index.php/index/cure/patient_edit?id=' + grid.datagrid('getRows')[index]['id'];
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