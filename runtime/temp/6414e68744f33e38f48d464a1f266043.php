<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\base\medicine_add.html";i:1538120465;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    首页<span class="divider">/</span>基础管理<span class="divider">/</span>药品管理
</ul>
<div class="title_right"><strong>药品管理</strong></div>
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
                <div class="margin-bottom-5">
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="javascript:add()">保存</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:commit()">提交</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/base/medicine'); ?>">列表</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
                    <!--a class="easyui-linkbutton" data-options="iconCls:'icon-other7'">冻结</a-->
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other5'" href="javascript:sales(1)">暂停销售</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other6'" href="javascript:sales(0)">恢复销售</a>                    
                </div>

                </div>
                
	
<div class="form_table2">
<div class="row">
<input type="hidden" id="id">
<form id="ff">
    <ul>
<li>
	
	<label>药品分类</label>
    <select name="classify_id" id="classify_id">
        <option value=0>请选择</option>
		<?php if(is_array($classify_id) || $classify_id instanceof \think\Collection || $classify_id instanceof \think\Paginator): $i = 0; $__LIST__ = $classify_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
</li>    
        <li><label>药品编码</label><input type="text" name="code" id="code" value="<?php echo $medicine_id; ?>" readonly="readonly"/></li><!--readonly="readonly" 不可修改-->
        <li><label>药品简码</label><input type="text" name="scode" id="scode" readonly="readonly"/></li>
		<li><label>通用名称</label><input type="text" placeholder="必填" name="uname" id="uname" onchange="jianma()"/></li>
        <li><label>商品名</label><input type="text" name="name" id="name"/></li>
        <li><label>规格</label><input type="text" name="specification" id="specification" /></li>

     	
        <li><label>生产厂商</label><input type="text" name="manufacturer" id="manufacturer"/></li>
        <li><label>产地</label><input type="text" name="produce_place" id="produce_place" /></li>
          </ul>
        
        <ul>
        
        <li>
            <label>单位</label>
            <select name="unit_id" id="unit_id">
                <option value=0>请选择</option>
				<?php if(is_array($unit_id) || $unit_id instanceof \think\Collection || $unit_id instanceof \think\Paginator): $i = 0; $__LIST__ = $unit_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
     	<li>
            <label>剂型</label>
            <select name="dosage_id" id="dosage_id">
                <option value=0>请选择</option>
				<?php if(is_array($dosage_id) || $dosage_id instanceof \think\Collection || $dosage_id instanceof \think\Paginator): $i = 0; $__LIST__ = $dosage_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li><li><label>商品效期(月)</label>
        	<select name="effect_id" id="effect_id">
                <option value=0>请选择</option>
				<?php if(is_array($effect_id) || $effect_id instanceof \think\Collection || $effect_id instanceof \think\Paginator): $i = 0; $__LIST__ = $effect_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
                <li>
        	<label>处方药</label>
			<select name="prescription" id="prescription">
                <option value=0>请选择</option>
				<?php if(is_array($prescription) || $prescription instanceof \think\Collection || $prescription instanceof \think\Paginator): $i = 0; $__LIST__ = $prescription;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
        <li>
        	<label style="line-height:16px">特殊药品复方制剂</label>
			<select name="special" id="special">
                <option value=-1>请选择</option>
				<option value=0>否</option>
				<option value=1>是</option>
            </select>
        </li>
       
        <li><label>批准文号</label><input type="text"  name="approval_number" id="approval_number"/></li>
        <li><label style="line-height:16px;">批准文号<br />有效期</label><input type="text" name="approval_number_effect" id="approval_number_effect" data-options="formatter:formatter,editable:false" class="easyui-datebox" style="width:170px" /></li>
	</ul>
        
        <ul>
            <li><label>适用诊疗范围</label><input type="text" placeholder="请双击选择" style="border:1px solid #1BBC9B" name="scope" id="scope_show" ondblclick="show_scope()" readonly="readonly" /><input type="hidden" id="scope"></li>
			<li><label>性状</label><input type="text" name="character" id="character"/></li>
			<li><label>功能主治</label><input type="text" name="function" id="function"/></li>
            <li><label>整箱数量</label><input type="text" name="box_number" id="box_number"/></li>
			<li><label>商品条码</label><input type="text" name="bar_code" id="bar_code"/></li>
            <li><label>库存上限</label><input type="text" name="stock_max" id="stock_max"/></li>
            <li><label>库存下限</label><input type="text" name="stock_min" id="stock_min"/></li>
