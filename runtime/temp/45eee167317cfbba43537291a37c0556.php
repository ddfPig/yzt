<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:95:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\diagnosisresult\diagnosisresult_edit.html";i:1538269402;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="javascript:add()">保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/diagnosisresult/index'); ?>">列表</a>
            </div>
            <div class="form_table1">
                <form id="dd">
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <div class="row"><ul><li><label>状态</label><input type="text" readonly="readonly" name="zljggl_state" value="<?php if($data['zljggl_state'] == 1): ?>已启用<?php else: ?>已停用<?php endif; ?>"/></li></ul></div>
                    <div class="row"><ul><li><label>名称</label><input type="text" placeholder="必填" name="zljggl_name" value="<?php echo $data['zljggl_name']; ?>"/></li></ul></div>
                    <div class="row"><ul><li><label>创建人</label><input type="text" readonly="readonly" name="zljggl_create_name" value="<?php echo $data['zljggl_create_name']; ?>"/></li></ul></div>
                    <div class="row"><ul><li><label>创建时间</label><input type="text" readonly="readonly" name="zljggl_create_time" value="<?php echo date('Y-m-d H:i:s',$data['zljggl_create_time']); ?>"/></li></ul></div>
                    <div class="row"><ul><li><label>最后修改人</label><input type="text" readonly="readonly" name="zljggl_last_name" value="<?php echo $data['zljggl_last_name']; ?>"/></li></ul></div>
                    <div class="row"><ul><li><label>最后修改时间</label><input type="text" readonly="readonly" name="zljggl_last_time" value="<?php echo date('Y-m-d H:i:s',$data['zljggl_last_time']); ?>"/></li></ul></div>
                </form>
            </div>
</div>
<script>
    function add(){
        $.ajax({
            url:"<?php echo url('index/diagnosisresult/dia_edit_button'); ?>",
            type:"POST",
            data:$('#dd').serialize(),
            success:function(result){
                var results = eval('('+result+')');
                if(results.error == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/diagnosisresult/index'); ?>";
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