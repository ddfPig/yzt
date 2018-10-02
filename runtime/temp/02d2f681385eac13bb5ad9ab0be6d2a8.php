<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\clerk\clerk_upd.html";i:1538292251;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
               <div class="right" id="mainFrame">
		
               
                <div class="margin-bottom-5">
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="update()" >保存</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/clerk/index'); ?>">列表</a>
                </div>
                
<input  type="hidden" id="creator" value="YG">
<input  type="hidden" id="zid" value="1">

<div class="form_table2">
    <div class="row">
        <ul>
            <li><label>职员类型</label>
			<select id="clerk_office_id">
			<option value="0" <?php if($data['clerk_office_id']): ?>==0}	selected = "selected" <?php endif; ?> >请选择</option>
			<?php foreach($arr_type as $k=>$v): ?>
		
			<option value="<?php echo $v['id']; ?>" <?php if($v['id']==$data['clerk_office_id']): ?>	selected = "selected" <?php endif; ?> ><?php echo $v['zylx_name']; ?></option>
			<?php endforeach; ?>
		
			</select></li>
		
		
            <li><label>职员编码</label><input type="text" placeholder="必填" readonly="readonly"  id="clerk_office_num"/ value="<?php echo $data['clerk_office_num']; ?>"></li>
            <li><label>姓名</label><input type="text" placeholder="必填" id="clerk_name" value="<?php echo $data['clerk_name']; ?>"/></li>       
            <li><label>性别</label>
			<select id="clerk_sex"><option value="0" <?php if($data['clerk_sex']==0): ?> selected = "selected"<?php endif; ?>>请选择</option>
			<?php foreach($arr_sex as $k=>$v): ?>
			<option value="<?php echo $k; ?>"  <?php if($k==$data['clerk_sex']): ?>	selected = "selected" <?php endif; ?> ><?php echo $v; ?></option>
			<?php endforeach; ?>			
			</select></li>
            <li><label>电话</label><input type="text" placeholder="必填" id="clerk_phone" value="<?php echo $data['clerk_phone']; ?>" /></li>
            <li><label>籍贯</label><input type="text"  id="clerk_place"  value="<?php echo $data['clerk_place']; ?>"/></li>
			<li><label>身份证号</label><input type="text" id="clerk_card" value="<?php echo $data['clerk_card']; ?>" /></li>
            <!-- <li><label>出生日期</label><input type="text" id="clerk_birth_date" /></li> -->
           <li><label>出生日期</label><input type="text" id="clerk_birth_date"  class="easyui-datebox" editable="fasle"  style="width:170px" 
		  <?php if($data['clerk_birth_date']!='1970-01-01'): ?> value="<?php echo $data['clerk_birth_date']; ?>" <?php endif; ?>
		   /></li>
		   <li><label>是否是诊疗人</label>
			<select id="is_code"><option value="-1">请选择</option>
			<option value="0" <?php if($data['is_code']==0): ?>	selected = "selected" <?php endif; ?>>否</option>
			<option value="1" <?php if($data['is_code']==1): ?>	selected = "selected" <?php endif; ?>>是</option>
			</select></li>
		</ul>
        <ul>
            <li><label>学历</label>
            <select id="clerk_education">
			<option value="0" <?php if($data['clerk_education']==0): ?> selected = "selected"<?php endif; ?> >请选择</option>
			<?php foreach($arr_education as  $k=>$v): ?>
			
			<option value="<?php echo $k; ?>"  <?php if($k==$data['clerk_education']): ?>	selected = "selected" <?php endif; ?>><?php echo $v; ?></option>	
				
			<?php endforeach; ?>     	
            </select>
            </li>
            <li><label>专业</label><input type="text" id="clerk_specialty" value="<?php echo $data['clerk_specialty']; ?>" /></li>
            <li>
            	<label>健康状况</label>
                <select id="clerk_healthy">
            	<option value="0" <?php if($data['clerk_healthy']==0): endif; ?>>请选择</option>
				<?php foreach($arr_healthy as  $k=>$v): ?>
				<option value="<?php echo $k; ?>" <?php if($k==$data['clerk_healthy']): ?>	selected = "selected" <?php endif; ?>><?php echo $v; ?></option>		
				<?php endforeach; ?>                 
            	</select>
            </li>
       
            <li><label>健康证号</label><input type="text" placeholder="必填" id="clerk_healthy_card"  value="<?php echo $data['clerk_healthy_card']; ?>"/></li>
			<li><label>发证日期</label><input type="text" 
				id="clerk_healthy_carddate"  editable="fasle" class="easyui-datebox"
				  style="width:170px"
				    <?php if($data['clerk_healthy_carddate']!='1970-01-01'): ?> value="<?php echo $data['clerk_healthy_carddate']; ?>"<?php endif; ?>
				
				 /></li>

		  <li><label>职称</label><input type="text" id="clerk_office_name" value="<?php echo $data['clerk_office_name']; ?>" /></li>
			
            <li><label>用户</label><input type="text" placeholder="必填" id="clerk_user" value="<?php echo $data_user['admin_user']; ?>" /></li>
            <li><label>密码</label><input type="password" placeholder="必填" id="clerk_pass"  value="111111"/></li>
			<li>
            	<label>科室</label>
                <select id="ks">
				<option value="0" <?php if($data['ks']==0): ?> selected = "selected" <?php endif; ?>>请选择</option>
				<?php foreach($arr_ks as $k=>$v): ?>
         	
				<option value="<?php echo $v['id']; ?>" <?php if($v['id']==$data['ks']): ?>	selected = "selected" <?php endif; ?>  ><?php echo $v['zlksgl_name']; ?></option>	
				
			    <?php endforeach; ?>     
            	</select>
            </li>
                 </ul>
        
      
        

        <ul>
            <li>
            	<label>在职/离职</label>
                <select id="clerk_office_type">
            	<option value="-1">请选择</option>
            
				<option value="1" <?php if(1==$data['clerk_office_type']): ?>	selected = "selected" <?php endif; ?>>在职</option>		
				<option value="0" <?php if(0==$data['clerk_office_type']): ?>	selected = "selected" <?php endif; ?>>离职</option>	
            	</select>
            </li>
        	<li><label>备注</label><textarea id="clerk_text" ><?php echo $data['clerk_text']; ?></textarea></li>
			<li><label>状态</label><input type="text" readonly="readonly" 
				 value="<?php if($data['clerk_status']==0): ?>已停用<?php else: ?>已启用<?php endif; ?>"
				 
				 
				 /></li>
            <li><label>创建人</label><input type="text" readonly="readonly"  value="<?php echo $data['creator']; ?>" /></li>
            <li><label>创建时间</label><input type="text" readonly="readonly" value="<?php echo $data['creator_atime']; ?>"  /></li>
            <li><label>最后修改人</label><input type="text" readonly="readonly"  value="<?php echo $data['upd_man']; ?>" /></li>
            <li><label>最后修改时间</label><input type="text" readonly="readonly"  value="<?php echo $data['upd_man_stime']; ?>" /></li>
        </ul>

    </div>
    
    
    

