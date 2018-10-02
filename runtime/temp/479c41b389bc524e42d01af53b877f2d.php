<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\debt\index.html";i:1538207837;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>

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
                <a id="doconfirm" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="#">确认结算欠款</a>

                <select id="qktype" name="qktype" style="height:24px;line-height:24px; width:auto">
                    <option value="">全部欠款类型</option>
                    <option value="1">诊疗欠款</option>
                    <option value="2">采购欠款</option>

                </select>
                <select id="qkstatus" name="qkstatus" style="height:24px;line-height:24px; width:auto">
                    <option value="">单据状态</option>
                    <option value="1">已结算</option>
                    <option value="0">未结算</option>

                </select>
                日期:
                <input id="startdates" id="start" class="easyui-datebox" name="startdate" style="width:120px;" data-options="editable:false"><span>--</span>

                <input id="enddates" id="end" class="easyui-datebox" name="enddate"  style="width:120px;"  data-options="editable:false">
                <input type="text" placeholder="请输入查询关键字" id="keyword" name="keyword" style="width:200px" />
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
            var qktype = $("#qktype").val();
            var qkstatus = $("#qkstatus").val();
            var keyword = $("#keyword").val();
            var start = $("#start").val();
            var end = $("#end").val();
            if (r) {
                window.location.href = "<?php echo url('index/debt/dataToExcel'); ?>?qktype="+qktype+"&qkstatus="+qkstatus+"&keyword="+keyword+"&start="+start+"&end="+end;
            }
        });
    }


    var grid;
    $.fn.datebox.defaults.formatter = function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return y+'-'+m+'-'+d;
    }

    $.fn.datebox.defaults.parser = function(s){
        var t = Date.parse(s);
        if (!isNaN(t)){
            return new Date(t);
        } else {
            return new Date();
        }
    }


    $(function () {
        grid = $('#rangegrid').datagrid({
            methord: 'get',
            url: "<?php echo url('index/debt/lists'); ?>",
            sortName: 'arrears_id',
            sortOrder: 'desc',
            idField: 'arrears_id',
            pageSize: 30,
            showFooter: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                { field: 'arrears_type', title: '欠款类型', fit: true },
                { field: 'arrears_nums', title: '单据编号', fit: true },
                { field: 'status', title: '单据状态', fit: true },
                { field: 'create_time', title: '创建日期', fit: true },
                { field: 'arrears_time', title: '欠款日期', fit: true },
                { field: 'arrears_days', title: '欠款天数', fit: true },
                { field: 'arrears_money', title: '欠款金额', fit: true },
                { field: 'arrears_person', title: '欠款方', fit: true },
                { field: 'info', title: '备注', fit: true },

            ]],
            fit: true,
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: false,
            // onDblClickCell: onDBClickCell,
            // onAfterEdit: function (rowIndex, rowData, changes) {
            //     //endEdit该方法触发此事件
            //     location.href = "<?php echo url('index/purchase/purEdit'); ?>?pid=" + rowData.purnumbers;
            // }
        });


        $("#a_search").click(search);

        //搜索
        function search() {
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            var startTime=$("#startdates").datebox("getValue");
            var endTime=$("#enddates").datebox("getValue");
            if(endTime < startTime){
                $.messager.alert('提示','结束时间不能小于实施开始时间','question');
                return false;
            }

            queryParams.startdate = startTime;
            queryParams.enddate = endTime;

            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
        }
        //欠款类型
        $("#qktype").change(function () {
            $("#qktype").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.qktype = $("#qktype").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });

        //药品分类
        $("#qkstatus").change(function () {
            $("#qkstatus").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.qkstatus = $("#qkstatus").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });

        qkstatus


    });

    $("#doconfirm").click(function(){
        var arr = getSelectedArr();
        //if (IsSubmit) {
        //IsSubmit = false;
        $.post("<?php echo url('index/debt/confirmDebt'); ?>",{arr:arr},
            function (result) {
                var results = eval('('+result+')');
                //console.info(results);
                //IsSubmit = true;
                if (results.code == 1) {
                    $.messager.alert('欠款结算提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/debt/index'); ?>";
                    });

                }
                else {
                    $.messager.alert('欠款结算提示',results.msg,'warning');
                }
            }, "json");
        //}
    });

    var ids = [];
    function getSelectedArr() {
        var rows = $('#rangegrid').datagrid('getSelections');
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].arrears_id);
        }
        return ids;
    }


    //刷新
    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }
</script>
</html>