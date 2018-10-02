<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\purback\backAdd.html";i:1537497716;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1537495602;}*/ ?>
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


</head>
<body>
<ul class="breadcrumb">
    首页<span class="divider">/</span>业务管理<span class="divider">/</span>退货管理
</ul>
<div class="title_right"><strong>退货管理</strong></div>
<div style="position:absolute; right:15px;top:4px;">
    <span id="span_current_userinfo">
         Hi,
                    <?php if($uinfo['role_short'] =='cj'): ?>
                         <?php echo $uinfo['user_name']; ?>（<?php echo $uinfo['admin_name']; ?>）
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="#" onclick="Save()">保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="#" onclick="Confirm()">确认出库</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/purback/index'); ?>">列表</a>

                <a class="easyui-linkbutton" data-options="iconCls:'icon-printer'">打印</a>
            </div>


        </div>


        <div class="form_table2" style="margin-top:0">
            <form style="width:100%;height:100%;">

                <div class="row">
                    <ul>
                        <li>
                            <label for="name">供货商名称</label>
                            <input type="text" placeholder="请双击选择"  name="supplier_name" id="SupplyName" readonly="readonly" style="border:1px solid #1BBC9B" /></li>
                        <li><label>供货商编码</label><input type="text" readonly="readonly" name="supplier_code" id="supplier_code" /></li>
                        <li><label>单据编号</label><input type="text" readonly="readonly" name="backpur_number" id="backpur_number" value="<?php echo $sn; ?>"/></li>
                        <li><label>创建日期</label><input type="text" data-options="editable:false"  name="create_time" class="easyui-datebox"  style="width:170px"/></li>
                        <li><label>确认日期</label><input type="text"  data-options="editable:false" name="confirm_time" class="easyui-datebox"  style="width:170px"/></li>
                        <li><label>操作人</label><input type="text" readonly="readonly" name="operater" value="张三峰" /></li>
                    </ul>
                    <ul>
                        <li><label>采购总金额</label><input type="text" readonly="readonly" id="OrderAccount" name="purtotal"/></li>
                        <li><label>应付总金额</label><input type="text" readonly="readonly" id="PayAccount" name="puryftotal"/></li>
                        <li><label>原到货日期</label><input type="text" data-options="editable:false" class="easyui-datebox"  name="daohuotime" style="width:170px" /></li>
                        <li><label>原随货同行单号</label><input type="text" name="SHDH"/></li>
                        <li><label>联系人</label><input type="text" name="contacts_person" id="contacts_person" /></li>
                        <li><label>联系电话</label><input type="text" name="contacts_phone" id="contacts_phone" /></li>
                    </ul>

                    <ul>
                        <li><label>备注</label><textarea name="info"></textarea></li>
                    </ul>
                </div>
        </div>

        <div id="divpadding" class="margin-top-5" style="clear:both;margin:0 10px">

            <div id="rangegrid" class="easyui-grid" style="min-height:300px; width:100%;"></div>

            <div class="margin-top-5">
                <a id="btnAddRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-add'" group="">添加行</a>
                <a id="btnDelRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-delete'" group="">删除行</a>
            </div>
</div>
<div id="dlg" class="easyui-dialog" style="padding: 10px; width:500px; height:500px;"></div>

