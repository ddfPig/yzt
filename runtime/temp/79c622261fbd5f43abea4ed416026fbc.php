<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\ini\index.html";i:1538286298;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="javascript:add()">保存</a>
                </div>
    <div class="form_table2">
        <div class="row">
            <form id="dd">
            <ul>
                <li><label>近效期天数</label><input type="text" placeholder="必填，仅先数字"  name="cshsz_jxqts" value="<?php echo $data['cshsz_jxqts']; ?>"/></li>
                <li><label style="line-height:16px;">近效期销售<br />限制天数</label><input type="text" placeholder="必填，仅先数字" name="cshsz_jxqxzts"  value="<?php echo $data['cshsz_jxqxzts']; ?>"/></li>
                <li><label style="line-height:16px;">入库商品效期<br />限制天数</label><input type="text" placeholder="必填，仅先数字"  name="cshsz_rkspxzts"  value="<?php echo $data['cshsz_rkspxzts']; ?>" /></li>
                <li><label style="line-height:16px;">开启礼券过期<br/>提醒</label><input type="checkbox"   id="Lqtx" name="cshsz_kqlqtx"  <?php if($data['cshsz_kqlqtx'] == 1): ?> checked <?php endif; ?> value="1" onclick="checkBox($(this));"/> 是<input type="checkbox" id="Lqtx2"  <?php if($data['cshsz_kqlqtx'] == 0): ?> checked <?php endif; ?>  name="cshsz_kqlqtx" value=" 0" onclick="checkBox($(this));" /> 否</li>
                <li><label style="line-height:16px;">礼券过期提醒<br/>限制天数</label><input type="text" placeholder="必填，仅先数字"  name="cshsz_lqxzts"  value="<?php echo $data['cshsz_lqxzts']; ?>" /></li>
                <li><label style="line-height:16px;">开启余额不足<br/>提醒</label><input type="checkbox" id="Yebz" name="cshsz_kqyebz"  <?php if($data['cshsz_kqyebz'] == 1): ?> checked <?php endif; ?> value="1" onclick="tx($(this));" /> 是<input type="checkbox"  id="Yebz2"  <?php if($data['cshsz_kqyebz'] == 0): ?> checked <?php endif; ?> name="cshsz_kqyebz" value="0" onclick="tx($(this));" /> 否</li>
                <li><label style="line-height:16px;">最低余额限制金额</label><input type="text" placeholder="必填，仅先数字"  name="cshsz_yexzje"  value="<?php echo $data['cshsz_yexzje']; ?>" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');this.value=this.value.replace('.','');" /></li>
            </ul>
            </form>
        </div>
    </div>



        </div>
</div>
<script>
    function checkBox(obj) {
// 只有当选中的时候才会去掉其他已经勾选的checkbox，所以这里只判断选中的情况
        if (obj.is(":checked")) {
            // 先把所有的checkbox 都设置为不选种
            $('input[name="cshsz_kqlqtx"]').prop('checked', false);
            // 把自己设置为选中
            obj.prop('checked',true);
        }
    }
    function tx(obj) {
        if (obj.is(":checked")) {
            // 先把所有的checkbox 都设置为不选种
            $('input[name="cshsz_kqyebz"]').prop('checked', false);
            // 把自己设置为选中
            obj.prop('checked',true);
        }
    }



    function add(){
        $.ajax({
            url:"<?php echo url('index/ini/add_ini'); ?>",
            type:"POST",
            data:$('#dd').serialize(),
            success:function(result){
                var results = eval('('+result+')');
                if(results.error == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/ini/index'); ?>";
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