<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\clinicbase\clinicbase_list.html";i:1538294665;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>诊所通V1.0.0</title>
    <link rel="stylesheet" href="/public/static/css/bootstrap.css" />
    <link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/static/css/css.css" />
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
    <script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
    <script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
    <link rel="shortcut icon" href="/public/static/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/public/static/js/kingeditor/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="/public/static/js/kingeditor/plugins/code/prettify.css">

<script type="text/javascript" src="/public/static/js/kingeditor/kindeditor.js"></script>
<script type="text/javascript" src="/public/static/js/kingeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="/public/static/js/kingeditor/plugins/code/prettify.js"></script>

 
</head>
<body>
   <ul class="breadcrumb">
    首页<span class="divider">/</span>基础管理<span class="divider">/</span>基础信息更新
</ul>
<div class="title_right"><strong>基础信息更新</strong></div>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="a_save" onclick="save()">保存</a>            
                </div>
            </div>
            <div class="flow">
            </div>
            
            <div class="form_table2" style="margin-top: 0">
                <div class="row">
                    <input type="hidden" id="id">
                    <form id="ff">
                        <ul>
                            <li><label>名称</label><input type="text" placeholder="必填"  value="<?php echo $data['shop_name']; ?>" id="shop_name"/></li>
                            <li><label style="line-height:16px;">医疗机构执业<br />许可证</label><input type="text" placeholder="必填" value="<?php echo $data['licence']; ?>" id="licence" />
                            <li><label>住所</label><textarea placeholder="必填" id="shop_address"><?php echo $data['shop_address']; ?></textarea></li>
                            <li><label>负责人</label><input type="text" placeholder="必填" value="<?php echo $data['leader']; ?>" id="leader"/></li>
                        </ul>
                        <ul>
                          
                            <li><label>成立日期</label><input type="text" placeholder="必填" <?php if($data['create_time']!='1970-01-01'): ?>value="<?php echo $data['create_time']; ?>" <?php endif; ?> class="easyui-datebox"   style="width:170px" editable="fasle" id="create_time"/></li>
                            <li><label>营业期限</label><input  name="busniss_date"
                                type="text" placeholder="必填" value="<?php echo $data['busniss_date']; ?>" 
                                 id="busniss_date" class="easyui-datebox"   style="width:120px" editable="fasle"/>
                            <input type="checkbox" onclick="checkboxOnclick(this)" id="checkbox" name="checkbox" <?php if($data['busniss_date']==null): ?>value="0"<?php endif; ?> >长期
                            </li>
                            <li><label>所在地区</label>
                                <select id="province" name="province" value="省" onchange="is_province()">
                                    <option value="0">请选择</option>
                                    <?php foreach($provincelist as $k=>$v): ?>
                                    <option value="<?php echo $v['ID']; ?>"<?php if($v['ID']==$data['province']): ?>selected="selected"<?php endif; ?>><?php echo $v['Name']; ?></option>
                                    <?php endforeach; ?>
                                </select></li>
                            <li><label>&nbsp;</label>
                                <select id="city" name="city" onchange="is_city()">
                                    <option>请选择</option>                           
                                </select>
                                </li>
                            <li><label>&nbsp;</label>
                                <select id="area" name="area">
                                    <option>请选择</option>                                  
                                </select
                                ></li>
                           
                        </ul>
                        <ul>
                            <li><label>开户户名</label><input type="text" value="<?php echo $data['bank_name']; ?>" id="bank_name" /></li>
                            <li><label>开户银行</label><input type="text"  value="<?php echo $data['bank_type']; ?>" id="bank_type"/></li>
                            <li><label>银行账号</label><input type="text"  value="<?php echo $data['bank_num']; ?>" id="bank_num"/></li>
                            <li><label>联系人</label><input type="text" placeholder="必填" value="<?php echo $data['contactor']; ?>" id="contactor" /></li>
                            <li><label>联系电话</label><input type="text" placeholder="必填" value="<?php echo $data['mobile']; ?>"  id="mobile"/></li>
                        </ul>
                        <ul>
                            <li><label>经营范围</label><textarea id="shop_scope"><?php echo $data['shop_scope']; ?></textarea></li>
                        </ul>
                       
                        <input type="hidden" id="details" name="details" />
                    </form>
                </div>
                <div id="win_patient" class="easyui-dialog" title="选择患者" style="width: 500px; height: 450px; padding: 10px"></div>
                <div id="win_medicine" class="easyui-dialog" title="选择药品" style="width: 600px; height: 450px; padding: 10px"></div>
            </div>
            <div id="divpadding" class="margin-top-5" style="clear: both; margin: 0 10px">
                <div region="center" style="width: 100%;" class="margin-top-5">
                    <div id="rangegrid" class="easyui-grid" style="min-height: 150px;"></div>
                </div>
                <div class="margin-top-5">
                    <a id="btnAddRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-add'" group="">添加行</a>
                    <a id="btnDelRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-delete'" group="">删除行</a>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="cityid" value="<?php echo $data['city']; ?>">
    <input type="hidden" id="areaid" value="<?php echo $data['town']; ?>">
	<input type="hidden" name="insertinfo" id="insertinfo">
	<input type="hidden" name="deleteinfo" id="deleteinfo">
	<input type="hidden" name="updateinfo" id="updateinfo">

