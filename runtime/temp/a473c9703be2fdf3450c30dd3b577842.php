<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\qualificationname\index.html";i:1538278189;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>医诊通V1.0.0</title>
    <link rel="stylesheet" href="/public/static/css/bootstrap.css" />
    <link rel="stylesheet" href="/public/static/css/css.css" />
    <link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/public/static/images/favicon.ico" />
    <script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
    <script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public/static/easyui/jquery.edatagrid.js"></script>
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
	
            <form>
                <div class="margin-bottom-5">
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/qualificationname/qua_add'); ?>">新建</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="xg">修改</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="btnSave">更新排序</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="qy">启用</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-del'" id="ty">停用</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" id="dc">导出</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" id="reload" name="reload">刷新</a>
                </div>

                <div id="divpadding" class="margin-top-5" style="clear:both">
                    <div id="rangegrid" style="width:100%;min-height:700px">
                    </div>
                    <!--新建一个隐藏域-->
                    <input type="hidden" name="updateinfo" id="updateinfo"/>

                </div>
            </form>
</div>
<script type="text/javascript" >
    var grid;
    var mustcolumnstyle = 'background-color:#b2def4;color:#000;';
    var editIndex = undefined;
    function endEditing() {
        if (editIndex == undefined) { return true }
        if ($('#rangegrid').datagrid('validateRow', editIndex)) {
            $('#rangegrid').datagrid('endEdit', editIndex);
            editIndex = undefined;
            return true;
        } else {
            return false;
        }
    }

    $(function(){

        //刷新
        $("#reload").click(function () {
            $('#rangegrid').datagrid("uncheckAll");
            grid.datagrid('reload');
        });

        grid = $('#rangegrid').datagrid({
            methord: 'get',
            url: "<?php echo url('index/qualificationname/ini'); ?>",
            sortOrder: 'desc',
            idField: 'id',
            pageSize: 50,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                {
                    field: 'zzgl_state', title: '状态'
                },
                {
                    field: 'zzgl_id', title: '证书编号'
                },
                {
                    field: 'zzgl_name', title: '证书名称'
                },
                {
                    field: 'sort', title: '排序', editor: 'text',
                },
                {
                    field: 'id', title: '主键', hidden:"hidden",
                }
            ]],
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: false,
            onDblClickCell: function (index, field, value) {
                window.location.href = '/index.php/index/qualificationname/edit' +
                    '?id=' + grid.datagrid('getRows')[index]['id'];
            },
            onClickCell: function (index, field, value) {
                endEditing();
                if (field == "sort") {
                    onClickCell(index, field);
                }
            }
        });

        function onClickCell(index, field) {
            if (editIndex != index) {
                if (endEditing()) {
                    $('#rangegrid').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);

                    var ed = $('#rangegrid').datagrid('getEditor', { index: index, field: field });
                    if (ed) {
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    editIndex = index;
                } else {
                    $('#rangegrid').datagrid('selectRow', editIndex);
                }
            }
        }

        $("#dc").click(function (){
            //导出
            $.messager.confirm('确认', '您确认要导出吗？', function (r) {
                if (r) {
                    window.location.href = "<?php echo url('index/qualificationname/daochu'); ?>";
                }
            });
        });

        $("#btnSave").click(function () {
            //Loading(true);
            endEditing();
            var $dg = $("#rangegrid");
            var rows = $dg.datagrid('getRows');
            for (var i = 0; i < rows.length; i++) {
                if (!rows[i]['sort']) {
                    // Loading(false)
                    $.messager.alert("提示", "排序不能为空！",'warning');
                    return;
                }
            }
            if ($dg.datagrid('getChanges').length) {
                var updated = $dg.datagrid('getChanges', "updated");
                if (updated.length && updated != undefined) {
                    $("#updateinfo").val(JSON.stringify(updated));
                }
            }

            UpdateList();
        });
        function UpdateList() {
            $.ajax({
                url:"<?php echo url('index/qualificationname/sort'); ?>",
                type: "post",
                data: $("form").serialize(),
                success: function (s) {
                    var results = eval('('+s+')');
                    if (results.code == 1) {
                        $.messager.alert("操作提示", results.Msg, "info", function () {
                            window.location.href = "<?php echo url('index/qualificationname/index'); ?>";

                        });
                    }
                    else
                        $.messager.alert('操作提示', results.Msg, 'error');
                },
                error: function (e) {
                    showMsg('操作提示', s.Msg, 'error');
                }
            })
        };

        //修改按钮
        $('#xg').click(function () {
            var rows = $('#rangegrid').datagrid('getSelections');
            if (rows.length != 1){
                $.messager.alert('提示','请选择一行进行编辑','warning');
                return;
            }
            for(var i=0; i<rows.length; i++){
                var id = rows[i]['id'];
            }
            window.location.href = ' /index.php/index/qualificationname/edit?id=' + id;
        });

        //启用按钮
        $('#qy').click(function(){
            var ids=[];
            var rows = $('#rangegrid').datagrid('getSelections');
            console.log(rows);
            if(rows==""){
                $.messager.alert('提示','请选择一条数据','warning');
                return;
            }
            for (var i = 0; i < rows.length; i++) {
                ids.push(rows[i].id);
            }
            $.ajax({
                url: "<?php echo url('index/qualificationname/qy'); ?>",
                type: "post",
                data: {ids:ids},
                success: function (s) {
                    if (s.error == 1) {
                        $.messager.alert('提示','启用成功','info');
                        $("#rangegrid" ).datagrid('reload');
                    }else {
                        $.messager.alert('提示','启用失败','warning');
                    }
                },
            });
        });
        //停用按钮
        $('#ty').click(function(){
            var ids=[];
            var rows = $('#rangegrid').datagrid('getSelections');
            console.log(rows);
            if(rows==""){
                $.messager.alert('提示','请选择一条数据','warning');
                return;
            }
            for (var i = 0; i < rows.length; i++) {
                ids.push(rows[i].id);
            }
            $.ajax({
                url: "<?php echo url('index/qualificationname/ty'); ?>",
                type: "post",
                data: {ids:ids},
                success: function (s) {
                    if (s.error == 1) {
                        $.messager.alert('提示','停用成功','info');
                        $("#rangegrid" ).datagrid('reload');
                    }else {
                        $.messager.alert('提示','停用失败','warning');
                    }
                },
            });
        });
    });
</script>
</body>
</html>