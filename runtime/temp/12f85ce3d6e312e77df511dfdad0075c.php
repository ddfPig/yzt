<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\ini\reward_edit.html";i:1538197339;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>诊所通V1.0.0</title>
    <link rel="stylesheet" href="/public/static/css/bootstrap.css" />
    <link rel="stylesheet" href="/public/static/css/css.css" />
    <link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/public/static/images/favicon.ico" />
    <script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
    <script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
<ul class="breadcrumb">
    首页<span class="divider">/</span>系统配置<span class="divider">/</span>储值奖励设置
</ul>
<div class="title_right"><strong>储值奖励设置</strong></div>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="javascript:add()">保存</a>
                </div>
    <div class="form_table2">
        <div class="row">
            <form id="dd">
            <ul>
                <input type="hidden" name="clinic_id" value="<?php echo $shop_id; ?>"/>
                <li><label>用户编码</label><input type="text" placeholder="必填，仅先数字"  name="shop_number" value="<?php echo $shop_number; ?>" readonly="readonly"/></li>
                <li><label>用户全称</label><input type="text" placeholder="必填，仅先数字"  name="shop_name" value="<?php echo $shop_name; ?>" readonly="readonly"/></li>
                <li><label style="line-height:16px;">开启储值奖励</label><input type="checkbox" id="Yejl" name="cshsz_kqczjl" <?php if($cshsz_kqczjl == 1): ?> checked <?php endif; ?>  value="1" onclick="jl($(this));" /> 是<input type="checkbox" id="Yejl2" <?php if($cshsz_kqczjl == 0): ?> checked <?php endif; ?>  name="cshsz_kqczjl" value="0" onclick="jl($(this));" /> 否</li>
            </ul>
            </form>
        </div>
    </div>



        </div>
</div>
<script>

    function jl(obj) {
        if (obj.is(":checked")) {
            // 先把所有的checkbox 都设置为不选种
            $('input[name="cshsz_kqczjl"]').prop('checked', false);
            // 把自己设置为选中
            obj.prop('checked',true);
        }
    }


    function add(){
        $.ajax({
            url:"<?php echo url('index/ini/reward_add'); ?>",
            type:"POST",
            data:$('#dd').serialize(),
            success:function(result){
                var results = eval('('+result+')');
                if(results.error == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/account/lists'); ?>";
                    });
                }else {
                    $.messager.alert('提示',results.msg,'warning');
                }
            }
        });
    }
</script>
</body>
</html>