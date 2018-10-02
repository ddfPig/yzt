<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\account\list_add.html";i:1538206739;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>医诊通V1.0.0</title>
    <link rel="stylesheet" href="/public//static/css/bootstrap.css" />
    <link href="/public//static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/public//static/css/css.css" />
    <link rel="stylesheet" type="text/css" href="/public//static/easyui/themes/gray/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public//static/easyui/themes/icon.css">
    <script type="text/javascript" src="/public//static/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/public//static/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public//static/js/sdmenu.js"></script>
    <script type="text/javascript" src="/public//static/js/laydate/laydate.js"></script>
    <link rel="shortcut icon" href="/public//static/images/favicon.ico" />
    <script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/static/js/kingeditor/themes/default/default.css">
    <link rel="stylesheet" type="text/css" href="/public/static/js/kingeditor/plugins/code/prettify.css">

    <script type="text/javascript" src="/public/static/js/kingeditor/kindeditor.js"></script>
    <script type="text/javascript" src="/public/static/js/kingeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="/public/static/js/kingeditor/plugins/code/prettify.js"></script>
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="saveUser()">保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-list'" href="<?php echo url('index/account/lists'); ?>">列表</a>
            </div>

            <div class="form_table2">
                <div class="row">
               <form action="" method="post" id="admin_user" >

                    <ul>
                        <li>
                            <label>用户类型</label>
                            <select name="shop_type">
                                <option value="">请选择</option>
                                <option value="1">单体</option>
                                <option value="2">连锁</option>
                            </select>
                        </li>

                        <li><label>用户编码</label><input type="text" name="shop_number" value="<?php echo $sn; ?>" readonly="readonly"  /></li>
                        <li><label for="name">用户全称</label><input type="text" placeholder="必填" name="shop_name" /></li>
                        <li><label style="line-height:16px">医疗机构执业<br />许可证</label><input type="text" name="licence" placeholder="必填" /></li>
                        <li><label id="ipt_area">经营场所</label><textarea name="shop_address" placeholder="必填"></textarea></li>
                    </ul>

                    <ul>
                        <li>
                            <label>省份</label>
                            <select name="province" class="form-control" id="province" onchange="is_province()">
                                <option value="0">请选择</option>
                                <?php foreach($province as $k=>$v): ?>
                                <option value="<?php echo $v['ID']; ?>"><?php echo $v['Name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <label>地区</label>
                            <select id="city" name="city" onchange="is_city()">
                                <option>请选择</option>
                            </select>
                        </li>
                        <li>
                            <label>县市</label>
                            <select id="area" name="town">
                                <option>请选择</option>
                            </select>
                        </li>
                        <li><label>负责人</label><input type="text" name="leader" placeholder="必填" /></li>
                        <li><label>联系人</label><input type="text" name="contactor"  placeholder="必填" /></li>
                        <li><label>联系电话</label><input type="text" name="mobile" placeholder="必填" /></li>
                    </ul>

                    <ul>
                        <li><label>登录用户名</label><input type="text" name="admin_user" placeholder="必填" /></li>
                        <li><label>登录密码</label><input type="text" name="admin_pass" placeholder="必填" /></li>



                        <li><label>开通时间</label><input type="text" name="open_time" class="easyui-datebox" editable="fasle" style="width:170px" placeholder="必填" /></li>
                        <li><label>营业期限</label><input  name="busniss_date"
                                                       type="text" placeholder="必填" value=""
                                                       id="busniss_date" class="easyui-datebox"   style="width:120px" editable="fasle"/>
                            <input type="checkbox" onclick="checkboxOnclick(this)" id="checkbox" name="checkbox" >长期
                        </li>

                        <li>
                            <label>客服</label>
                            <?php if($uinfo['role_short'] == 'zd'): ?>
                            <input type="text"   name="serviceName"  value="<?php echo $mykf['pkfgl_name']; ?>" readonly="readonly" style="border:1px solid #1BBC9B" />
                            <input type="hidden" name="serviceID" value="<?php echo $mykf['id']; ?>">
                            <?php else: ?>
                            <input type="text" placeholder="请双击选择"  name="serviceName" id="serviceName" readonly="readonly" style="border:1px solid #1BBC9B" />
                            <input type="hidden" name="serviceID" id="serviceID">
                            <?php endif; ?>
                        </li>
                        <li>
                            <label>终端经理</label>
                            <?php if($uinfo['role_short'] == 'zd'): ?>
                                <input type="text"   name="zdname"  readonly="readonly" value="<?php echo $uinfo['user_name']; ?>" style="border:1px solid #1BBC9B" />
                                <input type="hidden" name="zdID" id="zdID" value="<?php echo $uinfo['tid']; ?>">
                            <?php else: ?>

                            <input type="text" placeholder="请双击选择"  name="zdname" id="zdname" readonly="readonly" style="border:1px solid #1BBC9B" />
                            <input type="hidden" name="zdID" id="zdID" value="">

                            <?php endif; ?>
                        </li>
                    </ul>
                    <ul>
                        <li><label>开户户名</label><input type="text" value="" id="bank_name" name="bank_name"/></li>
                        <li><label>开户银行</label><input type="text"  value="" id="bank_type" name="bank_type"/></li>
                        <li><label>银行账号</label><input type="text"  value="" id="bank_num" name="bank_num"/></li>

                    </ul>

                    <ul>
                        <li><label>经营范围</label><textarea id="shop_scope" name="shop_scope"></textarea></li>
                    </ul>


                    <ul>
                        <li>
                            <label>备注</label>
                            <textarea name="mark"></textarea>
                        </li>
                    </ul>


                   <input type="hidden" name="roleID" id="roleID" value="5">
                   <!--<div id="divpadding" class="margin-top-5" style="clear: both; margin: 0 10px">-->

                           <!--<div id="rangegrid" class="easyui-grid" style="min-height: 150px;"></div>-->

                       <!--<div class="margin-top-5">-->
                           <!--<a id="btnAddRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-add'" group="">添加行</a>-->
                           <!--<a id="btnDelRow" class="easyui-linkbutton l-btn l-btn-small" data-options="iconCls:'icon-delete'" group="">删除行</a>-->
                       <!--</div>-->
                   <!--</div>-->
                   <input type="hidden" id="detail" name="detail" />
               </form>
            </div>


        </div>

</div>
<div id="dlg" class="easyui-dialog" style="padding: 10px; width:500px; height:500px;"></div>
<div id="win_medicine" class="easyui-dialog" title="选择" style="width: 600px; height: 450px; padding: 10px"></div>
</body>
<script>

    function is_province(){
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
                var Pro2 = "<option value='0'>请选择您所在城市</option>";
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
    function is_city(){
        var obj = document.getElementById('city');
        var index = obj.selectedIndex;
        var value = obj.options[index].value;
        $.ajax({
            url: "<?php echo url('index/home/setvalue'); ?>",
            type: "post",
            data: {provinceid:value},
            success: function (res) {
                $("#area").empty();
                var Pro2 = "<option value='0'>请选择您所在区/县</option>";
                $('#area').append(Pro2);
                var res=JSON.parse( res );
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
        $('#win_medicine').dialog('close');
        $('#dlg').dialog('close');
    });


    var mustcolumnstyle = 'border:1px solid #1BBC9B;background-color:#eeeeee;';
    var backgroundcolor = 'background-color:#eeeeee;';
    $(function(){

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
            url: '',
            fit:true,
            sortName: 'id',
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




    })

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
                                href: "<?php echo url('index/account/zzView'); ?>",
                                buttons: [{
                                    text: '确定',
                                    iconCls: 'icon-ok',
                                    handler: function () {
                                        var rows = $('#selectMedi').datagrid('getSelections');
                                        if (rows != null) {
                                            for (var i = 0; i < rows.length; i++) {
                                                $('#rangegrid').datagrid('insertRow', {
                                                    index: editIndex + 1,
                                                    row: {
                                                        certificate_type: rows[i].zzgl_name    ,
                                                        certificate_num :'',
                                                        organization  :"",
                                                        certificate_atime :"",
                                                        certificate_stime :"",
                                                        scope:"",
                                                        organization       :"",
                                                        certificate_identity    :"",
                                                        certificate_contacts:"",
                                                        imgurl:"",
                                                        certificate_test:"",
                                                    }
                                                });
                                            }
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


    function onDBClickCell(index, field) {

        var rows = $('#selectMedi').datagrid('getRows');
        var row = rows[index];
        var rangegrid_rows = $('#rangegrid').datagrid('getRows');
        var row_index = rangegrid_rows[editIndex];

        $('#rangegrid').datagrid('updateRow',{
            index: editIndex,
            row: {
                certificate_type: row.zzgl_name ,
                certificate_num: row.zzgl_id ,
                organization  :"",
                certificate_atime :"",
                certificate_stime :"",
                scope:"",
                organization       :"",
                certificate_identity    :"",
                certificate_contacts:"",
                imgurl:"",
                certificate_test:"",
            }
        });
        $('#rangegrid').datagrid('endEdit', editIndex);
        editIndex = undefined;

        $('#win_medicine').dialog('close');


    }




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


    var Servicer_url = "<?php echo url('index/account/getServicer'); ?>";
    $("#serviceName").dblclick(function () {

        $('#dlg').dialog({
            title: '请选择客服',
            width: 620,
            height: 550,
            closed: false,
            cache: false,
            href: Servicer_url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#serviceID').val(rows[0].id);
                        $('#serviceName').val(rows[0].pkfgl_name);
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

                        $('#serviceID').val(rows.id);
                        $('#serviceName').val(rows.pkfgl_name);

                        $('#dlg').dialog('close');
                    }
                });
            }
        });
    });

    var zd_url = "<?php echo url('index/account/zdmanage'); ?>";
    $("#zdname").dblclick(function () {

        $('#dlg').dialog({
            title: '请选择终端经理',
            width: 620,
            height: 550,
            closed: false,
            cache: false,
            href: zd_url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#zdID').val(rows[0].id);
                        $('#zdname').val(rows[0].name);
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

                        $('#zdID').val(rows.id);
                        $('#zdname').val(rows.name);

                        $('#dlg').dialog('close');
                    }
                });
            }
        });
    });


    var role_url = "<?php echo url('index/account/getRole'); ?>";
    $("#roleName").dblclick(function () {

        $('#dlg').dialog({
            title: '请选择角色',
            width: 620,
            height: 550,
            closed: false,
            cache: false,
            href: role_url,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var rows = $('#dwgrid').datagrid('getSelections');
                    console.info(rows);
                    if (rows != null && rows.length > 0) {
                        $('#roleID').val(rows[0].id);
                        $('#roleName').val(rows[0].title);
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
                        $('#roleID').val(rows.id);
                        $('#roleName').val(rows.title);
                        $('#dlg').dialog('close');
                    }
                });
            }
        });
    });

    //提交数据
   // var IsSubmit = true;
    function saveUser() {
        // for (var i = 0; i < $("#rangegrid").datagrid("getRows").length; i++) {
        //     $('#rangegrid').datagrid('endEdit', i);
        // }
        // if ($("#rangegrid").datagrid("getRows").length > 0)
        //     $("#detail").val(JSON.stringify($("#rangegrid").datagrid("getRows")));

        //if (IsSubmit) {
            //IsSubmit = false;
            $.post("<?php echo url('index/account/listRunInsert'); ?>", $("form").serialize(),
                function (result) {
                    var results = eval('('+result+')');
                    //IsSubmit = true;
                    if (results.code == 1) {
                        $.messager.alert("用户添加", results.msg, "info", function () {
                            window.location.href = "<?php echo url('index/account/lists'); ?>?pid="+results.pid;

                        });
                    }
                    else {
                        $.messager.alert('用户添加',results.msg,'warning');
                    }
                }, "json");
        //}
    }

</script>
</html>