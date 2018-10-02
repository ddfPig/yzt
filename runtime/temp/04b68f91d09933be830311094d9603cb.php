<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\rule\index.html";i:1538208811;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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

    <script src="/public/static/easyui/plugins/jquery.tree.js"></script>
    <link href="/public/static/easyui/themes/bootstrap/tree.css" rel="stylesheet" />
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/rule/addRule'); ?>">新建</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">
                <table id="treegrid" style="width:100%;min-height:300px;"></table>
            </div>

    </div>
</body>
<script>
    var State = [
        { Value: "0", Title: "停用" },
        { Value: "1", Title: "启用" },
    ];
    var editIndex;
    $(document).ready(function () {
        $('#treegrid').treegrid({
            url: "<?php echo url('index/rule/lists'); ?>",
            idField: 'id',
            treeField: 'title',
            animate: true,
            lines: true,
            columns: [[
                { field: 'title', title: '菜单名称', editor: { type: 'text' } },
                {
                    field: 'status', title: '状态', width: '100px', editor: {
                        type: 'combobox', options: {
                            valueField: 'Value',
                            textField: 'Title',
                            data: State,
                            editable: false,
                            limitToList: true,
                            required: false,
                            editable: false,
                        }
                    }, formatter: function (value, row, index) {
                        switch (value) {
                            case 0: return "停用"; break;
                            case 1: return "启用"; break;
                        }
                        return "未配置";
                    }
                },
                { field: 'name', title: '访问地址', editor: { type: 'text' } },
                { field: 'sort', title: '排序', width: '50px', editor: { type: 'numberbox', options: { precision: 0 } } },

            ]],
            onLoadSuccess: function (row, data) {

                $('#treegrid').treegrid('collapseAll');
                //alert('加载成功');
            },
            onLoadError: function (arguments) {
                //alert('加载失败');
            }
        }).datagrid('enableCellEditing');

        $("#btnSave").click(function () {
           Add();
        });
    });
    $.extend($.fn.datagrid.methods, {
        editCell: function (jq, param) {
            return jq.each(function () {
                var opts = $(this).datagrid('options');
                var fields = $(this).datagrid('getColumnFields', true).concat($(this).datagrid('getColumnFields'));
                for (var i = 0; i < fields.length; i++) {
                    var col = $(this).datagrid('getColumnOption', fields[i]);
                    col.editor1 = col.editor;
                    if (fields[i] != param.field) {
                        col.editor = null;
                    }
                }
                editIndex = param.index;
                $(this).datagrid('beginEdit', param.index);
                var ed = $(this).datagrid('getEditor', param);
                if (ed) {

                    if (param.field == "title") {
                        $(ed.target).textbox({
                            onChange: function (newValue, oldValue) {
                                if (OnSave(editIndex, 'title', newValue)) {
                                    $.messager.alert("操作提示", "保存成功！");
                                }
                                else {
                                    $(ed.target).textbox('setValue', oldValue);
                                    $.messager.alert("操作提示", "保存失败！", "error");
                                }
                                $('#treegrid').treegrid('endEdit', editIndex);
                            }
                        });
                        if ($(ed.target).hasClass('textbox-f')) {
                            $(ed.target).textbox('textbox').focus();
                        } else {
                            $(ed.target).focus();
                        }
                    }
                    else if (param.field == "status") {
                        $(ed.target).combobox({
                            onChange: function (newValue, oldValue) {
                                if (newValue != $('#treegrid').treegrid('find', editIndex).State) {
                                    if (OnSave(editIndex, 'status', newValue)) {
                                        $.messager.alert("操作提示", "保存成功！");
                                        $('#treegrid').treegrid('endEdit', editIndex);
                                    }
                                    else {
                                        $(ed.target).combobox('setValue', oldValue);
                                        $.messager.alert("操作提示", "保存失败！", "error");
                                        $('#treegrid').treegrid('endEdit', editIndex);
                                    }
                                }
                            }
                        });
                        $(ed.target).combobox("select", $('#treegrid').treegrid('find', editIndex).State);
                    }
                    else if (param.field == "name") {
                        $(ed.target).textbox({
                            onChange: function (newValue, oldValue) {
                                if (OnSave(editIndex, 'name', newValue)) {
                                    $.messager.alert("操作提示", "保存成功！");
                                }
                                else {
                                    $(ed.target).textbox('setValue', oldValue);
                                    $.messager.alert("操作提示", "保存失败！", "error");
                                }
                                $('#treegrid').treegrid('endEdit', editIndex);
                            }
                        });
                        if ($(ed.target).hasClass('textbox-f')) {
                            $(ed.target).textbox('textbox').focus();
                        } else {
                            $(ed.target).focus();
                        }
                    }
                    else if (param.field == "Params") {
                        $(ed.target).textbox({
                            onChange: function (newValue, oldValue) {
                                if (OnSave(editIndex, 'Params', newValue)) {
                                    $.messager.alert("操作提示", "保存成功！");
                                }
                                else {
                                    $(ed.target).textbox('setValue', oldValue);
                                    $.messager.alert("操作提示", "保存失败！", "error");
                                }
                                $('#treegrid').treegrid('endEdit', editIndex);
                            }
                        });
                        if ($(ed.target).hasClass('textbox-f')) {
                            $(ed.target).textbox('textbox').focus();
                        } else {
                            $(ed.target).focus();
                        }
                    }
                    else if (param.field == "sort") {
                        $(ed.target).numberbox({
                            onChange: function (newValue, oldValue) {
                                if (OnSave(editIndex, 'sort', newValue)) {
                                    $.messager.alert("操作提示", "保存成功！");
                                }
                                else {
                                    $(ed.target).numberbox('setValue', oldValue);
                                    $.messager.alert("操作提示", "保存失败！", "error");
                                }
                                $('#treegrid').treegrid('endEdit', editIndex);
                            }
                        });
                    }
                }
                for (var i = 0; i < fields.length; i++) {
                    var col = $(this).datagrid('getColumnOption', fields[i]);
                    col.editor = col.editor1;
                }
            });
        },
        enableCellEditing: function (jq) {
            return jq.each(function () {
                var dg = $(this);
                var opts = dg.datagrid('options');
                opts.oldOnClickCell = opts.onClickCell;
                opts.onClickCell = function (index, field) {
                    if (opts.editIndex != undefined) {
                        if (dg.datagrid('validateRow', opts.editIndex)) {
                            dg.datagrid('endEdit', opts.editIndex);
                            opts.editIndex = undefined;
                        } else {
                            return;
                        }
                    }
                    dg.datagrid('selectRow', index).datagrid('editCell', {
                        index: index,
                        field: field
                    });
                    opts.editIndex = index;
                    opts.oldOnClickCell.call(this, index, field);
                }
            });
        }
    });

    function reloadurl()
    {
        $('#treegrid').treegrid('reload');
    }


    function OnSave(ID, Field, Value) {
        var IsSuccess = false;
        $.messager.progress({ title: '操作提示', msg: '数据提交中,请稍后...', text: 'Loading...' });
        $.ajax({
            url: "<?php echo url('index/rule/updateRule'); ?>",
            type: "post",
            data: { ID: ID, Field: Field, Value: Value },
            async: false,
            success: function (result) {
                IsSuccess = true;
                $.messager.progress('close');
                if (result.code == 1) {
                    //$.messager.alert("操作提示", "保存成功！");
                }
                else {
                    //$.messager.alert("操作提示", "保存失败！", "error");
                }
            },
            error: function (e) {
                $.messager.progress('close');
                //alert(e);
                //$.messager.alert("操作提示", "网络连接异常！", "error");
            }
        });
        return IsSuccess;
    }
</script>
</html>