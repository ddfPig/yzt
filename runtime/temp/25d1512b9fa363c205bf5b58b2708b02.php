<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\clerk\index.html";i:1538283075;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
<link rel="shortcut icon" href="/public/static/images/favicon.ico" />



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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" href="<?php echo url('index/clerk/clerk_add'); ?>">新建</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="update()">修改</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" onclick="audit()">启用</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-del'" onclick="disable()">停用</a>
                    <select style="height:24px;line-height:24px; width:auto" id="type_code" onchange="search()">
                        <option value="0">按状态筛选</option>
                        <option value="1" >已启用</option>
                        <option value="2"> 已停用</option>
                    </select>
					<select style="height:24px;line-height:24px; width:auto" id="type_codes" onchange="search()">
						<option value="0" >按状态筛选</option>
                        <option value="1">在职</option>
                        <option value="2">离职</option>
                    </select>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" onclick="Execl(this)">导出</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="refresh()">刷新</a>
                </div>

			<!-- <div id="divpadding" class="margin-top-5" style="clear:both"> -->
				<div style="width:100%;height:85%;" >
					<div id="dg" style="width:100%;height:100%; ">
		
			  </div>
		</div>

   <!-- </div> -->
              
            </div>
      
</div>
<script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
<script>
	function Execl(obj) {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var type_codes = $("#type_codes").val();
            var type_code = $("#type_code").val();
         
            if (r) {
                window.location.href = "<?php echo url('index/clerkapi/dataToExcel'); ?>?type_codes="+type_codes+"&type_code="+type_code;
            }
        });
    }

	 var  dg;
    $(function(){

    $('#dg').datagrid({    
        url:'/public/index.php/index/clerkapi/clerk_list', 
        iconCls:'icon-save',
        pagination:true,
        pageSize:10,
        pageList:[10,20,50],
        fit:true,
        fitColums:false,
        nowarp:false,	
        rownumbers: true,      
		idFieid:"clerk_id",
		frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]], 
        columns:[[    
				{field:'clerk_status',title:"状态",},
				{field:'clerk_office_id',title:"职业类型",},
				{field:'ks',title:"科室",},
				{field:'clerk_office_num',title:"职员编码",},
				{field:'clerk_name',title:"名字",},
				{field:'clerk_sex',title:"性别",},
				{field:'clerk_phone',title:"电话",},
				{field:'clerk_place',title:"籍贯",},
				{field:'clerk_card',title:"身份证号",},
				{field:'clerk_birth_date',title:"出生日期",},
				{field:'clerk_education',title:"学历",},
				{field:'clerk_specialty',title:"专业",},
				{field:'clerk_healthy',title:"健康情况",},

				{field:'clerk_healthy_card',title:"健康证号",},
				{field:'clerk_healthy_carddate',title:"发证日期",},
				{field:'clerk_office_name',title:"职称",},
				{field:'clerk_office_type',title:"在职/离职",},
				{field:'clerk_text',title:"备注",},
        ]],
		onDblClickCell: onDBClickCell,		
    });  

    });
function  onDBClickCell(index, field, value){
  var rows = $('#dg').datagrid('getRows');//获得所有行
  var row = rows[index];//根据index获得其中一行。
	window.location.href="/public/index.php/index/clerk/clerk_upd?row="+row['clerk_id'];


}
function  refresh(){
location.reload();
}
function  update(){
	
	var rows = $('#dg').datagrid('getSelections');
	
	if(rows==""||rows.length>1){
		$.messager.alert("修改",'请选择一条数据','warning');
		return;
	}else{
		window.location.href="/public/index.php/index/clerk/clerk_upd?row="+rows[0]['clerk_id'];
	}
	
	
}
function search(){
	var type_code=$('#type_code').val();
	var type_codes=$('#type_codes').val();
	
	$('#dg').datagrid({    
		    url:'/public/index.php/index/clerkapi/clerk_list?type_code='+type_code+"&&type_codes=".type_codes,  
			iconCls:'icon-save',
			pagination:true,
			pageSize:10,
			pageList:[10,20,50],
			fit:true,
			fitColums:false,
			nowarp:false,
			border:false,		
			rownumbers: true,      
			idFieid:"clerk_id",
			queryParams: {
				type_code: type_code,
				type_codes: type_codes
			},
		    columns:[[    	        
			{field:'clerk_status',title:"学号",width:120},
				{field:'clerk_office_id',title:"职业类型",width:120},
				{field:'clerk_office_num',title:"职员编码",width:120},
				{field:'clerk_name',title:"名字",width:120},
				{field:'clerk_sex',title:"性别",width:120},
				{field:'clerk_phone',title:"电话",width:120},
				{field:'clerk_place',title:"籍贯",width:120},
				{field:'clerk_card',title:"身份证号",width:120},
				{field:'clerk_birth_date',title:"出生日期",width:120},
				{field:'clerk_education',title:"学历",width:120},
				{field:'clerk_specialty',title:"专业",width:120},
				{field:'clerk_healthy',title:"健康情况",width:120},

				{field:'clerk_healthy_card',title:"健康证号",width:120},
				{field:'clerk_healthy_carddate',title:"发放日期",width:120},
				{field:'clerk_office_name',title:"职称",width:120},
				{field:'clerk_office_type',title:"在职/离职",width:120},
				{field:'clerk_text',title:"备注",width:120},
		    ]]    
		}); 
	
}


function  audit(){
		var ids=[];
        var rows = $('#dg').datagrid('getSelections');
		
		if(rows==""){
			$.messager.alert('启动','请选择一条数据');
			return;
		}
		
		
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].clerk_id);
        }
	
		$.ajax({
				url: '/public/index.php/index/clerkapi/clerk_upd_code',
				type: 'POST',
				data: {        		
					ids :ids
				},
				success: function(res) {				
					location.reload();					
				}				
			})
   
}
function  disable(){
	var ids=[];
        var rows = $('#dg').datagrid('getSelections');
		
		if(rows==""){
			$.messager.alert('停用','请选择一条数据');
			return;
		}
        for (var i = 0; i < rows.length; i++) {
            ids.push(rows[i].clerk_id);
        }
	
		$.ajax({
				url: '/public/index.php/index/clerkapi/clerk_upd_disable',
				type: 'POST',
				data: {        		
					ids :ids
				},
				success: function(res) {				
					location.reload();					
				}				
			})
}



</script>
</body>
</html>