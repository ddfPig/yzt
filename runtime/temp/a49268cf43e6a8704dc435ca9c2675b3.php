<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\helpdocument\index.html";i:1538290928;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
            <div class="margin-bottom-5">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/helpdocument/documentadd'); ?>">新建</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="xg">修改</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-del'" id="sc">删除</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'"  id="reload" name="reload">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">


                <div id="rangegrid" style="width:100%;min-height:700px">
                </div>

            </div>

        </div>
    </div>
</div>
<script >
    $(function(){

         //刷新
        $("#reload").click(function () {
            $('#rangegrid').datagrid("uncheckAll");
            grid.datagrid('reload');
        });

        grid = $('#rangegrid').datagrid({
            methord: 'get',
            url: "<?php echo url('index/helpdocument/ini'); ?>",
            sortOrder: 'desc',
            idField: 'id',
            pageSize: 50,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                {
                    field: 'name', title: '分类'
                },
                {
                    field: 'bzwdgl_title', title: '标题'
                },
                {
                    field: 'bzwdgl_contents', title: '内容'
                },
                {
                    field: 'bzwdgl_customize_time', title: '时间'
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
                window.location.href = '/index.php/index/helpdocument/edit' +
                    '?id=' + grid.datagrid('getRows')[index]['id'];
            },
        });



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
            window.location.href = ' /index.php/index/helpdocument/edit?id=' + id;
        });

        //删除按钮
        $('#sc').click(function () {

            var rows = $('#rangegrid').datagrid('getSelections');

            $.messager.confirm('提示框', '你确定要删除吗?',function(){
                var arr = new Array();
                for(var i=0; i<rows.length; i++){
                    arr.push(rows[i]['id']);
                }
                $.ajax({
                    url:"<?php echo url('index/helpdocument/deldocbutton'); ?>",
                    type:"POST",
                    data:{id:arr},
                    success:function(data){
                        if(data.error == 1){
                            $.messager.alert('提示','删除成功！','info');
                            $("#rangegrid" ).datagrid('reload');
                        }else {
                            $.messager.alert('提示','删除失败！','warning');
                        }
                    }
                });
            })

        })
    })
</script>
</body>
</html>