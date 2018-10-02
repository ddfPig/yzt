<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\acography.html";i:1537925741;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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


<style>*{font-size:12px}</style>
</head>
<body>

<ul class="breadcrumb">
    首页<span class="divider">/</span>诊疗管理<span class="divider">/</span>诊疗记录管理
</ul>
<div class="title_right"><strong>诊疗记录管理</strong></div>
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
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/cure/acography_add'); ?>">新建</a>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="update">修改</a>
						<a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
                      <select style="height:24px;line-height:24px; width:auto" id="status" onchange="javascript:search()">
                            <option value=-1>按状态筛选</option>
                            <option value=0>待确认</option>
                            <option value=1>待收款</option>
                            <option value=2>已完成</option>
                        </select>
                      <select style="height:24px;line-height:24px; width:auto" id="confirm_id" onchange="javascript:search()">
                            <option value=-1>按诊疗人筛选</option>
                            <?php if(is_array($result) || $result instanceof \think\Collection || $result instanceof \think\Paginator): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
							<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <input type="text" style="width:120px;" placeholder="请选择开始日期" id="start_date" data-options="formatter:formatter,editable:false" class="easyui-datebox" style="width:170px" >
                        <input type="text" style="width:120px;" placeholder="请选择截止日期" id="end_date" data-options="formatter:formatter,editable:false" class="easyui-datebox" style="width:170px" >
                        <input type="text" placeholder="请输入查询关键字" style="width:200px" id="keyword"/>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" href="javascript:search()">查询</a>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" onclick="javascript:Execl();">导出</a>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                    </div>

            
<div style="width:100%;height:85%;" >
            <div id="grid" style="width:100%;height:100%; ">

			</div>
</div>



</div>

</body>
<script>
	//导出
    function Execl() {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var confirm_id = $("#confirm_id").val();
            var keyword = $("#keyword").val();
			var status = $("#status").val();
			var start_date = $("#start_date").datebox('getValue');
			var end_date = $("#end_date").datebox('getValue');
            if (r) {
                window.location.href = "/index/cure/acography_out.html?confirm_id="+confirm_id+"&keyword="+keyword+"&status="+status+"&start_date="+start_date+"&end_date="+end_date;
            }
        });
    }
		//修改日历框的显示格式
        function formatter(date){
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var hour = date.getHours();
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;
            return year + "-" + month + "-" + day;
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
			window.location.href = '/index.php/index/cure/acography_edit?id=' + getSelectedArr(grid);
        })

        //$("#search").click(function () {
		function search(){
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            queryParams.confirm_id = $("#confirm_id").val();
            queryParams.status = $("#status").val();
            queryParams.start_date = $("#start_date").datebox('getValue');
            queryParams.end_date = $("#end_date").datebox('getValue');
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
		}
        //})
	
	
		grid = $('#grid').datagrid({   
			url:'/index.php/index/cure/acography_search?ran=' + Math.random() + '&keyword=' + encodeURI($("#keyword").val()),
            pageSize: 50,
			fit: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]], 
			columns:[[
                { field: 'status', title: '诊疗状态' },
                { field: 'department_id', title: '诊疗科室' },
                { field: 'number', title: '诊疗记录编号' },
                { field: 'patient_number', title: '患者编号' },
                { field: 'name', title: '姓名' },
                { field: 'sex', title: '性别' },
                { field: 'age', title: '年龄' },
                { field: 'disease', title: '基本病症' },
                { field: 'content', title: '问诊内容' },
                { field: 'result', title: '诊疗结果' },
                { field: 'confirm_name', title: '诊疗人' },
                { field: 'confirm_date', title: '诊疗时间' },
                { field: 'remarks', title: '备注' },
			]],
            pagination: true,
            rownumbers: true,  
            fitColumns: true,
			onDblClickCell: function (index, field, value) {
                window.location.href = '/index.php/index/cure/acography_edit?id=' + grid.datagrid('getRows')[index]['id'];
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
</html>