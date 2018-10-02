<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\patient_add.html";i:1538201968;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>诊所通V1.0.0</title>
<link rel="stylesheet" href="/public/static/css/bootstrap.css" />
<link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/public/static/css/css.css" />
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
<script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
<script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
<script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
<link rel="shortcut icon" href="/public/static/images/favicon.ico" />



</head>
<body>
<ul class="breadcrumb">
    首页<span class="divider">/</span>诊疗管理<span class="divider">/</span>患者档案管理
</ul>
<div class="title_right"><strong>患者档案管理</strong></div>
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
					<a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/cure/patient'); ?>">列表</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                </div>

<div class="form_table2" style="margin-top:0">
    <div class="row">
	<form id="ff">
        <ul>
            <li><label>患者编号</label><input type="text" name="patient_number" id="patient_number" value="<?php echo $patient_id; ?>" readonly="readonly"/></li>
            <li><label>姓名</label><input type="text" name="name" id="name"/></li>
            <li><label>性别</label><select name="sex" id="sex"><option value='男'>男</option><option value='女'>女</option></select></li>
            <li><label>年龄</label><input type="text" name="age" id="age"/></li>
            <li><label>手机号</label><input type="text" name="phone" id="phone"/></li>
			<li><label>诊疗卡</label><input type="text" placeholder="请双击选择" style="border:1px solid #1BBC9B" name="card" id="card" ondblclick="show_card()" readonly="readonly" />
        </ul>
    	<ul>
            <li><label>首次诊疗日期</label><input type="text" id="first_cure" readonly="readonly"/></li>
            <li><label>最后诊疗日期</label><input type="text" readonly="readonly"/></li>
            <li><label>病症情况</label><input type="text" name="disease_situation" id="disease_situation"/></li>
            <li><label>备注</label><textarea name="remarks" id="remarks"></textarea></li>
        </ul>
		<ul>
            <li><label>有无医保</label><select name="medical_insurance" id="medical_insurance"><option value='无'>无</option><option value='有'>有</option></select></li>
            <li><label>遗传史</label><input type="text" value="无" name="inheritance" id="inheritance"/></li>
            <li><label>慢性病</label><input type="text" value="无" name="chronic" id="chronic"/></li>
            <li><label>大病史</label><input type="text" value="无" name="serious" id="serious"/></li>
            <li><label>过敏药物</label><input type="text" value="无" name="allergy" id="allergy"/></li>
        </ul>

    	<ul>
            <li><label>档案编号</label><input type="text" name="file_number" id="file_number" value="<?php echo $file_id; ?>" readonly="readonly" /></li>
            <li><label>建档日期</label><input type="text" readonly="readonly" /></li>
            <li><label>建档人</label><input type="text" readonly="readonly"/></li>
            <li><label>最后修改日期</label><input type="text" readonly="readonly"/></li>
            <li><label>最后修改人</label><input type="text" readonly="readonly"/></li>
        </ul>
        
    </form>
    </div>
</div>

              
<div id="win_card" class="easyui-dialog" title="选择诊疗卡" style="width: 500px; height: 450px; padding: 10px"></div>	
</div>
            
</div>




<script>
$(document).ready(function () {
	$('#win_card').dialog('close');

});
		//修改日历框的显示格式
        function formatter(date){
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var hour = date.getHours();
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;
            return year + "-" + month + "-" + day;
        }
		
		formatterDate = function(date) {
			var day = date.getDate() > 9 ? date.getDate() : "0" + date.getDate();
			var month = (date.getMonth() + 1) > 9 ? (date.getMonth() + 1) : "0"
			+ (date.getMonth() + 1);
			return date.getFullYear() + '-' + month + '-' + day;
		};
		/*
		window.onload = function() {
			$('#first_cure').datebox('setValue', formatterDate(new Date()));
			$('#createdate').datebox('setValue', formatterDate(new Date()));
		};*/
	function show_card() {
        dlg = $('#win_card').dialog({
            width: 560,
            height: 500,
            title: '选择诊疗卡',
            closed: false,
            cache: false,
            href: "win_card",
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = selectGrid.datagrid('getSelections');
                    if (rows != null) {
                        $('#card').val(rows[0].card);
                        $('#win_card').dialog('close');
                    }
                }
            }, {
                text: '取消',
                handler: function () {
                    dlg.dialog('close');
                }
            }]
        });
    }
	function add(){
		//判断是否填写完整
		/*if(!$('#patient_number').val() || !$('#name').val() || !$('#sex').val() || !$('#age').val() || !$('#phone').val() || !$('#disease_situation').val() || !$('#remarks').val() || !$('#medical_insurance').val() || !$('#inheritance').val() || !$('#chronic').val() || !$('#serious').val() || !$('#allergy').val() || !$('#file_number').val()){
			$.messager.alert('提示','请填写完整！','warning');
			return false;
		}*/
		var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;
		//电话
		var phone = $.trim($('#phone').val());
		if (phone && !phoneReg.test(phone)) {
		alert('请输入有效的手机号码！');
			return false;
		}
		$.ajax({
			url:'/index.php/index/cure/patient_add_api',
			type:"POST",
			data:$('#ff').serialize(),
			success:function(data){
				if(data.status==1){
					$.messager.alert('提示','保存成功！','info',function(){
						window.location.href = '/index.php/index/cure/patient';
					}); return;
				}
			}
		});
	}
</script>
</body>
</html>