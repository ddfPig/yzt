<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\role\index.html";i:1537497318;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    首页<span class="divider">/</span>医诊通用户<span class="divider">/</span>用户组管理
</ul>
<div class="title_right"><strong>用户组管理</strong></div>
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/role/addRole'); ?>">新建</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="editUser()">修改</a>
                <a class="easyui-linkbutton setStatus" data-options="iconCls:'icon-ok'" ty="1">启用</a>
                <a class="easyui-linkbutton setStatus" data-options="iconCls:'icon-del'" ty="2">停用</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">

                <div id="rangegrid" style="min-height:500px; width:100%;">

                </div>

            </div>
    </div>
</body>
<script>


    grid = $('#rangegrid').datagrid({
        methord: 'get',
        url: "<?php echo url('index/role/lists'); ?>",
        sortName: 'id',
        sortOrder: 'desc',
        idField: 'id',
        pageSize: 30,
        showFooter: true,
        frozenColumns: [[
            { field: 'ck', checkbox: true }
        ]],
        columns: [[
            { field: 'title', title: '用户组名称', fit: true },
            { field: 'status', title: '显示状态', fit: true },
            { field: 'addtime', title: '添加时间', fit: true },
            {
                field: 'rules', title: '权限管理', formatter: function (value, row, index) {
                    return '<a type="button" class="btn btn-info btn-mini" onclick="OnRoleLimit(\'' + row.id + '\');">分配权限</a>';
                }
            },

        ]],
        fit: true,
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        singleSelect: false,
        onDblClickCell: onDBClickCell,
        onAfterEdit: function (rowIndex, rowData, changes) {
            //endEdit该方法触发此事件
            location.href = "<?php echo url('index/role/editRole'); ?>?gid=" + rowData.id;
        }
    });


    function OnRoleLimit(RoleID)
    {
        window.location.href ="<?php echo url('index/rule/setRule'); ?>?RoleID=" + RoleID;
    }




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
            window.location.href ="<?php echo url('index/role/editRole'); ?>?gid="+ row.id;
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
        $("#statusid").val($(this).find("option:selected").val());
        $("#rangegrid").datagrid('options').page = 1;
        var queryParams = $("#rangegrid").datagrid('options').queryParams;
        queryParams.status = $("#status").val();
        $("#rangegrid").datagrid('options').queryParams = queryParams;
        $("#rangegrid").datagrid('reload');
    });

    $("#types").change(function () {
        $("#tyid").val($(this).find("option:selected").val());
        $("#rangegrid").datagrid('options').page = 1;
        var queryParams = $("#rangegrid").datagrid('options').queryParams;
        queryParams.tyid = $("#tyid").val();
        $("#rangegrid").datagrid('options').queryParams = queryParams;
        $("#rangegrid").datagrid('reload');
    });

    var IsSubmit = true;

    $(".setStatus").click(function () {
        var arr = getSelectedArr();
        var ty = $(this).attr('ty');
        if (IsSubmit) {
            IsSubmit = false;
            $.post("<?php echo url('index/role/setStatus'); ?>",{arr:arr,ty:ty},
                function (result) {
                    var results = eval('('+result+')');
                    IsSubmit = true;
                    if (results.code == 1) {
                        $.messager.alert('用户组状态设置提示',results.msg,'info',function(){
                            window.location.href = "<?php echo url('index/role/index'); ?>";
                        });
                    }
                    else {
                        $.messager.alert('用户组状态设置提示',results.msg,'warning');
                    }
                }, "json");
        }
    });

    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }

    function getSelectedArr() {
        var rows = $('#rangegrid').datagrid('getSelections');
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].id);
        }
        return ids;
    }
</script>
</html>