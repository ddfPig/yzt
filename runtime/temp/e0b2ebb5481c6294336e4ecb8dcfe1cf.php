<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\account\zdmanage.html";i:1538185124;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>医诊通V1.0.0</title>
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
<body>
<div class="margin-bottom-5 nowrap">
    <input id="keyword" type="text" placeholder="请输入查询关键字" />
    <a id="search" class="easyui-linkbutton" iconcls="icon-search">查询</a>
</div>
<div id="div_data">
    <div region="center" style="width: 100%; height: 370px; padding: 1px; overflow-y: hidden">
        <div id="dwgrid">
        </div>
    </div>
</div>
<script type="text/javascript">
    var grid;
    $(document).ready(function () {
        List();
    });
    $("#search").click(function () {
        List();
    })
    function List() {
        grid = $('#dwgrid').datagrid({
            method: 'get',
            url:"<?php echo url('index/account/zdList'); ?>?keyword="+encodeURI($("#keyword").val()),
            sortName: 'id',
            sortOrder: 'desc',
            idField: 'id',
            pageSize: 10,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                { field: 'number', title: '编号', fit: true },
                { field: 'name', title: '姓名', fit: true },
                { field: 'phone', title: '电话', fit: true },
                { field: 'fixedphone', title: '固定电话', fit: true },
                { field: 'qq', title: '客服qq', fit: true },
                { field: 'email', title: '邮箱', fit: true }
            ]],
            fit: true,
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: true,
        });
    };
</script>

</body>
</html>