<script>
		function  is_province(){
			var obj = document.getElementById('province');
			var index = obj.selectedIndex; 
			var value = obj.options[index].value;
			var text = obj.options[index].text; 
		 
			 $.ajax({
				url: "<?php echo url('index/home/setvalue'); ?>",
				type: "post",
				data: {provinceid:value},
				success: function (res) {
					$("#city").empty();
					var Pro2 = "<option value='0'>请选择</option>";
					$('#city').append(Pro2);
					var res=JSON.parse( res );
					var Pro1 = '';
					for( var i=0; i<res.length; i++){
						var Pro = '<option value='+res[i].ID+'>'+ res[i].Name +'</option>';
						Pro1 += Pro;
					}
					$('#city').append(Pro1);
					is_city();
				},
			});
		}
		function  is_city(){
		
			 var obj = document.getElementById('city');
			var index = obj.selectedIndex; 
			var value = obj.options[index].value;
		console.log(value)
			$.ajax({
				url: "<?php echo url('index/home/setvalue'); ?>",
				type: "post",
				data: {provinceid:value},
				success: function (res) {
					$("#area").empty();
					var Pro2 = "<option value='0'>请选择</option>";
					$('#area').append(Pro2);
					var res=JSON.parse( res );
					console.log(res)
					var Pro1 = '';
					for( var i=0; i<res.length; i++){
						var Pro = '<option value='+res[i].ID+'>'+ res[i].Name +'</option>';
						Pro1 += Pro;
					}
					$('#area').append(Pro1);
				 
				},
			});
		}
       
      
        function checkboxOnclick(checkbox){
        if ( checkbox.checked == true){
                $('#busniss_date').datebox({    
                required:true  ,
                disabled:true, 
                }); 
                var busniss_date = $("#busniss_date").textbox('getValue') 
                $("#busniss_date").textbox('setValue','')
              
        }else{
            $('#busniss_date').datebox({    
                required:true  ,
                disabled:false, 
                }); 

            
        }
        
        }
 
        $(document).ready(function () {
            $('#win_patient').dialog('close');
            $('#win_medicine').dialog('close');
        });
    
       
        var mustcolumnstyle = 'border:1px solid #1BBC9B;background-color:#eeeeee;';
		var backgroundcolor = 'background-color:#eeeeee;';
        $(function () {
            var cityid= $("#cityid").val();
            var areaid= $("#areaid").val();
			
            $.ajax({
                url: "<?php echo url('index/home/getValue'); ?>",
                type: "post",
                data: {
                    cityid:cityid,
                    areaid:areaid    
                    },
                success: function (res) {
                    var res=JSON.parse( res );
					
					var Pro2 = "<option value="+res.city.ID+" selected='selected'>"+res.city.Name+"</option>";
                    $('#city').append(Pro2);
                    var Pro3 = "<option value="+res.area.ID+" selected='selected'>"+res.area.Name+"</option>";
                    $('#area').append(Pro3);
					
					var Proo1 = '';
					
					for( var i=0; i<res.citys.length; i++){
				
						var Proo2 = '<option value='+res.citys[i].ID+'>'+ res.citys[i].Name +'</option>';	
										
						Proo1 += Proo2;
						
					}
					$('#city').append(Proo1);
					
					
					
					var P2 = '';
					for( var i=0; i<res.areas.length; i++){
				
						
						var P3 = '<option value='+res.areas[i].ID+'>'+ res.areas[i].Name +'</option>';						
						
						P2 += P3;
					}
					$('#area').append(P2);
					
				
                  
                },
            });





            var busniss_date = $("#busniss_date").textbox('getValue') 
           if(busniss_date){ }else{
            var checkbox = document.getElementById('busniss_date');//
            $(":checkbox").attr("checked", true);  
            $('#busniss_date').datebox({    
                required:true  ,
                disabled:true, 
                }); 
                var busniss_date = $("#busniss_date").textbox('getValue') 
                $("#busniss_date").textbox('setValue','')
           }
            

            $('#rangegrid').datagrid({
                methord: 'post',
                url: '/public/index.php/index/Clinicapi/Clinic_zz_list',
                fit:true,
                sortName: 'ID',
                sortOrder: 'desc',
                idField: 'id',
                showFooter: true,
                onAfterEdit: function (rowIndex, rowData, changes) {
                    editRow = undefined;
                },
                frozenColumns: [[
                    { field: 'ck', checkbox: true }
                ]],
                columns: [[
					{
						field: 'id', title: 'id', hidden: 'true'
					},
                    {
                        field: 'certificate_type', title: '资质/证书类型',editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
                            return mustcolumnstyle;
                        }
                    },
                    { field: 'certificate_num', title: '证书编号', fit: true , editor: { type: 'text' },styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'organization', title: '发证机构', fit: true ,editor: { type: 'text' }, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'certificate_atime', title: '发证日期', fit: true ,editor: { type: 'datebox' ,options: {editable: false}}, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'certificate_stime', title: '到期日期', fit: true ,editor: { type: 'datebox' ,options: {editable: false}}, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'scope', title: '许可/认证范围', fit: true ,editor: { type: 'text' }, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'certificate_name', title: '名字', fit: true ,editor: { type: 'text' }, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'certificate_identity', title: '身份证号',editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
					},
                    { field: 'certificate_contacts', title: '联系电话', editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
                            return backgroundcolor;
                        }
                   },
                   {
                        field: 'file', title: '资料文件', align: 'center',
                        styler: function (value, row, index) {
                            return backgroundcolor;
                        } ,
                        formatter: function (value, row, index) {
                            var str = zlwjedit(row.Url);
                            return str;
                        }
                    },

                    { field: 'certificate_test', title: '备注',editor: { type: 'text' }, fit: true, styler: function (value, row, index) {
                            return backgroundcolor;
                        } 
                     },
					  {
                    field: 'imgurl', title: 'Url', hidden: 'true', fit: true, editor: 'text'
					},
                ]],
                fit: false,
                pagination: false,
                rownumbers: true,
                fitColumns: true,
                singleSelect: false,
                onDblClickCell: function (index, field, value) {
                }
            }).datagrid('enableCellEditing');
           
            var IsSubmit = true;
           
            $("#btnAddRow").click(function () {
                $("#rangegrid").datagrid('insertRow', {
                    index: 0,
                    row: {
						certificate_type:  "",
						certificate_num: "" ,
						organization  :"",                  
						certificate_atime :"",    
						certificate_stime :"", 
						certificate_name:"",							
						scope:"",    
						organization       :"",         
						certificate_identity    :"",          
						certificate_contacts:"",    
						imgurl:"",    
						certificate_test:"",  
					}
                });
                return false;
            });
            $("#btnDelRow").click(function () {
                var rows = $('#rangegrid').datagrid('getSelections');
                if (rows) {
                    $.messager.confirm('删除提示', '确定要删除吗？', function (r) {
                        if (r) {
                            var rows = $('#rangegrid').datagrid("getSelections");
							
							var arr=new Array()
                            for (var i = 0; i < rows.length; i++) {
                                var index = $('#rangegrid').datagrid('getRowIndex', rows[i]);
								 arr[i]=index;												
                             //   $('#rangegrid').datagrid('deleteRow', index);
                            }												
							var temp;
							for(var i=0; i<arr.length;i++)
							{
								for(var j=i+1;j<arr.length;j++)
								{
									 if(arr[i]<arr[j])
									  {
										 temp=arr[i];
										 arr[i]=arr[j];
										 arr[j]=temp;
									   }
								 }
							}						
							for (var i = 0; i < arr.length; i++) {						
                               $('#rangegrid').datagrid('deleteRow', arr[i]);
                            }
												
							
                        }
                        else {
                            $.messager.alert('提示信息', '您没有选中任何行！', 'warning');
                            $('#rangegrid').parent().find("div .datagrid-header-check").children("input[type='checkbox']").eq(0).attr("checked", false);
                        }
                    });
                    return false
                }
            });
        });
        var editIndex = undefined;
        function endEditing() {
            if (editIndex == undefined) { return true }
            if ($('#rangegrid').datagrid('validateRow', editIndex)) {
                $('#rangegrid').datagrid('endEdit', editIndex);
                editIndex = undefined;
                return true;
            } else {
                return false;
            }
        }
        function onClickCell(index, field) {
            if (editIndex != index) {
                if (endEditing()) {
                    $('#rangegrid').datagrid('selectRow', index)
                        .datagrid('beginEdit', index);
                    var ed = $('#rangegrid').datagrid('getEditor', { index: index, field: field });
                    if (ed) {
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    editIndex = index;
                } else {
                    $('#rangegrid').datagrid('selectRow', editIndex);
                }
            }
        }
        function zlwjedit(url) {
            if (url == "")
                return "<input id='btnupload' type='button'  onclick='ShowPic(this)' value='查看' disabled='true' /><input id='btnupload' type='button' onclick='Upload(this)' value='上传' />";
            else
                return "<input id='btnupload' type='button' onclick='ShowPic(this)' value='查看' /><input id='btnupload' type='button' onclick='Upload(this)' value='上传' />";
        }
		var editor1;
		KindEditor.ready(function (K) {
			editor1 = K.editor({
			  cssPath: '/public/static/js/kingeditor/plugins/code/prettify.css',
				 uploadJson: "<?php echo url('index/home/upload'); ?>",      //上传地址
			
			});
			/*K('#upload_pic').click(function () {
				editor.loadPlugin('image', function () {
					editor.plugin.imageDialog({
						imageUrl: K('#hidpic').val(),
						showRemote: false,
						clickFn: function (url, title) {
							K('#hidpic').val(url);
							disablebtn(1, 1);
							$.messager.alert("提示", "商品图片上传成功");
							editor.hideDialog();
						}
					});
				});
			});*/
		});
		function Upload(obj) {
        var uploadRowIndex = $(obj).parent().parent("td[field='file']").parent("tr").index();
        editor1.loadPlugin('image', function () {
            editor1.plugin.imageDialog({
                showRemote: false,
                fileUrl: "",
                clickFn: function (url, title) {

                    var roleObj = $(".datagrid-view2 .datagrid-body table  tbody tr:eq(" + uploadRowIndex + ")");

                    $(roleObj).find("td[field='imgurl']").children("div").click();
                    $(roleObj).find("td[field='imgurl']").children("div").find("input").val(url);
                    $(roleObj).find("td[field='file']").children("div").click();
                    $(roleObj).find("td[field='file']").children("div").val(zlwjedit(url));
                    $('#rangegrid').datagrid('endEdit', uploadRowIndex);
                    editor1.hideDialog();
                }
            });
        });
    }
	 function ShowPic(obj) {
        var url = "";
        var uploadRowIndex = $(obj).parent().parent("td[field='file']").parent("tr").index();
        var roleObj = $(".datagrid-view2 .datagrid-body table  tbody tr:eq(" + uploadRowIndex + ")");
        $(roleObj).find("td[field='imgurl']").children("div").click();
        url = $(roleObj).find("td[field='imgurl']").children("div").find("input").val();
        $('#rangegrid').datagrid('endEdit', uploadRowIndex);
        if (url != "") {
            window.open(url);
        }
    }
		

        $.extend($.fn.datagrid.methods, {
            editCell: function (jq, param) {
                return jq.each(function () {
                    var opts = $(this).datagrid('options');
                    var fields = $(this).datagrid('getColumnFields', true).concat($(this).datagrid('getColumnFields'));
                    for (var i = 0; i < fields.length; i++) {
                        var col = $(this).datagrid('getColumnOption', fields[i]);
                        col.editor1 = col.editor;
                        if (fields[i] != param.field) {
                            col.editor = null;
                        }
                    }
                    $(this).datagrid('beginEdit', param.index);
                    var ed = $(this).datagrid('getEditor', param);
                    if (ed) {
                        editIndex = param.index;
                        if (param.field == "certificate_type") {
						
                            $(ed.target).prop('readonly', true);
                            $(ed.target).bind('dblclick', function (e) {
                                $('#rangegrid').datagrid('endEdit', editIndex);
                                $('#win_medicine').dialog({
                                    title: "选择",
                                    height: 450,
                                    width: 750,
                                    top: 100,
                                    left: 200,
                                    href: "win_medicine",
                                    buttons: [{
                                        text: '确定',
                                        iconCls: 'icon-ok',
                                        handler: function () {
											var rows = $('#selectMedi').datagrid('getSelections');
                                             var allsalerows = $('#rangegrid').datagrid('getRows');

                                           
                                            if (rows != null) {
                                                  var cindex = param.index;
												  
                                                $.each(rows, function (idx, obj) {

                                                    var r = rows[idx];
											
                                                    isrow = false;
                                                    $.each(allsalerows, function (rindex, robj) {
												
                                                        if (allsalerows[rindex].certificate_type && r.zzgl_name == allsalerows[rindex].certificate_type) {
                                                            isrow = true;
                                                            return false;
                                                        }
                                                    });
												
                                                    if (!isrow) {
                                                        $('#rangegrid').datagrid('insertRow', {
                                                        index: editIndex + 1,
                                                        row: { 
															certificate_type: r.zzgl_name ,
															certificate_num: r.zzgl_id ,
															organization  :"",                  
															certificate_atime :"",    
															certificate_stime :"", 
															certificate_name:"",							
															scope:"",    
															organization       :"",         
															certificate_identity    :"",          
															certificate_contacts:"",    
															imgurl:"",    
															certificate_test:"",    
														}
														});
                                                    }else {
                                                        $.messager.alert("提示", "当前列表中已存在该资质/证书类型，不能重复添加！");
                                                        return false;
                                                    }
                                                    

                                                });
                        
                                                $('#rangegrid').datagrid('deleteRow', editIndex);
                                                $('#win_medicine').dialog('close');
     
                                            }
                                        }
                                    }, {
                                        text: '取消',
                                        handler: function () {
                                            $('#win_medicine').dialog('close');
                                        }
                                    }]
                                });
                            });
                        }
                        else if(param.field == "certificate_atime"){
                            //$(ed.target).prop('readonly', true);
                            $(ed.target).datebox({
                                onChange: function(newValue, oldValue){
                                    if(newValue!=oldValue)
                                    {
                                        $('#rangegrid').datagrid('endEdit', editIndex);
                                    }
                                }
                            });
                        }
                        else if(param.field == "certificate_stime"){
                            //$(ed.target).prop('readonly', true);
                            $(ed.target).datebox({
                                onChange: function(newValue, oldValue){
                                    if(newValue!=oldValue)
                                    {
                                        $('#rangegrid').datagrid('endEdit', editIndex);
                                    }
                                }
                            });
                        }
                        if ($(ed.target).hasClass('textbox-f')) {
                            $(ed.target).textbox('textbox').focus();
                        } else {
                            $(ed.target).focus();
                        }
                    }
                    for (var i = 0; i < fields.length; i++) {
                        var col = $(this).datagrid('getColumnOption', fields[i]);
                        col.editor = col.editor1;
                    }
                });
            },
            enableCellEditing: function (jq) {
                return jq.each(function () {
                    var dg = $(this);
                    var opts = dg.datagrid('options');
                    opts.oldOnClickCell = opts.onClickCell;
                    opts.onClickCell = function (index, field) {
                        if (opts.editIndex != undefined) {
                            if (dg.datagrid('validateRow', opts.editIndex)) {
                                dg.datagrid('endEdit', opts.editIndex);
                                opts.editIndex = undefined;
                            } else {
                                return;
                            }
                        }
                        dg.datagrid('selectRow', index).datagrid('editCell', {
                            index: index,
                            field: field
                        });
                        opts.editIndex = index;
                        opts.oldOnClickCell.call(this, index, field);
                    };
                });
            }
			
        });

function save(){
    for (var i = 0; i < $("#rangegrid").datagrid("getRows").length; i++) {
            $('#rangegrid').datagrid('endEdit', i);
        }
        if ($("#rangegrid").datagrid("getRows").length > 0)
            $("#detail").val(JSON.stringify($("#rangegrid").datagrid("getRows")));

        if ($('#rangegrid').datagrid('getChanges').length) {
            var inserted = $('#rangegrid').datagrid('getChanges', "inserted");
			
            var deleted = $('#rangegrid').datagrid('getChanges', "deleted");
            var updated = $('#rangegrid').datagrid('getChanges', "updated");
            var effectRow = new Object();
            if (inserted.length) {
			$("#insertinfo").val(JSON.stringify(inserted));
            }
            if (deleted.length && deleted != undefined) {
        $("#deleteinfo").val(JSON.stringify(deleted));
            }
            if (updated.length && updated != undefined) {
          $("#updateinfo").val(JSON.stringify(updated));
            }
        }
   
    var rows = $("#rangegrid").datagrid("getRows"); 


    var shop_name=$('#shop_name').val(); 
	var licence=$('#licence').val(); 
	var shop_address=$('#shop_address').val(); 
	var leader=$('#leader').val(); 
   //  var create_time=$('#create_time').val(); 
   //  var busniss_date=$('#busniss_date').val(); 
	var bank_name=$('#bank_name').val(); 
	var bank_type=$('#bank_type').val(); 
	var bank_num=$('#bank_num').val(); 
	var contactor=$('#contactor').val(); 
	var mobile=$('#mobile').val(); 
	var shop_scope=$('#shop_scope').val(); 
	var create_time = $('#create_time').datebox('getValue');   
	var busniss_date = $('#busniss_date').datebox('getValue');  
	var province=$('#province').val(); 
	var city=$('#city').val(); 
	var area=$('#area').val(); 	 
	var insertinfo=$('#insertinfo').val(); 	
	var deleteinfo=$('#deleteinfo').val(); 	
	var updateinfo=$('#updateinfo').val(); 	

   



    $.ajax({ 
                url: "<?php echo url('index/Clinicapi/upd'); ?>",
                type: 'POST', 
                data: {        		 
                    shop_name:shop_name, 
                    licence:licence, 
                    shop_address:shop_address, 
                    leader:leader, 
                    create_time:create_time, 
                    busniss_date:busniss_date, 
                    bank_name:bank_name, 
                    bank_type:bank_type, 
                    bank_num:bank_num, 
                    contactor:contactor, 
                    mobile:mobile, 
                    rows:rows, 
					province:province,
					city:city,
					town:area,
					insertinfo:insertinfo,
					deleteinfo:deleteinfo,
					updateinfo:updateinfo,
                    shop_scope:shop_scope 
                }, 
                success: function(res) {	 
   
                    var res=JSON.parse( res ); 
                    if(res.status==1){ 
                     
						$.messager.alert('提示', res.msg, 'info', function () {
                            window.location.href = "<?php echo url('index/clinicbase/clinicbase_list'); ?>";
                        })						
                    }else{ 
                        $.messager.alert('基础信息更新',res.msg,'warning'); 
                    } 
                }				 
            }) 



}
//双击事件
function onDBClickCell(index, field) { 

 
 var rows = $('#selectMedi').datagrid('getRows');
 var row = rows[index];
 var rangegrid_rows = $('#rangegrid').datagrid('getRows');
 var row_index = rangegrid_rows[editIndex];


var isrow2=false;
$.each(rangegrid_rows, function (rindex, robj) {

if (rangegrid_rows[rindex].certificate_type && row.zzgl_name == rangegrid_rows[rindex].certificate_type) {
	isrow2 = true;
	return false;
}
});
if (!isrow2) {
	$('#rangegrid').datagrid('insertRow', {
	index: editIndex + 1,
	row: { 
		certificate_type: row.zzgl_name ,
		certificate_num: row.zzgl_id ,
		organization  :"",                  
		certificate_atime :"",    
		certificate_stime :"", 
		certificate_name:"",							
		scope:"",    
		organization       :"",         
		certificate_identity    :"",          
		certificate_contacts:"",    
		imgurl:"",    
		certificate_test:"",    
	}
	});
	   $('#rangegrid').datagrid('deleteRow', editIndex);
                                             
	$('#win_medicine').dialog('close');
}else{
$('#win_medicine').dialog('close');

	$.messager.alert("提示", "当前列表中已存在该资质/证书类型，不能重复添加！");
	return false;
}

	


<!-- console.log(isrow2); -->
	<!-- $('#rangegrid').datagrid('updateRow',{ -->
		<!-- index: editIndex, -->
		<!-- row: { -->
			<!-- certificate_type: row.zzgl_name , -->
			<!-- certificate_num: row.zzgl_id , -->
			<!-- organization  :"",                   -->
			<!-- certificate_atime :"",     -->
			<!-- certificate_stime :"",  -->
			<!-- certificate_name:"",							 -->
			<!-- scope:"",     -->
			<!-- organization       :"",          -->
			<!-- certificate_identity    :"",           -->
			<!-- certificate_contacts:"",     -->
			<!-- imgurl:"",     -->
			<!-- certificate_test:"",     -->
		<!-- } -->
	<!-- }); -->
   <!-- $('#rangegrid').datagrid('endEdit', editIndex); -->
   <!-- editIndex = undefined; -->
   
  <!-- $('#win_medicine').dialog('close');                        -->
		
	
	}
</script>
</body>
</html>
