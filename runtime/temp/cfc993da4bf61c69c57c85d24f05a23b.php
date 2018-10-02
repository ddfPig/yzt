<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cards\addCards.html";i:1538226682;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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

        <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" id="a_save">生成</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" href="<?php echo url('index/cards/index'); ?>">列表</a>
    </div>
</div>

        <form method="post" style="width:100%;height:100%;">
            <div class="form_table2">
                <div class="row">
                    <ul style="width:60%;">
                        <li>
                            <label>卡号门店</label>&nbsp;&nbsp;&nbsp;
                            <select id="ShopID" name="ShopID" style="height:24px;line-height:24px; width:auto">
                            <option>按门店筛选</option>

                        </select>
                        </li>
                        <li></li>
                        <li><label>门店编号</label><input type="text" readonly="readonly" id="ShopCode" name="ShopCode" /></li>
                        <li></li>
                        <li>
                            <label>生成数量</label>&nbsp;&nbsp;&nbsp;<select id="CardCount" name="CardCount" style="height:24px;line-height:24px; width:auto">
                            <option value="5">5</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="2000">2000</option>

                        </select>
                        </li>
                    </ul>
                </div>
</div>
        </form>


    <div id="p" class="easyui-progressbar" data-options="value:60" style="width:400px;"></div>

</body>
<script>

    $(document).ready(function () {
        $("#ShopID option").remove();
        $("#ShopID").append(" <option value=''>按门店筛选</option>");
        $("#ShopID").append(" <option value='-1'>不带门店编号</option>");
        $.getJSON("<?php echo url('index/cards/getShopsList'); ?>", {}, function (data) {
            $.each(data, function (i, item) {
                $("#ShopID").append(" <option value='" + item.shop_id + "'>" + item.shop_name + "</option>");

            });
        });
        $("#ShopID").change(function () {
            if ($(this).val() != "" && $(this).val() != "0") {
                $.getJSON("<?php echo url('index/cards/getShopInfo'); ?>", { id: $(this).val() }, function (data) {

                    $("#ShopCode").val(data.shop_number);
                });
            } else {
                if ($(this).val() == "0") {
                    $("#ShopCode").val("");
                }
                if ($(this).val() == "") {

                    $("#ShopCode").val("");
                }
            }
        });
    });


    var IsSubmit = true;
    $("#a_save").click(function () {
        if ($("#ShopID").find("option:selected").val() == "") {
            $.messager.alert('提示信息', "请选择门店！", 'error');
            return false;
        }
        $('#p').progressbar({
            value: $("#CardCount").find("option:selected").val()
        });

        var value = $('#p').progressbar('getValue');
        if (value < 100) {
            value += Math.floor(Math.random() * 10);
            $('#p').progressbar('setValue', value);
        }

        $.messager.progress();

        if (IsSubmit) {
            IsSubmit = false;
            $.post("<?php echo url('index/cards/insertCards'); ?>", $("form").serialize(),
                function (result) {
                    var results = eval('('+result+')');
                    IsSubmit = true;
                    if (results.code == 1) {

                        $.messager.alert("操作成功", results.msg, "info", function () {
                            window.location.href = "<?php echo url('index/cards/index'); ?>";
                            $.messager.progress('close');	// 如果表单是无效的则隐藏进度条

                        });
                    }
                    else {
                        $.messager.alert('提示信息', results.msg, 'error');

                        $.messager.progress('close');	// 如果提交成功则隐藏进度条

                    }
                }, "json");
        }
    });
</script>
</html>