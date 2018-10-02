<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\purback\index.html";i:1537855511;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1537495602;}*/ ?>
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
    首页<span class="divider">/</span>业务管理<span class="divider">/</span>退货管理
</ul>
<div class="title_right"><strong>退货管理</strong></div>
<div style="position:absolute; right:15px;top:4px;">
    <span id="span_current_userinfo">
         Hi,
                    <?php if($uinfo['role_short'] =='cj'): ?>
                         <?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/purback/backAdd'); ?>">新建</a>
                <a onclick="editPur()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'" href="#">修改</a>
                <input type="text" placeholder="请输入查询关键字" name="keyword" id="keyword" style="width:200px" />
                <a id="a_search" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" id="toExcel"  onclick="Execl(this)">导出</a>
                <a onclick="reloadurl()" class="easyui-linkbutton" data-options="iconCls:'icon-refresh'">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">
                <div id="rangegrid" style="min-height:500px; width:100%;">
                </div>
            </div>
</div>
</body>
<script type="text/javascript">

    //导出
    function Execl(obj) {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
           // var pay = $("#pay").val();
            var keyword = $("#keyword").val();
            if (r) {
                window.location.href = "<?php echo url('index/purback/dataToExcel'); ?>?keyword="+keyword;
            }
        });
    }


    var ids = [];
    var grid;
    var IsSubmit = true;
    $(function () {
        grid = $('#rangegrid').datagrid({
            methord: 'get',
            url: "<?php echo url('index/purback/lists'); ?>",
            sortName: 'backpur_id',
            sortOrder: 'desc',
            idField: 'backpur_id',
            pageSize: 30,
            showFooter: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                { field: 'backpur_number', title: '单据编号', fit: true },
                { field: 'backpur_status', title: '单据状态', fit: true },
                { field: 'create_time', title: '创建日期', fit: true },
                { field: 'confirm_time', title: '确认日期', fit: true },
                { field: 'operater', title: '操作人', fit: true },
                { field: 'supplier_code', title: '供货单位编码', fit: true },
                { field: 'SupplyName', title: '供货单位名称', fit: true },
                { field: 'contactor', title: '联系人', fit: true },
                { field: 'contactor_phone', title: '联系电话', fit: true },
                { field: 'backmoney', title: '退货总金额', fit: true },
                { field: 'ytbackmoney', title: '应退总金额', fit: true },
                { field: 'print_time', title: '最后打印日期', fit: true },
                { field: 'printor', title: '最后打印人', fit: true },
                { field: 'info', title: '备注', fit: true },
            ]],
            fit: true,
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: false,
            onDblClickCell: onDBClickCell,
            onAfterEdit: function (rowIndex, rowData, changes) {
                //endEdit该方法触发此事件
                location.href = "<?php echo url('index/purback/backEdit'); ?>?pid=" + rowData.backpur_number;
            }
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

        $("#a_search").click(search);

        //搜索
        function search() {
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
        }




    });

    function getSelectedArr() {
        var rows = $('#rangegrid').datagrid('getSelections');
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].pid);
        }
        return ids;
    }

    //刷新
    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }

    //修改
    function editPur(){
        var row = $('#rangegrid').datagrid('getSelected');

        if (getSelectedArr(grid).length > 1) {
            $.messager.alert('提示', '只能选择一条数据进行修改', 'error'); return;
        }
        if (getSelectedArr(grid).length < 1) {
            $.messager.alert('提示', '您未选择修改数据', 'error'); return;
        }


        if (row){
            window.location.href ="<?php echo url('index/purback/backEdit'); ?>?pid="+ row.backpur_number;
        }
    }

</script>
</html>