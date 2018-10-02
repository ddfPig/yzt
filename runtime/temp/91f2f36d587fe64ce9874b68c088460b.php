<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\acography_edit.html";i:1538292834;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
    首页<span class="divider">/</span>诊疗管理<span class="divider">/</span>诊疗记录管理
</ul>
<div class="title_right"><strong>诊疗记录管理</strong></div>
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
                	<!--a class="easyui-linkbutton" data-options="iconCls:'icon-user_add'">选择患者</a-->
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="a_save">保存</a>
					<?php if($data['status'] == '0'): ?>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" onclick="confirm()">确认诊疗</a>
					<?php endif; if($data['status'] == '1'): ?>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other9'" onclick="pay()">结算收款</a>
					<?php endif; if($data['status'] == '2'): ?>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other9'" onclick="pay()">结算收款</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-other10'" onclick="refund()" >退款</a>
					<?php endif; ?>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/cure/acography'); ?>">列表</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="javascript:location.reload();">刷新</a>
                </div>




<div class="flow">
	<?php if($data['status'] == '0'): ?>
    <ul>
        <li class="check">
            <span><?php echo $data['save_name']; ?></span><span>已保存</span><br />
            <span><?php echo $data['save_date']; ?></span>
        </li>
	</ul>
	<ul>
        <li style="line-height:35px">
			<span>待确认</span>
		</li>
    </ul>
	<?php endif; if($data['status'] == '1'): ?>
    <ul>
        <li class="check">
            <span><?php echo $data['save_name']; ?></span><span>已保存</span><br />
            <span><?php echo $data['save_date']; ?></span>
        </li>
    </ul>
    <ul>
        <li class="check">
            <span><?php echo $data['confirm_name']; ?></span><span>已确认</span><br />
            <span><?php echo $data['confirm_date']; ?></span>
        </li>
    </ul>
	<ul>
        <li style="line-height:35px">
			<span>待收款</span>
		</li>
    </ul>
	<?php endif; if($data['status'] == '2'): ?>
    <ul>
        <li class="check">
            <span><?php echo $data['save_name']; ?></span><span>已保存</span><br />
            <span><?php echo $data['save_date']; ?></span>
        </li>
    </ul>
    <ul>
        <li class="check">
            <span><?php echo $data['confirm_name']; ?></span><span>已确认</span><br />
            <span><?php echo $data['confirm_date']; ?></span>
        </li>
    </ul>
    <ul>
        <li class="check">
            <span><?php echo $data['payee_name']; ?></span><span>已收款</span><br />
            <span><?php echo $data['payee_date']; ?></span>
        </li>
    </ul>
    <ul class="check"><li style="line-height:35px"><span>诊疗结束</span></li></ul>
	<?php endif; ?>
</div>

<input type="hidden" id="status" value="<?php echo $data['status']; ?>">
<div class="form_table2" style="margin-top:0">
    <div class="row">
	<form id="ff">
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <ul>
            <li><label>诊疗科室</label><select name="department_id" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>>
			<?php if(is_array($department_id) || $department_id instanceof \think\Collection || $department_id instanceof \think\Paginator): $i = 0; $__LIST__ = $department_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value="<?php echo $vo['id']; ?>" <?php if($data['department_id'] == $vo['id']): ?> selected <?php endif; ?>><?php echo $vo['name']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
			</select></li>
            <li><label>患者编号</label><input type="text" readonly="readonly" name="patient_number" value="<?php echo $data['patient_number']; ?>"/></li>
            <li><label>姓名</label><input type="text" name="name" readonly="readonly" value="<?php echo $data['name']; ?>" /></li>
            <li><label>性别</label><select name="sex" readonly="readonly" ><option>男</option><option>女</option></select></li>
            <li><label>年龄</label><input type="text" name="age" readonly="readonly" value="<?php echo $data['age']; ?>" /></li>
            <li><label>手机号</label><input type="text" name="phone" readonly="readonly" value="<?php echo $data['phone']; ?>" /></li>
        </ul>
    	<ul>
            <li><label>基本病症</label><textarea name="disease" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>><?php echo $data['disease']; ?></textarea></li>
            <li><label>问诊内容</label><textarea name="content" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>><?php echo $data['content']; ?></textarea></li>
            <li><label>诊疗结果</label>
			<select name="result" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>>
            <option value=0>未选择</option>
			<?php if(is_array($result_id) || $result_id instanceof \think\Collection || $result_id instanceof \think\Paginator): $i = 0; $__LIST__ = $result_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option value="<?php echo $vo['id']; ?>" <?php if($data['result'] == $vo['id']): ?> selected <?php endif; ?>><?php echo $vo['name']; ?></option>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</select></li>
        </ul>
    	<ul>
            <li><label>医嘱指导</label><textarea name="advice" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>><?php echo $data['advice']; ?></textarea></li>  
            <li><label>诊疗记录编号</label><input type="text" readonly="readonly"  name="number" value="<?php echo $data['number']; ?>" /></li>
            <li><label>备注</label><textarea name="remarks" <?php if($data['status'] >= '1'): ?> readonly="readonly" <?php endif; ?>><?php echo $data['remarks']; ?></textarea></li>            
        </ul>
        
