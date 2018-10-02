<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cards\index.html";i:1538206836;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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

        <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/cards/addCards'); ?>">新建</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" href="<?php echo url('index/cards/allotCards'); ?>">发卡</a>

        <select id="ShopID" name="ShopID" style="height:24px;line-height:24px; width:auto">
            <option value="">按门店筛选</option>

        </select>
        <input id="statusid" name="statusid" type="hidden" />

        <select id="status" name="status" style="height:24px;line-height:24px; width:auto">
            <option value="">按状态筛选</option>
            <option value="0">未使用</option>
            <option value="1">已使用</option>
            <option value="-1">已作废</option>
        </select>

        <input id="tyid" name="tyid" type="hidden" />


        <input type="text" id="keyword" name="keyword" placeholder="请输入查询关键字" style="width:200px" />
        <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="search" name="search">查找</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" id="toExcel"  onclick="Execl(this)">导出</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>
    </div>

    <div id="divpadding" class="margin-top-5" style="clear:both">

        <div id="rangegrid" style="min-height:650px; width:100%;">

        </div>

    </div>
</div>
</body>
<script>

    //导出
    function Execl(obj) {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var ShopID = $("#ShopID").val();
            var status = $("#status").val();
            var keyword = $("#keyword").val();
            if (r) {
                window.location.href = "<?php echo url('index/cards/excelTo'); ?>?ShopID="+ShopID+"&status="+status+"&keyword="+keyword;
            }
        });
    }


    $(document).ready(function () {
        $("#ShopID option").remove();
        $("#ShopID").append(" <option value=''>按门店筛选</option>");
        $("#ShopID").append(" <option value='0'>没有门店的卡号</option>");
        $.getJSON("<?php echo url('index/cards/getShopsList'); ?>", {}, function (data) {
            $.each(data, function (i, item) {
                $("#ShopID").append(" <option value='" + item.shop_id + "'>" + item.shop_name + "</option>");

            });
        });
    });




    grid = $('#rangegrid').datagrid({
        methord: 'get',
        url: "<?php echo url('index/cards/lists'); ?>",
        sortName: 'id',
        sortOrder: 'desc',
        idField: 'id',
        pageSize: 20,
        showFooter: true,
        frozenColumns: [[
            { field: 'ck', checkbox: true }
        ]],
        columns: [[
            { field: 'status', title: '状态', fit: true },
            { field: 'shop_name', title: '门店名称', fit: true },
            { field: 'shop_num', title: '门店编号', fit: true },
            { field: 'card_no', title: '生成编号', fit: true },
            { field: 'card', title: '会员卡号', fit: true },
            { field: 'create_person', title: '创建人', fit: true },
            { field: 'create_time', title: '创建日期', fit: true }
        ]],
        fit: true,
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        singleSelect: false,
    });



    var editIndex = undefined;

    function onDBClickCell(index, field) {
        if (endEditing()) {
            $('#rangegrid').datagrid('selectRow', index)
                .datagrid('beginEdit', index);
            editIndex = index;
            if ($('#rangegrid').datagrid('validateRow', editIndex)) {
                $('#rangegrid').datagrid('endEdit', editIndex);
            }

        }
    }
    function endEditing() {
        if (editIndex == undefined) { return true }
        if (grid.datagrid('validateRow', editIndex)) {
            grid.datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }

    var ids = [];
    function editUser(){
        var row = $('#rangegrid').datagrid('getSelected');
        if (getSelectedArr(grid).length > 1) {
            $.messager.alert('提示', '只能选择一条数据进行修改', 'error'); return;
        }
        if (getSelectedArr(grid).length < 1) {
            $.messager.alert('提示', '您未选择修改数据', 'error'); return;
        }
        if (row){
            window.location.href ="<?php echo url('index/account/listEdit'); ?>?aid="+ row.shop_id;
        }
    }


    $("#search").click(function () {
        $("#rangegrid").datagrid('options').page = 1;
        var queryParams = $("#rangegrid").datagrid('options').queryParams;
        queryParams.keyword = $("#keyword").val();
        $("#rangegrid").datagrid('options').queryParams = queryParams;
        $("#rangegrid").datagrid('reload');
    })

    $("#status").change(function () {
        $("#status").val($(this).find("option:selected").val());
        $("#rangegrid").datagrid('options').page = 1;
        var queryParams = $("#rangegrid").datagrid('options').queryParams;
        queryParams.status = $("#status").val();
        $("#rangegrid").datagrid('options').queryParams = queryParams;
        $("#rangegrid").datagrid('reload');
    });

    $("#ShopID").change(function () {
        $("#ShopID").val($(this).find("option:selected").val());
        $("#rangegrid").datagrid('options').page = 1;
        var queryParams = $("#rangegrid").datagrid('options').queryParams;
        queryParams.ShopID = $("#ShopID").val();
        $("#rangegrid").datagrid('options').queryParams = queryParams;
        $("#rangegrid").datagrid('reload');
    });



    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }

</script>
</html>