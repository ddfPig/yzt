<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\account\transformShop.html";i:1538115165;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>医诊通V1.0.0</title>
    <link rel="stylesheet" href="/public//static/css/bootstrap.css" />
    <link href="/public//static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/public//static/css/css.css" />
    <link rel="stylesheet" type="text/css" href="/public//static/easyui/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public//static/easyui/themes/icon.css">
    <script type="text/javascript" src="/public//static/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/public//static/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public//static/js/sdmenu.js"></script>
    <script type="text/javascript" src="/public//static/js/laydate/laydate.js"></script>
    <link rel="shortcut icon" href="/public//static/images/favicon.ico" />

</head>
<body>
<ul class="breadcrumb">
    <?php echo $navInfo; ?>
</ul>
<div class="title_right"><strong><?php echo $nav; ?></strong></div>
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
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="saveUser()">保存</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/account/lists'); ?>">列表</a>

    </div>

    <div class="form_table2">
        <form action="" method="post" id="admin_user">
            <input type="hidden" name="shop_id" value="<?php echo $shop['shop_id']; ?>">
            <div class="row">
                <ul>
                    <li><label>移交店铺</label><input type="text" name="shop_name" value="<?php echo $shop['shop_name']; ?>" readonly="readonly"  /></li>
                    <li><label for="name">原终端经理</label><input type="text"  name="zd_name" value="<?php echo $shop['name']; ?>" readonly="readonly"/></li>

                    <li>
                        <label>终端经理</label>
                        <input type="text" placeholder="请双击选择"  name="zdname" id="zdname" readonly="readonly" style="border:1px solid #1BBC9B"  value=""/>
                    </li>


                </ul>





            </div>
            <input type="hidden" name="oldzdID" id="oldzdID" value="<?php echo $shop['tid']; ?>">
            <input type="hidden" name="newzdID" id="newzdID" value="">

        </form>
    </div>

</div>
<div id="dlg" class="easyui-dialog" style="padding: 10px; width:500px; height:500px;"></div>
</body>
<script>


    $(function(){
        $('#dlg').dialog('close');
    });


    var zd_url = "<?php echo url('index/account/zdmanage'); ?>";
    $("#zdname").dblclick(function () {

        $('#dlg').dialog({
            title: '请选择终端经理',
            width: 600,
            height: 500,
            closed: false,
            cache: false,
            href: zd_url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#newzdID').val(rows[0].id);
                        $('#zdname').val(rows[0].name);
                        $('#dlg').dialog('close');
                    }
                }
            }, {
                text: '取消',
                handler: function () {
                    $('#dlg').dialog('close');
                }
            }],
            //放到dialog事件中
            onLoad: function () {
                //弹出窗里的ID
                $('#dwgrid').datagrid({
                    onDblClickRow: function (index, rows) {

                        $('#newzdID').val(rows.id);
                        $('#zdname').val(rows.name);

                        $('#dlg').dialog('close');
                    }
                });
            }
        });
    });




    function saveUser(){
        $('#admin_user').form('submit',{
            url: "<?php echo url('index/account/transformToShop'); ?>",
            onSubmit: function(){
                //return $(this).form('validate');
            },
            success: function(result){
                var results = eval('('+result+')');
                if(results.code == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/account/lists'); ?>";
                    });
                }else{

                    $.messager.alert('提示',results.msg,'warning');
                }
            }
        });
    }

</script>

</script>
</html>