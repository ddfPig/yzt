<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\searchs\searchStore.html";i:1537498024;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>

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
    首页<span class="divider">/</span>综合查询<span class="divider">/</span>库存查询
</ul>
<div class="title_right"><strong>库存查询正在开发中...</strong></div>
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
        <!--<a  class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="#">保存</a>-->
        <!--<a onclick="reloadurl()" class="easyui-linkbutton" data-options="iconCls:'icon-refresh'">刷新</a>-->
    </div>

    <div id="divpadding" class="margin-top-5" style="clear:both">
        <div id="rangegrid" style="min-height:500px; width:100%;">

        </div>
    </div>
</div>
</body>
<script type="text/javascript">


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
        window.location.href = "<?php echo url('index/more/scans'); ?>";
    }
</script>
</html>