<input type="hidden" id="SupplyID" name="SupplyID" value="00000000-0000-0000-0000-000000000000" />
<input type="hidden" id="State" name="State" />
<input type="hidden" id="detail" name="detail" />
<input type="hidden" name="insertinfo" id="insertinfo">
<input type="hidden" name="deleteinfo" id="deleteinfo">
<input type="hidden" name="updateinfo" id="updateinfo">
</form>
</body>
<script>

    $.fn.datebox.defaults.formatter = function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
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

    var url = "<?php echo url('index/purchase/SelSupply'); ?>";
    $("#SupplyName").dblclick(function () {

        $('#dlg').dialog({
            title: 'My Dialog',
            width: 500,
            height: 500,
            closed: false,
            cache: false,
            href: url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#SupplyID').val(rows[0].id);
                        $('#SupplyName').val(rows[0].supplier_name);
                        $('#supplier_code').val(rows[0].supplier_code);
                        $('#contacts_person').val(rows[0].contacts_person);
                        $('#contacts_phone').val(rows[0].contacts_phone);
                        //$('#Province').val(rows[0].SF);
                        // $('#City').val(rows[0].DQ);
                        // $('#County').val(rows[0].XS);
                        // $('#SupplySalePerson').val(rows[0].XM);
                        // $('#ContactTel').val(rows[0].LXDH);
                        $('#dlg').dialog('close');
                    }
                }
            }, {
                text: '取消',
                handler: function () {
                    $('#dlg').dialog('close');
                }
            }],
            //放到dialog事件中
            onLoad: function () {
                   //弹出窗里的ID
                   $('#dwgrid').datagrid({
                       onDblClickRow: function (index, rows) {

                           $('#SupplyID').val(rows.id);
                           $('#SupplyName').val(rows.supplier_name);
                           $('#supplier_code').val(rows.supplier_code);
                           $('#contacts_person').val(rows.contacts_person);
                           $('#contacts_phone').val(rows.contacts_phone);

                           $('#dlg').dialog('close');
                       }
                   });
               }
        });
    });

    var selitem = [
        { selvalue: '1', seltext: '合格' },
        { selvalue: '2', seltext: '可疑' },
        { selvalue: '0', seltext: '不合格' }
    ];
    var mustcolumnstyle = 'background-color:#eee;color:#000;';

    $(function(){
        $('#dlg').dialog('close');

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
                { field: 'code', title: '商品编码', fit: true },
                {
                    field: 'uname', title: '通用名称', editor: { type: 'text', options: { editable: false } }, fit: true, styler:
                        function (value, row, index) {
                            return mustcolumnstyle;
                        }
                },
                { field: 'name', title: '商品名', fit: true },
                { field: 'specification', title: '规格', fit: true },

                { field: 'dwgl_name', title: '单位', fit: true },
                {
                    field: 'pnumber', title: '退货数量', editor: {
                        type: 'numberbox',
                        options: { precision: 2 }
                    }, fit: true
                },
                {
                    field: 'price', title: '退货单价', editor: {
                        type: 'numberbox',
                        options: { precision: 2 }
                    }, fit: true
                },


                { field: 'MoneyAccount', title: '退货金额', fit: true },


                {
                    field: 'innumber', title: '出库数量', editor: {
                        type: 'numberbox',
                        options: { precision: 2 }
                    }, fit: true
                },

                { field: 'MoneyAccount2', title: '应退金额', fit: true },


                { field: 'approval_number', title: '批准文号', fit: true },
                { field: 'yxq_month', title: '有效期(月)', fit: true },


                { field: 'zl_status', title: '质量状况', fit: true, editor: {
                        type: 'combobox', options: {
                            valueField: 'selvalue',
                            textField: 'seltext',
                            data: selitem,
                            required: true,
                            editable: false
                        }
                    }, formatter: function (value, row, index) {
                        switch (value) {
                            case "0": return "不合格"; break;
                            case "1": return "合格"; break;
                            case "2": return "可疑"; break;
                        }
                        return "";
                    }},





                { field: 'LastPrice', title: '上批退货单价', fit: true },
                { field: 'Piece', title: '退货件数', fit: true },
                { field: 'box_number', title: '整箱数量', fit: true }
            ]],
            fit: false,
            pagination: false,
            rownumbers: true,
            fitColumns: true,
            singleSelect: false
        }).datagrid('enableCellEditing');

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
    function DetailSum() {
        var rows = $('#rangegrid').datagrid('getRows');
        var wstotal = 0;
        for (var i = 0; i < rows.length; i++) {
            if (rows[i]['MoneyAccount'])
                wstotal += parseFloat(rows[i]['MoneyAccount']);
            else
                wstotal += 0;
        }
        wstotal = toDecimalT(wstotal);
        $('#OrderAccount').val(wstotal);
    }

    function DetailSum2() {
        var rows = $('#rangegrid').datagrid('getRows');
        var wstotal = 0;
        for (var i = 0; i < rows.length; i++) {
            if (rows[i]['MoneyAccount2'])
                wstotal += parseFloat(rows[i]['MoneyAccount2']);
            else
                wstotal += 0;
        }
        wstotal = toDecimalT(wstotal);
        $('#PayAccount').val(wstotal);
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


    $.extend(
        $.fn.datagrid.methods,
        {
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
                    editIndex = param.index;

                    $(this).datagrid('beginEdit', param.index);
                    var ed = $(this).datagrid('getEditor', param);

                    if (ed) {

                        if ($("#State").val() == "0" || $("#State").val() == "") {

                            if (param.field == "uname") {

                                $(ed.target).prop('readonly', true);
                                $(ed.target).bind('dblclick', function (e) {
                                    if ($("#SupplyID").val() == "00000000-0000-0000-0000-000000000000") {
                                        $.messager.alert("提示", "请选择供货单位！");
                                        return;
                                    }

                                    $('#dlg').dialog({
                                        title: "选择",
                                        height: 500,
                                        width: 500,
                                        top: 100,
                                        left: 200,
                                        href: "<?php echo url('index/purchase/SelPro'); ?>",
                                        buttons: [{
                                            text: '确定',
                                            iconCls: 'icon-ok',
                                            handler: function () {
                                                var rows = $('#dwgrid').datagrid('getSelections');
                                                if (rows != null) {
                                                    for (var i = 0; i < rows.length; i++) {
                                                        $('#rangegrid').datagrid('insertRow', {
                                                            index: param.index + 1,
                                                            row: {
                                                                id: rows[i].id,
                                                                code: rows[i].code,
                                                                uname: rows[i].uname,
                                                                name: rows[i].name,
                                                                specification: rows[i].specification,
                                                                dwgl_name: rows[i].dwgl_name,
                                                                pnumber: 0.00,
                                                                price:0.00,
                                                                MoneyAccount:0.00,
                                                                innumber:0.00,
                                                                MoneyAccount2:0.00,
                                                                approval_number: rows[i].approval_number,
                                                                yxq_month: rows[i].yxq_month,
                                                                zl_status: '1',
                                                                LastPrice: rows[i].LastPrice,
                                                                box_number:rows[i].box_number

                                                            }
                                                        });
                                                    }
                                                    editIndex = param.index;
                                                    $('#rangegrid').datagrid('deleteRow', param.index);
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
                            }
                        }

                        $('.datagrid-editable .textbox,.datagrid-editable .datagrid-editable-input,.datagrid-editable .textbox-text').blur(function () {

                            if (param.field == "price" || param.field == "pnumber" || param.field == "innumber") {
                                if (param.field == "pnumber") {
                                    var pcount = 0;

                                    var pmoney = 0;
                                    pcount = $(ed.target).val();

                                    pmoney = $("#rangegrid").datagrid('getRows')[param.index]['price'];
                                    //商品数量改变时商品单价相关计算
                                    var pspmoney = parseFloat(pcount ? pcount : '0') * parseFloat(pmoney ? pmoney : '0');
                                    $("#rangegrid").datagrid('getRows')[param.index]['MoneyAccount'] = parseFloat(pspmoney.toFixed(2));

                                    DetailSum();

                                }

                                if (param.field == "price") {
                                    var fcount = 0;
                                    var fmoney = 0;
                                    var fcount2 = 0;
                                    fmoney = $(ed.target).val();
                                    fcount = $("#rangegrid").datagrid('getRows')[param.index]['pnumber'];
                                    fcount2 = $("#rangegrid").datagrid('getRows')[param.index]['innumber'];
                                    //商品数量改变时商品单价相关计算
                                    var fspmoney = parseInt(fcount ? fcount : '0') * parseFloat(fmoney ? fmoney : '0');
                                    $("#rangegrid").datagrid('getRows')[param.index]['MoneyAccount'] = fspmoney.toFixed(2);

                                    var fspmoney2 = parseInt(fcount2 ? fcount2 : '0') * parseFloat(fmoney ? fmoney : '0');
                                    $("#rangegrid").datagrid('getRows')[param.index]['MoneyAccount2'] = fspmoney2.toFixed(2);

                                    DetailSum();
                                    DetailSum2();

                                }


                                if (param.field == "innumber") {

                                    var icount = 0;
                                    var imoney = 0;
                                    imoney = $("#rangegrid").datagrid('getRows')[param.index]['price'];
                                    icount = $("#rangegrid").datagrid('getRows')[param.index]['pnumber'];
                                    var innumber = $(ed.target).val();
                                    var row = $('#rangegrid').datagrid('getData').rows[editIndex];

                                    var RKSL = $("#rangegrid").datagrid('getEditor', { index: param.index, field: 'innumber' });
                                    var RKSLValue = parseFloat(($(RKSL.target).numberbox('getValue') != '' ? $(RKSL.target).numberbox('getValue') : 0)).toFixed(2);

                                    if(parseInt(RKSLValue) > parseInt(icount)){
                                        //debugger
                                        $.messager.alert('退货出库提示','出库数量不得大于退货数量','warning');
                                        $(RKSL.target).numberbox('setValue', row.pnumber);

                                    }
                                    var ispmoney = parseFloat(parseFloat(innumber ? innumber : '0') * parseFloat(imoney ? imoney : '0')).toFixed(2);


                                    $("#rangegrid").datagrid('getRows')[param.index]['MoneyAccount2'] = ispmoney;




                                    var box_number = $("#rangegrid").datagrid('getRows')[param.index]['box_number'];
                                    var js = parseFloat(innumber ? innumber : '0') / parseFloat(box_number ? box_number : '1');
                                    $("#rangegrid").datagrid('getRows')[param.index]['Piece'] = js.toFixed(3);

                                    DetailSum2();
                                }


                            }
                        });

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
                    }
                });
            }, addEditor: function (jq, param) {
                if (param instanceof Array) {
                    $.each(param, function (index, item) {
                        var e = $(jq).datagrid('getColumnOption', item.field);
                        e.editor = item.editor;
                    });
                } else {
                    var e = $(jq).datagrid('getColumnOption', param.field);
                    e.editor = param.editor;
                }
            },
            removeEditor: function (jq, param) {
                if (param instanceof Array) {
                    $.each(param, function (index, item) {
                        var e = $(jq).datagrid('getColumnOption', item);
                        e.editor = {};
                    });
                } else {
                    var e = $(jq).datagrid('getColumnOption', param);
                    e.editor = {};
                }
            }
        });

    //提交数据
    var IsSubmit = true;
    function Save() {
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


        DetailSum();
        DetailSum2();
        if (IsSubmit) {
         IsSubmit = false;
        $.post("<?php echo url('index/purback/backInsert'); ?>", $("form").serialize(),
            function (result) {
                var results = eval('('+result+')');
                IsSubmit = true;
                if (results.code == 1) {
                    $.messager.alert("采购操作提示", results.msg, "info", function () {
                        //window.location.href = "<?php echo url('index/purchase/index'); ?>";
                        window.location.href = "<?php echo url('index/purback/backEdit'); ?>?pid="+results.pid;
                    });
                }
                else {
                    $.messager.alert('采购操作提示',results.msg,'warning');
                }
            }, "json");
        }
    }

    var IsConfirm = true;
    function Confirm() {
        if (IsConfirm) {
         IsConfirm = false;
        $.post("<?php echo url('index/purback/runConfirm'); ?>", {backpur_number:$("#backpur_number").val()},
            function (result) {
                var results = eval('(' + result + ')');
                 IsConfirm = true;
                if (results.code == 1) {
                    $.messager.alert("退货操作提示", results.msg, "info", function () {
                        window.location.href = "<?php echo url('index/purback/index'); ?>";
                    });
                }
                else {
                    $.messager.alert('退货操作提示', results.msg, 'warning');
                }
            }, "json");
        }
    }


</script>


</html>