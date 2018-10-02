<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\base\supplier_add.html";i:1538184838;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    首页<span class="divider">/</span>基础管理<span class="divider">/</span>供货商管理
</ul>
<div class="title_right"><strong>供货商管理</strong></div>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="a_save" ">保存</a><!--onclick="submitForm()-->
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:commit()">提交</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/base/supplier'); ?>">列表</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
                    <!--a class="easyui-linkbutton" data-options="iconCls:'icon-other7'">冻结</a-->
                    <!--a class="easyui-linkbutton" data-options="iconCls:'icon-other5'" href="javascript:supply(1)">暂停供货</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other6'" href="javascript:supply(0)">恢复供货</a-->
                    
                </div>


<div class="form_table2">

<div class="row">
<input type="hidden" id="id">
<form id="ff">
    <ul>
        <li><label>供货商编码</label><input type="text" name="supplier_code" id="supplier_code" value="<?php echo $supplier_id; ?>" readonly="readonly"/></li>
        <li><label>供货商简码</label><input type="text" name="supplier_scode" id="supplier_scode" readonly="readonly"/></li>

        <li><label>供货商名称</label><input type="text" placeholder="必填" name="supplier_name" id="supplier_name" onchange="jianma()"/></li>
        <li><label>营业执照号</label><input type="text" placeholder="必填" name="business_license" id="business_license" /></li>
        <li><label id="ipt_area">注册地址</label><textarea placeholder="必填" name="register_addr" id="register_addr"></textarea></li>
        <li><label id="ipt_area">生产/仓库地址</label><textarea placeholder="必填" name="warehouse_addr" id="warehouse_addr"></textarea></li>
    </ul>

    <ul> 
        <li><label>省份</label><select name="province" id="province" style="width:173px;" onchange="is_province()">
		<option>请选择</option>
		<?php if(is_array($provincelist) || $provincelist instanceof \think\Collection || $provincelist instanceof \think\Paginator): $i = 0; $__LIST__ = $provincelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<option value="<?php echo $vo['ID']; ?>"><?php echo $vo['Name']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		</select></li>
        <li><label>县市</label><select name="city" id="city" style="width:173px;" onchange="is_city()"><option>请选择</option></select></li>
        <li><label>地区</label><select name="area" id="area" style="width:173px;" ><option>请选择</option></select></li>
    
       <li><label>法定代表人</label><input type="text" name="legal_representative" id="legal_representative" /></li>
        <li><label>企业负责人</label><input type="text" name="business_person" id="business_person" /></li>
        <li><label>质量负责人</label><input type="text" name="quality_person" id="quality_person" /></li>
    <li><label>联系人</label><input type="text" name="contacts_person" id="contacts_person" /></li>
    <li><label>联系电话</label><input type="text" name="contacts_phone" id="contacts_phone" /></li>

</ul>
<ul> 
        <li><label>开户户名</label><input type="text" name="account_person" id="account_person" /></li>
        <li><label>开户银行</label><input type="text" name="account_bank" id="account_bank" /></li>
        <li><label>银行账号</label><input type="text" name="account_number" id="account_number" /></li>
        
        <li>
            <label>单位类型</label>
            <select name="business_type" id="business_type">
                <option>请选择</option>
				<?php if(is_array($business_type) || $business_type instanceof \think\Collection || $business_type instanceof \think\Paginator): $i = 0; $__LIST__ = $business_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value=<?php echo $vo['id']; ?>><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
        <!--li><label>经营范围</label><textarea placeholder="点击选择" style="border:1px solid #1BBC9B" id="business_scope"></textarea></li--><!--readonly="readonly" 不可修改-->
    <li><label>电子邮箱</label><input type="text" name="email" id="email" /></li>
        <li><label>档案编号</label><input type="text" name="file_number" id="file_number" value="<?php echo $file_id; ?>" readonly="readonly"/></li>
		<li><label>备注</label><textarea name="remarks" id="remarks"></textarea></li>
        
</ul>

<input type="hidden" id="details" name="details" />

</form>
    </div>
	
<div id="win_cert" class="easyui-dialog" title="选择资质证明" style="width: 600px; height: 450px; padding: 10px"></div>
			</div>
			<div id="divpadding" class="margin-top-5" style="clear:both">

			<div region="center" style="width: 100%;" class="margin-top-5">
                <div id="rangegrid" class="easyui-grid" style="min-height:150px;"></div>
            </div>
			
            <div class="margin-top-5">
    <a id="btnAddRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-add'">添加行</a>
    <a id="btnDelRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-del'">删除行</a>
