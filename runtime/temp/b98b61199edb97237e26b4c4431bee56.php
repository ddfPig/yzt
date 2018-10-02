<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\terminus\terminus_add.html";i:1538297310;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    <script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'"  href="javascript:add()">保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/terminus/lists'); ?>">列表</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" href="<?php echo url('index/terminus/terminalm_add'); ?>">刷新</a>
            </div>

            <div class="form_table2">

                <div class="row">
                    <form id="dd">
                        <ul>
                            <li><label>编码</label><input type="text" name="number" value="<?php echo $numbers; ?>"  readonly="readonly"/></li>
                            <?php if($uinfo['role_short'] == 'cj'): ?>
                            <li>
                                <label>所属客服</label>
                            <input type="text" placeholder="请双击选择"  name="serviceName" id="serviceName" readonly="readonly" style="border:1px solid #1BBC9B" />
                            <input type="hidden" name="serviceID" id="serviceID">
                            </li>
                            <?php endif; ?>
                            <!--readonly="readonly"-->
                            <li><label for="name">姓名</label><input type="text" placeholder="必填" name="name" /></li>
                            <li><label>登录用户名</label><input type="text" placeholder="必填" name="username"/></li>
                            <li><label>登录密码</label><input type="text" placeholder="必填" name="password"/></li>
                            <li><label>手机号</label><input type="text" placeholder="必填" name="phone"/></li>
                            <li><label>固定电话</label><input type="text" name="fixedphone"></li>
                            <li><label>QQ</label><input type="text" name="qq"></li>
                            <li><label>邮箱</label><input type="text" name="email" ></li>
                        </ul>
                    </form>

                </div>
            </div>

</div>
<div id="dlg" class="easyui-dialog" style="padding: 10px; width:500px; height:500px;"></div>
<script>
    function add(){
        $.ajax({
            url:"<?php echo url('index/terminus/add'); ?>",
            type:"POST",
            data:$('#dd').serialize(),
            success:function(result){
                var results = eval('('+result+')');
                if(results.error == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/terminus/lists'); ?>";
                    });
                }else {
                    $.messager.alert('提示',results.msg,'warning');
                }
            }
        });
    }

    $(document).ready(function () {
        $('#dlg').dialog('close');
    });

    var Servicer_url = "<?php echo url('index/account/getServicer'); ?>";
    $("#serviceName").dblclick(function () {

        $('#dlg').dialog({
            title: '请选择客服',
            width: 620,
            height: 550,
            closed: false,
            cache: false,
            href: Servicer_url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#serviceID').val(rows[0].id);
                        $('#serviceName').val(rows[0].pkfgl_name);
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

                        $('#serviceID').val(rows.id);
                        $('#serviceName').val(rows.pkfgl_name);

                        $('#dlg').dialog('close');
                    }
                });
            }
        });
    });

</script>
</body>
</html>