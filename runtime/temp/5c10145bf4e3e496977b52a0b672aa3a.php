<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\win_card.html";i:1538201777;}*/ ?>
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



</head>
<body><div class="margin-bottom-5 nowrap">
    <input id="keyword" type="text" placeholder="请输入查询关键字" />
    <a id="search" class="easyui-linkbutton" iconcls="icon-search">查询</a>
</div>
<div id="div_data">
    <div region="center" style="width: 100%; height: 330px; padding: 1px; overflow-y: hidden">
        <div id="selectGrid">
        </div>
    </div>
</div>
<script type="text/javascript">
	var selectGrid;
    $(function () {
        
        $(document).ready(function () {
            List();
        });
        $("#search").click(function () {
            List();
        })
        function List() {
			selectGrid = $('#selectGrid').datagrid({   
				url:'/index.php/index/cure/patient_card_api?ran=' + Math.random() + '&keyword=' + encodeURI($("#keyword").val()) ,
				pageSize: 10,
				fit: true,
				frozenColumns: [[
					{ field: 'ck', checkbox: true }
				]], 
				columns:[[
					{ field: 'card', title: '诊疗卡号' },
				]],
				pagination: true,
				rownumbers: true,
				fitColumns: true,

			});
		
		
        };
	});

</script>

</body>
</html>