</div>
</div>
</div>
          
    <script>
        $(document).ready(function () {
            $('#win_cert').dialog('close');
        });
		
		function jianma(){
			var name = $('#supplier_name').val();
			$.ajax({
				url: "/index.php/index/base/getpinyin",
				type: "post",
				data: {keyword:name},
				success: function (res) {
					$('#supplier_scode').val(res.str);
				},
			});
		}
		function  is_province(){
			var obj = document.getElementById('province');
			var index = obj.selectedIndex; 
			var value = obj.options[index].value;
			var text = obj.options[index].text; 
		 
			 $.ajax({
				url: "/index.php/index/home/setvalue",
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
				url: "/index.php/index/home/setvalue",
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

		function commit(){
			var id = $('#id').val();
			if(!id){
				$.messager.alert('提示','请先保存','warning'); return;
			}
			$.ajax({
				url:'/index.php/index/base/supplier_commit_api',
				type:"POST",
				data:{'id':id},
				success:function(data){
					if(data.status==1){
						$.messager.alert('提示','提交成功！','info'); return;
					}
				}
			});
		}
		function supply(type){
			var id = $('#id').val();
			if(!id){
				$.messager.alert('提示','请先保存','warning'); return;
			}
			$.ajax({
				url:'/index.php/index/base/supplier_supply_api',
				type:"POST",
				data:{'id':id,'type':type},
				success:function(data){
					if(data.status==1){
						$.messager.alert('提示','修改成功！','info'); return;
					}
				}
			});
		}
        var mustcolumnstyle = 'border:1px solid #1BBC9B;background-color:#eeeeee;';
		//var backgroundcolor = 'background-color:#eeeeee;';
        $(function () {
            $('#rangegrid').datagrid({
                methord: 'get',
                url: '',
                sortName: 'ID',
                sortOrder: 'desc',
                idField: '',
                pageSize: 50,
                showFooter: true,
                onAfterEdit: function (rowIndex, rowData, changes) {
                    editRow = undefined;
                },
                frozenColumns: [[
                    { field: 'ck', checkbox: true }
                ]],
                columns: [[
                    {
                        field: 'certname', title: '企业资料类型名', editor: { type: 'text' },  fit: true, styler:
                        function (value, row, index) {
                            return mustcolumnstyle;
                        }

                    },
                    { field: 'number', title: '证书编号', editor: { type: 'text' }, fit: true },
                    { field: 'office', title: '发证机关', editor: { type: 'text' }, fit: true },
                    {
                        field: 'createtime', title: '发证日期    ', editor: { type: 'datebox' ,options: {editable: false}}, width: '100px'
                    },
                    {
                        field: 'expiretime', title: '到期日期    ', editor: { type: 'datebox' ,options: {editable: false}}, width: '100px'
                    },
                    { field: 'scope', title: '许可/认证范围', editor: { type: 'text' }, fit: true },
                    { field: 'username', title: '姓名', editor: { type: 'text' }, fit: true },
                    { field: 'idcard', title: '身份证号', editor: { type: 'text' }, fit: true },
                    { field: 'phone', title: '联系电话', editor: { type: 'text' }, fit: true },
                    {
                        field: 'file', title: '资料文件', align: 'center', width: '100px',
                        formatter: function (value, row, index) {
                            var str = zlwjedit(row.Url);
                            return str;
                        }
                    },
                    { field: 'remarks', title: '备注', editor: { type: 'text' }, fit: true, width: '300px' },
                    {
                        field: 'FileUrl', title: 'Url', hidden: 'true', fit: true, editor: 'text'
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
            
			$.fn.datebox.defaults.formatter = function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				m = m < 10 ? '0' + m : m;
				d = d < 10 ? '0' + d : d;
				//m+'/'+d+'/'+y;
				return y+'-'+m+'-'+d;
			}
			$.fn.datebox.defaults.parser = function(s){
				var t = Date.parse(s);
				if (!isNaN(t)){
					return new Date(t);
				} else {
					return new Date();
				}
			}
		
            var IsSubmit = true;
            $("#a_save").click(function () {
				//判断是否填写完整
				if(!$('#supplier_name').val() || !$('#business_license').val() || !$('#register_addr').val() || !$('#warehouse_addr').val()){
					$.messager.alert('提示','请填写完整！','warning');
					return false;
				}
				var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;
				//电话
				var phone = $.trim($('#contacts_phone').val());
				if (phone && !phoneReg.test(phone)) {
					$.messager.alert('提示','请输入有效的手机号码！','warning');
					return false;
				}
				var emailReg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
				//邮箱
				var email = $.trim($('#email').val());
				if (email && !emailReg.test(email)) {
					$.messager.alert('提示','请输入有效的电子邮箱！','warning');
					return false;
				}
				for (var i = 0; i < $("#rangegrid").datagrid("getRows").length; i++) {
					$('#rangegrid').datagrid('endEdit', i);
				}
				if ($("#rangegrid").datagrid("getRows").length > 0)
					$("#details").val(JSON.stringify($("#rangegrid").datagrid("getRows")));
					$.post("/index.php/index/base/supplier_add_api", $("form").serialize(),
					function (s) {
						if (s.status == 1) {
							$.messager.alert('提示','保存成功！','info',function(){
								//window.location.href = '/index.php/index/base/supplier_edit?id=' + s.id;
								window.location.href = '/index.php/index/base/supplier';
							});
										
							return false;
						}
                        else {
                            $.messager.alert('操作提示', s.message, 'error');
                                }
                            }, "json");
            });
            $("#btnAddRow").click(function () {
                $("#rangegrid").datagrid('insertRow', {
                    index: 0,
                    row: {}
                });
                return false;
            });
            $("#btnDelRow").click(function () {
                var rows = $('#rangegrid').datagrid('getSelections');
                if (rows) {
                    $.messager.confirm('删除提示', '确定要删除吗？', function (r) {
                        if (r) {
                            var rows = $('#rangegrid').datagrid("getSelections");
                            for (var i = 0; i < rows.length; i++) {
                                var index = $('#rangegrid').datagrid('getRowIndex', rows[i]);
                                $('#rangegrid').datagrid('deleteRow', index);
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
		
        function zlwjedit(url) {
            if (url == "")
                return "<input id='btnupload' type='button' onclick='ShowPic(this)' value='查看' disabled='true' /><input id='btnupload' type='button' onclick='Upload(this)' value='上传' />";
            else
                return "<input id='btnupload' type='button' onclick='ShowPic(this)' value='查看' /><input id='btnupload' type='button' onclick='Upload(this)' value='上传' />";
        }
		var editor1;
		KindEditor.ready(function (K) {
			editor1 = K.editor({
				cssPath: '/public/static/js/kingeditor/plugins/code/prettify.css',
				uploadJson: "<?php echo url('index/home/upload'); ?>",
				//fileManagerJson: '/public/static/js/kingeditor/file_manager_json.ashx',
				//allowFileManager: false
			});

		});
		function Upload(obj) {
			var uploadRowIndex = $(obj).parent().parent("td[field='file']").parent("tr").index();
			editor1.loadPlugin('image', function () {
				editor1.plugin.imageDialog({
					showRemote: false,
					fileUrl: "",
					clickFn: function (url, title) {
						var roleObj = $(".datagrid-view2 .datagrid-body table  tbody tr:eq(" + uploadRowIndex + ")");
						$(roleObj).find("td[field='FileUrl']").children("div").click();
						$(roleObj).find("td[field='FileUrl']").children("div").find("input").val(url);
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
			$(roleObj).find("td[field='FileUrl']").children("div").click();
			url = $(roleObj).find("td[field='FileUrl']").children("div").find("input").val();
			$('#rangegrid').datagrid('endEdit', uploadRowIndex);
			if (url != "") {
				window.open(url);
			}
		}
		
		
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
                        if (param.field == "certname") {
                            $(ed.target).prop('readonly', true);
                            $(ed.target).bind('dblclick', function (e) {
                                $('#rangegrid').datagrid('endEdit', editIndex);
                                $('#win_cert').dialog({
                                    title: "选择",
                                    height: 450,
                                    width: 700,
                                    top: 100,
                                    left: 200,
                                    href: "win_cert",
                                    buttons: [{
                                        text: '确定',
                                        iconCls: 'icon-ok',
                                        handler: function () {
                                            var rows = $('#selectGrid').datagrid('getSelections');
											var allsalerows = $('#rangegrid').datagrid('getRows');

											console.log(rows);
                                            if (rows != null) {
											/*
                                                for (var i = 0; i < rows.length; i++) {
                                                    $('#rangegrid').datagrid('insertRow', {
                                                        index: editIndex + 1,
                                                        row: { certname: rows[i].certname}
                                                    });
                                                }
                                                $('#rangegrid').datagrid('deleteRow', editIndex);
                                                $('#win_cert').dialog('close');
												*/
												var cindex = param.index;
                                                $.each(rows, function (idx, obj) {

                                                    var r = rows[idx];
                                                    isrow = false;
                                                    $.each(allsalerows, function (rindex, robj) {
                                                        if (allsalerows[rindex].certname && r.certname == allsalerows[rindex].certname) {
                                                            isrow = true;
                                                            return false;
                                                        }
                                                    });
                                                    if (!isrow) {
                                                        $('#rangegrid').datagrid('insertRow', {
															index: editIndex + 1,
															row: { certname: rows[idx].certname}
														});
                                                    }
                                                    else {
                                                        $.messager.alert("提示", "当前列表中已存在该商品类型，不能重复添加！");
                                                        return false;
                                                    }

                                                });
												
                                                $('#rangegrid').datagrid('deleteRow', editIndex);
                                                $('#win_cert').dialog('close');

                                            }
                                        }
                                    }, {
                                        text: '取消',
                                        handler: function () {
                                            $('#win_cert').dialog('close');
                                        }
                                    }]
                                });
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

    </script>
</body>
</html>
