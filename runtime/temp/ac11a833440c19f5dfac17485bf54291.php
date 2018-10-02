<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\account\list.html";i:1538208285;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                <?php if($uinfo['role_short'] != 'kf'): ?>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/account/listadd'); ?>">新建</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="editUser()">修改</a>


                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="tranTo()">移交</a>

                <a class="easyui-linkbutton setStatus" data-options="iconCls:'icon-ok'" ty="1">开通</a>
                <a class="easyui-linkbutton setStatus" data-options="iconCls:'icon-del'" ty="2">锁定</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="reward()">储值奖励开关</a>
                <?php endif; if($uinfo['role_short'] != 'zd'): ?>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="tranTo()">移交</a>
                <?php endif; ?>

                <select id="status" name="status" style="height:24px;line-height:24px; width:auto">
                    <option value="">按状态筛选</option>
                    <option value="1">已启用</option>
                    <option value="0">已停用</option>
                </select>
                <input id="statusid" name="statusid" type="hidden" />

                <select id="types" name="types" style="height:24px;line-height:24px; width:auto">
                    <option value="">按类型筛选</option>
                    <option value="2">单体</option>
                    <option value="1">连锁</option>
                </select>

                <input id="tyid" name="tyid" type="hidden" />


                <input type="text" id="keyword" name="keyword" placeholder="请输入查询关键字" style="width:200px" />
                <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="search" name="search">查找</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" id="toExcel"  onclick="Execl(this)">导出</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">

                <div id="rangegrid" style="min-height:500px; width:100%;">

            </div>

        </div>
</div>
</body>
<script>

    //导出
    function Execl(obj) {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var status = $("#status").val();
            var types = $("#types").val();
            var keyword = $("#keyword").val();
            if (r) {
                window.location.href = "<?php echo url('index/account/dataToExcel'); ?>?status="+status+"&types="+types+"&keyword="+keyword;
            }
        });
    }
    





    grid = $('#rangegrid').datagrid({
        methord: 'get',
        url: "<?php echo url('index/account/lists2'); ?>",
        sortName: 'shop_id',
        sortOrder: 'desc',
        idField: 'shop_id',
        pageSize: 30,
        showFooter: true,
        frozenColumns: [[
            { field: 'ck', checkbox: true }
        ]],
        columns: [[
            { field: 'shop_status', title: '状态', fit: true },
            { field: 'shop_type', title: '用户类型', fit: true },
            { field: 'shop_number', title: '用户编码', fit: true },
            { field: 'shop_name', title: '用户全称', fit: true },
            { field: 'province', title: '省份', fit: true },
            { field: 'city', title: '城市', fit: true },
            { field: 'town', title: '区县', fit: true },
            { field: 'contactor', title: '联系人', fit: true },
            { field: 'mobile', title: '联系电话', fit: true },
            { field: 'open_time', title: '开通时间', fit: true },
            { field: 'busniss_date', title: '到期时间', fit: true },
            { field: 'customor_id', title: '客服', fit: true },
            { field: 'terminator_id', title: '终端经理', fit: true },

        ]],
        fit: true,
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        singleSelect: false,
        onDblClickCell: onDBClickCell,
        onAfterEdit: function (rowIndex, rowData, changes) {
            //endEdit该方法触发此事件
            location.href = "<?php echo url('index/account/listEdit'); ?>?aid=" + rowData.shop_id;
        }
    });

    //储值奖励开关
    function reward() {

        var row = $('#rangegrid').datagrid('getSelected');
        if (row == null){
            $.messager.alert('提示',"请选择一行进行编辑",'warning');
            return false;
        }
        var shop_id = row.shop_id;
        var shop_name = row.shop_name;
        var shop_number = row.shop_number;
        window.location.href =  "<?php echo url('index/ini/reward_list'); ?>?shop_id="+shop_id+"&shop_name="+shop_name+"&shop_number="+shop_number;
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
            window.location.href ="<?php echo url('index/account/listEdit'); ?>?aid="+ row.shop_id;
        }
    }

    /**
     * 诊所移交
     */
    function tranTo()
    {
        var row = $('#rangegrid').datagrid('getSelected');
        if (getSelectedArr(grid).length > 1) {
            $.messager.alert('提示', '只能选择一条数据进行修改', 'error'); return;
        }
        if (getSelectedArr(grid).length < 1) {
            $.messager.alert('提示', '您未选择修改数据', 'error'); return;
        }
        if (row){
            window.location.href ="<?php echo url('index/account/transformShop'); ?>?aid="+ row.shop_id;
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

    $("#open").click(function () {
        var arr = getSelectedArr();
        if (IsSubmit) {
            IsSubmit = false;
            $.post("<?php echo url('index/account/accountCheck'); ?>",{arr:arr},
                function (result) {
                    var results = eval('('+result+')');
                    console.info(results);
                    IsSubmit = true;
                    if (results.code == 1) {
                        $.messager.alert('用户审核设置',results.msg,'info',function(){
                            window.location.href = "<?php echo url('index/account/lists'); ?>";
                        });

                    }
                    else {
                        $.messager.alert('用户审核设置',results.msg,'warning');
                    }
                }, "json");
        }
    });

    $(".setStatus").click(function () {
        var arr = getSelectedArr();
        var ty = $(this).attr('ty');
        if (IsSubmit) {
            IsSubmit = false;
            $.post("<?php echo url('index/account/accountStatus'); ?>",{arr:arr,ty:ty},
                function (result) {
                    var results = eval('('+result+')');
                    IsSubmit = true;
                    if (results.code == 1) {
                        $.messager.alert('用户启用状态设置',results.msg,'info',function(){
                            window.location.href = "<?php echo url('index/account/lists'); ?>";
                        });
                    }
                    else {
                        $.messager.alert('用户启用状态设置',results.msg,'warning');
                    }
                }, "json");
        }
    });

    //储值奖励开关
    function reward() {

        var row = $('#rangegrid').datagrid('getSelected');
        if (row == null){
            $.messager.alert('提示',"请选择一行进行编辑",'warning');
            return false;
        }
        var shop_id = row.shop_id;
        var shop_name = row.shop_name;
        var shop_number = row.shop_number;
        window.location.href =  "<?php echo url('index/ini/reward_list'); ?>?shop_id="+shop_id+"&shop_name="+shop_name+"&shop_number="+shop_number;
    }


    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }

    function getSelectedArr() {
        var rows = $('#rangegrid').datagrid('getSelections');
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].shop_id);
        }
        return ids;
    }
</script>
</html>