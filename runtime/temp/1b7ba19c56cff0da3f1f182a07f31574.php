<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\cure\acography_pay.html";i:1538291991;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="a_save">确认结算收款</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/cure/acography'); ?>">列表</a>
					<a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
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
            <li><label>患者编号</label><input type="text" readonly="readonly" id="patient_number" value="<?php echo $data['patient_number']; ?>"/></li>
            <li><label>姓名</label><input type="text" name="name" readonly="readonly" value="<?php echo $data['name']; ?>" /></li>
            <li><label>性别</label><input type="text" readonly="readonly" value="<?php echo $data['sex']; ?>" /></li>
            <li><label>年龄</label><input type="text" readonly="readonly" value="<?php echo $data['age']; ?>" /></li>
        </ul>
    	<ul>
            <li><label>收款日期</label><input type="text" readonly="readonly" name="payee_date" value="<?php echo $date; ?>" /></li>
            <li><label>收款方式</label><select name="payee_type" id="payee_type" <?php if($data['status'] == '2'): ?> readonly="readonly" <?php endif; ?> onchange="payee_choose()">
			<option value=0>未选择</option>
			<?php if(is_array($payee) || $payee instanceof \think\Collection || $payee instanceof \think\Paginator): $i = 0; $__LIST__ = $payee;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			<option id="payee<?php echo $vo['id']; ?>" value=<?php echo $vo['id']; if($data['payee_type'] == $vo['id']): ?> selected <?php endif; ?>><?php echo $vo['name']; ?></option>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			<font id="balance_f"></font>
			<input type="hidden" id="balance">
			</li>
            <li><label>应收总金额</label><input type="text" readonly="readonly" name="receive_able" value="<?php echo $data['receive_able']; ?>" /></li>
            <li><label>实收总金额</label><input type="text" name="receive_real" id="receive_real" value="<?php echo $data['receive_real']; ?>"  <?php if($data['status'] == '2'): ?> readonly="readonly" <?php endif; ?> /></li>
        </ul>
    	<ul>
            <li><label>诊疗科室</label><input type="text" readonly="readonly" value="<?php echo $data['department_id']; ?>" /></li>
            <li><label>诊疗人</label><input type="text" readonly="readonly" value="<?php echo $data['confirm_name']; ?>" /></li>
            <li><label>诊疗日期</label><input type="text" readonly="readonly" value="<?php echo $data['confirm_date']; ?>" /></li>
            <li><label>诊疗记录编号</label><input type="text" readonly="readonly" value="<?php echo $data['number']; ?>" /></li>
        </ul>
        
<input type="hidden" id="details" name="details" />
    </form>
    </div>
</div>

              
     
<div id="divpadding" class="margin-top-5" style="clear:both;margin:0 10px">
			<div region="center" style="width: 100%;" class="margin-top-5">
                <div id="rangegrid" class="easyui-grid" style="min-height:150px;"></div>
            </div>


   </div>

</div>