</ul>
<ul>         

			<li><label>最低收款价</label><input type="text" name="price_min" id="price_min"/></li>
			<li><label>收款单价</label><input type="text" name="price" id="price"/></li>
			<li><label>最高收款价</label><input type="text" name="price_max" id="price_max"/></li>
            <li><label>储存方法</label><input type="text" name="storage_method" id="storage_method"/></li>
            <li><label>养护方法</label><input type="text" name="maintenance_method" id="maintenance_method"/></li>
            <li><label>档案信息</label><input type="text" name="archival_information" id="archival_information" value="<?php echo $file_id; ?>" readonly="readonly"/></li>
            <li><label>备注</label><input type="text" name="remarks" id="remarks"/></li>
        </ul>
</form>
    </div>
	

<div id="win_scope" class="easyui-dialog" title="选择适用诊疗范围" style="width: 500px; height: 450px; padding: 10px"></div>	
</div>

</div>
<script>
$(document).ready(function () {
	$('#win_scope').dialog('close');

});

	
		function jianma(){
			var name = $('#uname').val();
			$.ajax({
				url: "/index.php/index/base/getpinyin",
				type: "post",
				data: {keyword:name},
				success: function (res) {
					$('#scode').val(res.str);
				},
			});
		}
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
	function show_scope() {
        dlg = $('#win_scope').dialog({
            width: 560,
            height: 500,
            title: '选择适用诊疗范围',
            closed: false,
            cache: false,
            href: "win_scope",
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = selectGrid.datagrid('getSelections');
                    if (rows != null) {
                        $('#scope_show').val('');
                        var jyfw;
                        $.each(rows, function (i, item) {
                            $('#scope_show').val($('#scope_show').val() + rows[i].name + ",");
                        });
                        if ($('#scope_show').val().length > 0) {
                            $('#scope_show').val($('#scope_show').val().substr(0, $('#scope_show').val().length - 1));
                        }
                        $('#win_scope').dialog('close');
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
		if(!$('#uname').val()){
			$.messager.alert('提示','请填写完整！','warning');
			return false;
		}
		if(parseFloat($('#stock_max').val()) < parseFloat($('#stock_min').val())){
			$.messager.alert('提示','库存上限不能小于库存下限！','warning'); 
			return false;
		}
		if(parseFloat($('#price_max').val()) < parseFloat($('#price').val())){
			$.messager.alert('提示','最高收款价不能小于收款单价！','warning'); 
			return false;
		}
		if(parseFloat($('#price_min').val()) > parseFloat($('#price').val())){
			$.messager.alert('提示','最低收款价不能大于收款单价！','warning'); 
			return false;
		}
		$.ajax({
			url:'/index.php/index/base/medicine_add_api',
			type:"POST",
			data:$('#ff').serialize(),
			success:function(data){
				if(data.status==1){
					$.messager.alert('提示','保存成功！','info',function(){
						window.location.href = '/index.php/index/base/medicine';
					}); 
				
					return;
				}
			}
		});
	}
	function commit(){
		var id = $('#id').val();
		if(!id){
			$.messager.alert('提示','请先保存','warning'); return;
		}
		$.ajax({
			url:'/index.php/index/base/medicine_commit_api',
			type:"POST",
			data:{'id':id},
			success:function(data){
				if(data.status==1){
					$.messager.alert('提示','提交成功！','info'); return;
				}
			}
		});
	}
	function sales(type){
		var id = $('#id').val();
		if(!id){
			$.messager.alert('提示','请先保存','warning'); return;
		}
		$.ajax({
			url:'/index.php/index/base/medicine_sales_api',
			type:"POST",
			data:{'id':id,'type':type},
			success:function(data){
				if(data.status==1){
					$.messager.alert('提示','修改成功！','info'); return;
				}
			}
		});
	}
</script>
</body>
</html>