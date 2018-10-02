<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\rule\rule.html";i:1538208812;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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

    <script src="/public/static/easyui/plugins/jquery.tree.js"></script>
    <link href="/public/static/easyui/themes/bootstrap/tree.css" rel="stylesheet" />
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
                <a id="a_save" class="easyui-linkbutton" data-options="iconCls:'icon-add'">确认保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>
            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">
                <p style="font-size: 14px;font-weight: bold;">正在为用户组: .<?php echo $groups['title']; ?> 设置访问权限:</p>
                <input name="id" id="rid" type="hidden" value="<?php echo $roleid; ?>" />
                <ul id="tt"></ul>
                <div style="display:none;" id="dialog">
                    <div id="grid"></div>
                </div>


            </div>
    </div>
<input type = 'hidden' name="oldRule" value="<?php echo $groups['rules']; ?>">
</body>
<script>

    var MenuData;//数据
    $(document).ready(function () {
        OnInitTree();
        $('#a_save').bind('click', function () {
            OnSaveRoleMenuRel();//提交
        });
    });


    function OnInitTree() {
        $('#tt').tree({
            url: "<?php echo url('index/rule/ruleLists'); ?>?rid=" + $("#rid").val(),
            parentField: "pid",
            textFiled: "title",
            idFiled: "id",
            selectField: 'Flag',
            animate: true,
            checkbox: true,
            cascadeCheck: false,
            lines: true,
            onClick: function (node) {
            },
            onCheck: function (node) {

                //如果选中子节点 则自动选中父节点（如果有）
                var node1 = $('#tt').tree('getParent', node.target);
                if (node1 != undefined && node1 != null) {
                    $('#tt').tree('check', node1.target);
                }
            },
            onLoadSuccess: function (node, data) {
                $('#tt').tree('collapseAll');
            }
        });
    }

    //扩展Tree 重定义主键、父节点、显示名称、是否选中字段名称
    $.fn.tree.defaults.loadFilter = function (data, parent) {
        var opt = $(this).data().tree.options;
        var idFiled, textFiled, parentField, selectField;
        if (opt.parentField) {
            idFiled = opt.idFiled || 'id';
            textFiled = opt.textFiled || 'text';
            selectField = opt.selectField || 'checked';
            parentField = opt.parentField;

            var i, l, treeData = [], tmpMap = [];
            for (i = 0, l = data.length; i < l; i++) {
                tmpMap[data[i][idFiled]] = data[i];
            }
            for (i = 0, l = data.length; i < l; i++) {
                if (tmpMap[data[i][parentField]] && data[i][idFiled] != data[i][parentField]) {
                    if (!tmpMap[data[i][parentField]]['children'])
                        tmpMap[data[i][parentField]]['children'] = [];
                    data[i]['text'] = data[i][textFiled];
                    data[i]['checked'] = parseInt(data[i][selectField]) == 1 ? true : false;
                    tmpMap[data[i][parentField]]['children'].push(data[i]);
                } else {
                    data[i]['text'] = data[i][textFiled];
                    data[i]['checked'] = parseInt(data[i][selectField]) == 1 ? true : false;
                    treeData.push(data[i]);
                }
            }
            return treeData;
        }
        return data;
    };
    //获取所有选中节点ID
    function GetSelectAllID() {
        var ID = '';
        var node = $('#tt').tree('getChecked');
        $(node).each(function (index, item) {
            ID += (ID.length > 0 ? ',' : "") + item.id;
        });
        return ID;
    }


    //提交保存
    var IsSubmit = false;//提交状态 防止反复点击提交
    function OnSaveRoleMenuRel() {
        var new_rules = GetSelectAllID();
        if (!IsSubmit) {
            $.messager.progress({ title: '操作提示', msg: '数据提交中,请稍后......', text: '' });
            IsSubmit = true;
            $.ajax({
                url: "<?php echo url('index/rule/accessRule'); ?>",
                type: "post",
                data: {new_rules: new_rules,id:$("#rid").val()},
                success: function (s) {
                    var results = eval('('+s+')');
                    IsSubmit = false;
                    if (results.code == 1) {
                        $.messager.alert('操作成功',results.msg,'info',function(){
                            $('#tt').tree('reload');
                        });

                    }
                    else {
                        $.messager.alert('操作提示',results.msg,'warning');
                    }
                    $.messager.progress('close');
                },
                error: function (e) {
                    IsSubmit = false;
                    $.messager.alert('操作提示',e.msg,'warning');
                    $.messager.progress('close');
                }
            });
        }
    }

    function reloadurl()
    {
        $('#tt').tree('reload');
    }

</script>
</html>