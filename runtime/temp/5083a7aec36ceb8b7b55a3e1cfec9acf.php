<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\helpdocument\helpdocument_edit.html";i:1538290927;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/helpdocument/index'); ?>">列表</a>
            </div>

            <div class="form_table2">
                <div class="row">
                    <form id="dd">
                        <ul>
                            <li><label>类型</label>
                            <select name="bzwdgl_state" id="bzwdgl_state">
                                <option>请选择</option>
                            </select>
                            <input type="hidden" id="type" name="type" />
                            </li>
                            <li><label>标题</label><input type="text" name="bzwdgl_title" value="<?php echo $data['bzwdgl_title']; ?>"/></li>
                            <li><label>自定义时间</label><input type="text"  name="bzwdgl_customize_time" value="<?php echo date('Y-m-d',$data['bzwdgl_customize_time']); ?>" class="easyui-datebox" editable="false" style="width:174px"/></li>
                            <li><label>内容</label><textarea style="width:170px;height:80px" name="bzwdgl_contents"><?php echo $data['bzwdgl_contents']; ?></textarea></li>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ghsz_type = "<?php echo $data['bzwdgl_state']; ?>";
    $(document).ready(function () {
        //Async(false);
        Bindqylx();//绑定企业类型
        $("#bzwdgl_state").change(function () {
            $("#gyslx").val($(this).find("option:selected").text());
        });
        //Async(true);
    });
    //绑定供应商资料类型
    function Bindqylx() {
        $("#bzwdgl_state").html("");
        //$("#ghsz_type").html(option);
        $.post("<?php echo url('index/helpdocument/type'); ?>", {}, function (result) {
            var str = "<option>请选择</option>";
            var check = '';
            $.each(result, function (i, item) {
                 if (ghsz_type == item.id) {
                      check = 'selected';
                  }else{
                       check = '';
                  }
                str +="<option value='" + item.id + "'  "+check+">" + item.name + "</option>";
            });
             $("#bzwdgl_state").append( str);
        },'json')
    };


    function add(){
        $.ajax({
            url:"<?php echo url('index/helpdocument/editdobutton'); ?>",
            type:"POST",
            data:$('#dd').serialize(),
            success:function(result){
                var results = eval('('+result+')');
                if(results.error == 1){
                    $.messager.alert('提示',results.msg,'info',function(){
                        window.location.href = "<?php echo url('index/helpdocument/index'); ?>";
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