</div>




           
	</div>
</div>
<input  type="hidden" id="clerk_id" value="<?php echo $data['clerk_id']; ?>">
</body>
<script>
	function formatter(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hour = date.getHours();
		month = month < 10 ? '0' + month : month;
		day = day < 10 ? '0' + day : day;
		return year + "-" + month + "-" + day;
	}

function  update(){
	var clerk_office_id=$("#clerk_office_id").val();
	var clerk_office_num=$("#clerk_office_num").val();
	var clerk_name=$("#clerk_name").val();
	var clerk_sex=$("#clerk_sex").val();
	var clerk_phone=$("#clerk_phone").val();
	var clerk_place=$("#clerk_place").val();
	var clerk_card=$("#clerk_card").val();
	// var clerk_birth_date=$("#clerk_birth_date").val();
	var clerk_education=$("#clerk_education").val();
	var clerk_specialty=$("#clerk_specialty").val();
	var clerk_healthy =$("#clerk_healthy").val();
	var clerk_healthy_card=$("#clerk_healthy_card").val();
	//var clerk_healthy_carddate=$("#clerk_healthy_carddate").val();
	var clerk_office_name=$("#clerk_office_name").val();
	var clerk_user=$("#clerk_user").val();
	var clerk_pass=$("#clerk_pass").val();
	var clerk_office_type=$("#clerk_office_type").val();
	var clerk_text=$("#clerk_text").val();
	var creator=$("#creator").val();
	var clerk_id=$("#clerk_id").val();
	var is_code=$("#is_code").val();
	var ks=$("#ks").val();
	var clerk_birth_date = $('#clerk_birth_date').datebox('getValue');  
	var clerk_healthy_carddate = $('#clerk_healthy_carddate').datebox('getValue');  
	
	

	if(clerk_office_type==-1){
		$.messager.alert('职员管理','请选择离职状态','warning');
		return;
	}
	
	
	if(is_code==-1){
		$.messager.alert('职员管理','请选择诊疗人状态','warning');
		return;
	}



	$.ajax({
				url: "<?php echo url('index/clerkapi/clerk_upd'); ?>",
				type: 'POST',
				data: {        
					clerk_office_id :clerk_office_id,
					clerk_office_num :clerk_office_num,
					clerk_name :clerk_name,
					clerk_sex :clerk_sex,
					clerk_phone :clerk_phone,
					clerk_place :clerk_place,
					clerk_card :clerk_card,
					clerk_birth_date :clerk_birth_date,
					clerk_education :clerk_education,
					clerk_specialty :clerk_specialty,
					clerk_healthy  :clerk_healthy,
					clerk_healthy_card :clerk_healthy_card,
					clerk_healthy_carddate :clerk_healthy_carddate,
					clerk_office_name :clerk_office_name,
					clerk_user :clerk_user,
					clerk_pass :clerk_pass,
					clerk_office_type :clerk_office_type,
					creator :creator,
					clerk_id :clerk_id,
					is_code:is_code,
					ks:ks,
					clerk_text :clerk_text
				},
				success: function(res) {

				
				
					var res=JSON.parse( res );

					if(res.status==1){
						
						 $.messager.alert('提示', res.msg, 'info', function () {
                            window.location.href = "<?php echo url('index/clerk/index'); ?>";
                        })
					}else if(res.status==-1){
						$.messager.alert('职员管理',res.msg,'warning');
					}else{
					$.messager.alert('职员管理',res.msg,'warning');
					}
				}				
			})
}


</script>
</html>