<input type="hidden" id="deleteinfo" name="deleteinfo" />
<input type="hidden" id="insertinfo" name="insertinfo" />
<input type="hidden" id="updateinfo" name="updateinfo" />
	</form>
    </div>
	
    <div id="win_medicine" class="easyui-dialog" title="选择药品" style="width: 600px; height: 450px; padding: 10px"></div>
</div>

              
     
<div id="divpadding" class="margin-top-5" style="clear:both;margin:0 10px">
			<div region="center" style="width: 100%;" class="margin-top-5">
                <div id="rangegrid" class="easyui-grid" style="min-height:150px;"></div>
            </div>
<div class="margin-top-5">
    <a id="btnAddRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-add'" group="">添加行</a>
    <a id="btnDelRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-delete'" group="">删除行</a>
</div>

   </div>
                     
</div>


	<script>
	
    $(document).ready(function () {
        $('#win_medicine').dialog('close');
        });
	function confirm(){
	var id = $('#id').val();
	var status = $('#status').val();
	if(status==1){
		$.messager.alert('提示','该诊疗已确认','error');		
		return;
	}
	if(status==2){
		$.messager.alert('提示','该诊疗已结束','error');		
		return;
	}
		$.ajax({
			url:'/index.php/index/cure/acography_confirm_api',
			type:"POST",
			data:{'id':id},
			success:function(data){
				if(data.status==1){
					$.messager.alert('提示','确认成功！','info',function(){
						location.reload();
					});		
					return;
				}
			}
		});
	}
	function pay(){
		var id = $('#id').val();
		window.location.href = '/index.php/index/cure/acography_pay?id='+id;
	}
	
	function refund(){
		var id = $('#id').val();
		$.messager.alert('提示','暂未开通','info');	
		//window.location.href = '/index.php/index/cure/acography_refund?id='+id;
	}
	var mustcolumnstyle = 'border:1px solid #1BBC9B;background-color:#eeeeee;';
	var backgroundcolor = 'background-color:#eeeeee;';
    $(function () {
		var id = $('#id').val();
		var status = $('#status').val();
		if(status<1){
			$('#rangegrid').datagrid({
				methord: 'get',
				url: '/index.php/index/cure/acography_medicine_api?id='+id,
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
						{ field: 'medicine_id', title: '药品id', hidden: 'true' },
						{ field: 'id', title: '记录id', hidden: 'true' },
						{
							field: 'bar_code', title: '条形码', editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
								return mustcolumnstyle;
							}
						},
						{ field: 'sp_name', title: '药品分类', editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
								return mustcolumnstyle;
							}
						},
						{ field: 'code', title: '药品编码', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'uname', title: '通用名称', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'name', title: '商品名', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'specification', title: '规格', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'dwgl_name', title: '单位', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'jxgl_name', title: '剂型', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'number', title: '数量', editor: { type: 'text' }, fit: true },

				]],
				fit: false,
				pagination: false,
				rownumbers: true,
				fitColumns: true,
				singleSelect: false
			}).datagrid('enableCellEditing');
		}else{
			$('#rangegrid').datagrid({
				methord: 'get',
				url: '/index.php/index/cure/acography_medicine_api?id='+id,
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
						{ field: 'medicine_id', title: '药品id', hidden: 'true' },
						{ field: 'id', title: '记录id', hidden: 'true' },
						{
							field: 'bar_code', title: '条形码', editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
								return mustcolumnstyle;
							}
						},
						{ field: 'sp_name', title: '药品分类', editor: { type: 'text' }, fit: true , styler: function (value, row, index) {
								return mustcolumnstyle;
							}
						},
						{ field: 'code', title: '药品编码', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'uname', title: '通用名称', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'name', title: '商品名', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'specification', title: '规格', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'dwgl_name', title: '单位', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'jxgl_name', title: '剂型', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							} 
						},
						{ field: 'number', title: '数量', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
							}
						},

				]],
				fit: false,
				pagination: false,
				rownumbers: true,
				fitColumns: true,
				singleSelect: false
			}).datagrid('enableCellEditing');
		}
		
		
        $("#OperateScope").click(function () {
            $('#dlg').dialog({
                title: "选择",
                height: 450,
                width: 500,
                top: 100,
                left: 200,
                href: "SelScope",
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var rows = $('#jyfwgrid').datagrid('getSelections');
                        if (rows != null) {
                            $('#OperateScope').val('');
                            var scopestr = ''
                            $.each(rows, function (i, item) {
                                scopestr += rows[i].Name + ",";
                            });
                            $('#OperateScope').val(scopestr);
                            $('#dlg').dialog('close');
                        }
                    }
                }, {
                    text: '取消',
                    handler: function () {
                        $('#dlg').dialog('close');
                    }
                }]
            });
        });
        var IsSubmit = true;
        $("#a_save").click(function () {
			var status = $('#status').val();
			if(status==1){
				$.messager.alert('提示','该诊疗已确认','error');		
				return;
			}
			if(status==2){
				$.messager.alert('提示','该诊疗已结束','error');		
				return;
			}
			var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;
			//电话
			var phone = $.trim($('#phone').val());
			if (phone && !phoneReg.test(phone)) {
			alert('请输入有效的手机号码！');
				return false;
			}
            for (var i = 0; i < $("#rangegrid").datagrid("getRows").length; i++) {
                $('#rangegrid').datagrid('endEdit', i);
            }
            //if ($("#rangegrid").datagrid("getRows").length > 0)
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
            //$("#Province").val($("#ProvinceID").find("option:selected").text());
            //$("#City").val($("#CityID").find("option:selected").text());
            //$("#County").val($("#CountyID").find("option:selected").text());
            //if (IsSubmit) {
                //IsSubmit = false;
                $.post("/index.php/index/cure/acography_edit_api", $("form").serialize(),
                            function (s) {
                                //IsSubmit = true;
                                if (s.status == 1) {
                                    $.messager.alert('提示','修改成功！','info');
									$('#rangegrid').datagrid('reload');
									$('#insertinfo').val('');
									$('#updateinfo').val('');
									$('#deleteinfo').val('');
									return false;
                                }
                                else {
                                    showMsg('操作提示', s.Msg, 'error');
                                }
                            }, "json");
            //}
        });
        $("#btnAddRow").click(function () {
            $("#rangegrid").datagrid('appendRow', {
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
                        if (param.field == "sp_name" || param.field == "bar_code") {
                            $(ed.target).prop('readonly', true);
                            $(ed.target).bind('dblclick', function (e) {
                                $('#rangegrid').datagrid('endEdit', editIndex);
                                $('#win_medicine').dialog({
                                    title: "选择",
                                    height: 450,
                                    width: 700,
                                    top: 100,
                                    left: 200,
                                    href: "win_medicine",
                                    buttons: [{
                                        text: '确定',
                                        iconCls: 'icon-ok',
                                        handler: function () {
                                            var rows = $('#selectGrid').datagrid('getSelections');
											var allsalerows = $('#rangegrid').datagrid('getRows');
											console.log(rows);
                                            if (rows != null) {
												
												var cindex = param.index;
                                                $.each(rows, function (idx, obj) {

                                                    var r = rows[idx];
                                                    isrow = false;
                                                    $.each(allsalerows, function (rindex, robj) {
                                                        if (allsalerows[rindex].medicine_id && r.id == allsalerows[rindex].medicine_id) {
															if(param.field == "bar_code"){
																$('#rangegrid').datagrid('updateRow', {
																	index: rindex,
																	row: { number:allsalerows[rindex].number+1 }
																});
															}
                                                            isrow = true;
                                                            return false;
                                                        }
                                                    });
                                                    if (!isrow) {
														$('#rangegrid').datagrid('insertRow', {
															index: editIndex + 1,
															row: { medicine_id: rows[idx].id,bar_code: rows[idx].bar_code,sp_name: rows[idx].classify_id, code: rows[idx].code, uname: rows[idx].uname,name: rows[idx].name,specification: rows[idx].specification,dwgl_name: rows[idx].unit_id,jxgl_name: rows[idx].dosage_id,number:1 }
														});
														$('#rangegrid').datagrid('deleteRow', editIndex);
                                                    }
                                                    else {
														if(param.field == "sp_name"){
															$.messager.alert("提示", "当前列表中已存在该商品类型，不能重复添加！");
															return false;
														}
                                                    }

                                                });
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