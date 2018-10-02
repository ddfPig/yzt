<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\clinicbase\win_medicine.html";i:1537513028;}*/ ?>
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
    <input id="keyword" type="text" placeholder="请输入搜索证书类型" />
    <a id="search" class="easyui-linkbutton" iconcls="icon-search">检索</a>
</div>
<div id="div_data">
    <div region="center" style="width: 100%; height: 320px; padding: 1px; overflow-y: hidden">
        <div id="selectMedi">
        </div>
    </div>
</div>
<script type="text/javascript">
	var selectMedi;
    $(function () {
        
        $(document).ready(function () {
            List();
        });
        $("#search").click(function () {
            List();
        })
		
        function List() {
			selectMedi = $('#selectMedi').datagrid({   
				url:"/public/index.php/index/clinicapi/zz_lists?keyword="+encodeURI($("#keyword").val()),
				pageSize: 10,
				fit: true,
				frozenColumns: [[
					{ field: 'ck', checkbox: true }
				]], 
				columns:[[
					{field:'zzgl_name',title:'证书类型',},    
					{field:'zzgl_id',title:'证书编号',},    
				]],
				pagination: true,
				rownumbers: true,  
				fitColumns: false,
				singleSelect: false,
				onDblClickCell: onDBClickCell,
			});
		
		
		
        };
	});
	
</script>

</body>
</html>