<script>

	function payee_choose(){
		$('#balance').html('');
		var patient_number = $('#patient_number').val();
		var payee_type = $('#payee_type').val();
		var payee = $('#payee'+payee_type).html();
		
		if(payee == '余额'){
			$.ajax({
				url:'/index.php/index/cure/acography_card_balance',
				type:"POST",
				data:{'id':patient_number},
				success:function(data){
					if(data.status==1){
						$('#balance_f').html('　余额：'+data.balance+'元');
						$('#balance').val(data.balance);
					}else{
						$.messager.alert('提示',data.message,'warning'); return;
					}
				}
			});
		}
		
	}

	var backgroundcolor = 'background-color:#eeeeee;';
    $(function () {
		var id = $('#id').val();
		var status = $('#status').val();
		if(status!=2){
			$('#rangegrid').datagrid({
				methord: 'get',
				url: '/index.php/index/cure/acography_pay_search?id='+id,
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
						{ field: 'medicine_id', title: '记录药品id', hidden: 'true' },
						{ field: 'id', title: '记录id', hidden: 'true' },
						{ field: 'sp_name', title: '药品分类', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
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
						{ field: 'price', title: '收款单价', editor: { type: 'text' }, fit: true },
						{ field: 'total', title: '金额', fit: true, styler: function (value, row, index) {
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
		}else{
			$('#rangegrid').datagrid({
				methord: 'get',
				url: '/index.php/index/cure/acography_pay_search?id='+id,
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
						{ field: 'medicine_id', title: '记录药品id', hidden: 'true' },
						{ field: 'id', title: '记录id', hidden: 'true' },
						{ field: 'sp_name', title: '药品分类', fit: true , styler: function (value, row, index) {
								return backgroundcolor;
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
						{ field: 'number', title: '数量', fit: true, styler: function (value, row, index) {
								return backgroundcolor;
							}
						},
						{ field: 'price', title: '收款单价', fit: true, styler: function (value, row, index) {
								return backgroundcolor;
							}
						},
						{ field: 'total', title: '金额', fit: true, styler: function (value, row, index) {
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
			if(status==2){
				$.messager.alert('提示','该诊疗已结束','error');		
				return;
			}
			
			var payee_type = $('#payee_type').val();
			var payee = $('#payee'+payee_type).html();
			
			if(payee == '余额'){
				var balance = $('#balance').val();
				var total = $('#receive_real').val();
				
				if(parseFloat(balance)<parseFloat(total)){
					$.messager.alert('提示','余额不足','warning');		
					return;
				}
			}
            for (var i = 0; i < $("#rangegrid").datagrid("getRows").length; i++) {
                $('#rangegrid').datagrid('endEdit', i);
            }
            if ($("#rangegrid").datagrid("getRows").length > 0)
                    $("#details").val(JSON.stringify($("#rangegrid").datagrid("getRows")));
            //$("#Province").val($("#ProvinceID").find("option:selected").text());
            //$("#City").val($("#CityID").find("option:selected").text());
            //$("#County").val($("#CountyID").find("option:selected").text());
            //if (IsSubmit) {
                //IsSubmit = false;
                $.post("/index.php/index/cure/acography_pay_api", $("form").serialize(),
                            function (s) {
                                //IsSubmit = true;
                                if (s.status == 1) {
                                    $.messager.alert('提示','修改成功！','info',function(){
										location.reload();
									});
									
									return false;
                                }
                                else {
                                    $.messager.alert('操作提示', s.message, 'error');return false;
                                }
                            }, "json");
            //}
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
	
	
    function DetailSum() {
        var rows = $('#rangegrid').datagrid('getRows');
        var wstotal = 0;
        for (var i = 0; i < rows.length; i++) {
            if (rows[i]['total'])
                wstotal += parseFloat(rows[i]['total']);
            else
                wstotal += 0;
        }
        wstotal = toDecimalT(wstotal);
        $('#receive_real').val(wstotal);
    }
	function toDecimalT(x) {
        var f = parseFloat(x);
        if (isNaN(f)) {
            return false;
        }
        var f = Math.round(x * 100) / 100;
        var s = f.toString();
        var rs = s.indexOf('.');
        if (rs < 0) {
            rs = s.length;
            s += '.';
        }
        while (s.length <= rs + 2) {
            s += '0';
        }
        return s;
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
					
						
                        $('.datagrid-editable .textbox,.datagrid-editable .datagrid-editable-input,.datagrid-editable .textbox-text').blur(function () {

                            if (param.field == "price" || param.field == "number") {
                                if(param.field == "price"){
                                    price = $(ed.target).val();
                                    number = $("#rangegrid").datagrid('getRows')[param.index]['number'];
									console.log(price);
									console.log(number);
                                    //商品数量改变时商品单价相关计算
                                    //var pspmoney = parseFloat(price ? price : '0') * parseFloat(number ? number : '0');
                                    //$("#rangegrid").datagrid('getRows')[param.index]['total'] = parseFloat(pspmoney.toFixed(2));
									
									var ispmoney = parseFloat(parseFloat(price ? price : '0') * parseFloat(number ? number : '0')).toFixed(2);
									$("#rangegrid").datagrid('getRows')[param.index]['total'] = ispmoney;
									
                                    DetailSum();

                                }
								if(param.field == "number"){
                                    price = $("#rangegrid").datagrid('getRows')[param.index]['price'];
                                    number = $(ed.target).val();
									console.log(price);
									console.log(number);
                                    //商品数量改变时商品单价相关计算
                                    var pspmoney = parseFloat(price ? price : '0') * parseFloat(number ? number : '0');
                                    $("#rangegrid").datagrid('getRows')[param.index]['total'] = parseFloat(pspmoney.toFixed(2));

									var ispmoney = parseFloat(parseFloat(price ? price : '0') * parseFloat(number ? number : '0')).toFixed(2);
									$("#rangegrid").datagrid('getRows')[param.index]['total'] = ispmoney;
									
                                    DetailSum();

                                }

                            }
                        });
					
                        editIndex = param.index;
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