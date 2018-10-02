<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\login\login.html";i:1538052662;}*/ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>医诊通V1.0.0</title>
    <link rel="stylesheet" href="/public/login/css/docs.css" />
    <link rel="stylesheet" href="/public/login/css/jquery.running.css" />
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
    <link rel="shortcut icon" href="/favicon.ico" />
    <script type="text/javascript" src="/public/login/js/jquery.min.js"></script>
    <!--<script type="text/javascript" src="js/timepeople.js"></script>-->
    <script type="text/javascript" src="/public/login/js/docs.js"></script>
    <script src="/public/login/js/jquery.running.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>

</head>
<body>
<div class="ztsg">
    <p class="faw"><a href="http://www.quanyaotong.com" target="_blank">访问全药通</a></p>
    <div class="logodiv">
        <img src="/public/login/img/logo.png" class="logoimg"/>
    </div>
    <form action="">
        <p class="zhp">
            <input type="text" name="loginName" placeholder="请输入用户名" id="admin_user" class="zhinput" autofocus/>
            <span class="falsetip" id="users"></span>

        </p>
        <p class="zhp">
            <input type="password" name="password" placeholder="请输入密码" id="admin_pass" class="zhinput"/>
            <span class="falsetip" id="pass"></span>

        </p>
        <p class="dlbtn">
            <input type="button" value="登录系统" class="loginbtn" id="btnLogin"/>
        </p>
    </form>

    <p class="wxtips">
    </p>
    <p class="copyright">
        温馨提醒：医诊通已于2018年09月17日20:30升级更新系统，请在此时间后首次使用时，需要清空浏览器缓存重新打开进入使用。<br>
        © 2018 医诊通 技术支持:北京通药集采科技有限公司 电话:400-6336-519
    </p>
    <div class="fwh">
        <div class="fwhone">
            <img src="/public/login/img/quanyaotong.png" alt="" class="hideimg"/>
            <img src="/public/login/img/qytfwh.png" alt="" class="fwhbs"/>
            <p class="fwp1">全药通</p>
            <p class="fwp2">微信服务号</p>
        </div>
        <div class="fwhone">
            <img src="/public/login/img/zhangshangyaotong.png" alt="" class="hideimg"/>
            <img src="/public/login/img/qytfwh.png" alt="" class="fwhbs"/>
            <p class="fwp1">医诊通</p>
            <p class="fwp2">微信服务号</p>
        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        $("#admin_user").focus();
        $(".falsetip").hide();
        $(document).keypress(function (e) {
            // 回车键事件
            if (e.which == 13) {
                $("#btnLogin").click();
            }
        });
        $("#btnLogin").click(function () {
            //$(".falsetip").html("<span class='falsetip'></span>");
            $(".falsetip").hide();
            var loginName = $("#admin_user").val();
            var password = $("#admin_pass").val();
            $.post("<?php echo url('index/login/runlogin'); ?>", {loginName:loginName,password:password},
                function (resultfg) {
                    var results = eval('('+resultfg+')');
                    if (results.code == 1) {
                       // $.messager.alert('登录提示',results.msg,'info',function(){
                            $(".falsetip").hide();
                            window.location.href = "<?php echo url('index/index/index'); ?>";
                        //});
                    }else if(results.code == 2) {
                        $("#users").html(results.msg);
                        $('#users').show();

                    }else if(results.code == 3){

                        $("#pass").html(results.msg);
                        $('#pass').show();
                    }
                }, "json");
        });
    });

</script>

</html>
