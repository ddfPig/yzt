<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\store\index.html";i:1538208403;s:74:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\userinfo.html";i:1538038290;}*/ ?>
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
                <select id="sp_type" name="sp_type" style="height:24px;line-height:24px; width:auto">
                    <option value="">全部分类</option>
                    <?php if(is_array($ypfl) || $ypfl instanceof \think\Collection || $ypfl instanceof \think\Paginator): $i = 0; $__LIST__ = $ypfl;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                          <option value="<?php echo $v['sp_id']; ?>"><?php echo $v['sp_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select id="ypzl" name="ypzl" style="height:24px;line-height:24px; width:auto">
                    <option value="">质量状态</option>
                    <option value="1">合格</option>
                    <option value="2">可疑</option>
                    <option value="0">不合格</option>
                </select>
                <select style="height:24px;line-height:24px; width:auto" id="jxq",name="jxq">
                    <option>全部效期</option>
                    <option>近效期180天</option>
                    <option>已过期</option>
                </select>
                <select style="height:24px;line-height:24px; width:auto" id="kcyj" name="kcyj">
                    <option value="">按库存预警</option>
                    <option value="-1">低于下限</option>
                    <option value="1">高于上限</option>
                </select>
                <input type="text" placeholder="请输入查询关键字" id="keyword" name="keyword" style="width:200px;" />
                <a class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="a_search">查询</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-excel'" id="toExcel"  onclick="Execl(this)">导出</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-refresh'" onclick="reloadurl()">刷新</a>

                <a class="easyui-linkbutton" data-options="iconCls:'icon-del'">核减销毁</a>

            </div>

            <div id="divpadding" class="margin-top-5" style="clear:both">
                <div id="rangegrid" style="min-height:500px; width:100%;">
            </div>
</div>
</body>
<script type="text/javascript">

    $(document).ready(function () {
        $("#jxq option").remove();
        $("#jxq").append(" <option value=''>全部效期</option>");
        $("#jxq").append(" <option value='-1'>已过期</option>");
        $.getJSON("<?php echo url('index/store/getIniDate'); ?>", {}, function (data) {
                $("#jxq").append(" <option value='" + data.cshsz_jxqts + "'>近效期" + data.cshsz_jxqts + "天</option>");
        });
    });

    //导出
    function Execl(obj) {
        $.messager.confirm('确认', '您确认要导出吗？', function (r) {
            var sp_type = $("#sp_type").val();
            var ypzl = $("#ypzl").val();
            var jxq = $("#jxq").val();
            var kcyj = $("#kcyj").val();
            var keyword = $("#keyword").val();
            if (r) {
                window.location.href = "<?php echo url('index/store/dataToExcel'); ?>?sp_type="+sp_type+"&ypzl="+ypzl+"&keyword="+keyword+"&jxq="+jxq+"&kcyj="+kcyj;
            }
        });
    }


    var grid;
    $(function () {
        grid = $('#rangegrid').datagrid({
            methord: 'get',
            url: "<?php echo url('index/store/lists'); ?>",
            sortName: 'ware_id',
            sortOrder: 'desc',
            idField: 'ware_id',
            pageSize: 30,
            showFooter: true,
            frozenColumns: [[
                { field: 'ck', checkbox: true }
            ]],
            columns: [[
                { field: 'code', title: '药品编码', fit: true },
                { field: 'uname', title: '通用名称', fit: true },
                { field: 'name', title: '商品名', fit: true },
                { field: 'specification', title: '规格', fit: true },
                { field: 'jxgl_name', title: '剂型', fit: true },
                { field: 'dwgl_name', title: '单位', fit: true },
                { field: 'manufacturer', title: '生产厂商', fit: true },
                { field: 'box_number', title: '整箱数量', fit: true },
                { field: 'innumber', title: '库存数量(盒)', fit: true },
                { field: 'Piece', title: '库存数量(件)', fit: true },
                { field: 'produce_place', title: '产地', fit: true },
                { field: 'pihao', title: '批号', fit: true },
                { field: 'yxqz', title: '有效期至', fit: true },
                { field: 'yxq_month', title: '商品效期(月)', fit: true },
                { field: 'price', title: '库存单价(元)', fit: true },
                { field: 'stock_max', title: '库存上线', fit: true },
                { field: 'stock_min', title: '库存下线', fit: true },
                { field: 'zl_status', title: '质量状况', fit: true },
            ]],
            fit: true,
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: false,
            // onDblClickCell: onDBClickCell,
            // onAfterEdit: function (rowIndex, rowData, changes) {
            //     //endEdit该方法触发此事件
            //     location.href = "<?php echo url('index/purchase/purEdit'); ?>?pid=" + rowData.purnumbers;
            // }
        });


        $("#a_search").click(search);

        //搜索
        function search() {
            grid.datagrid('options').page = 1;
            var queryParams = grid.datagrid('options').queryParams;
            queryParams.keyword = $("#keyword").val();
            grid.datagrid('options').queryParams = queryParams;
            grid.datagrid('reload');
        }
        //质量
        $("#ypzl").change(function () {
            $("#ypzl").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.ypzl = $("#ypzl").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });

        //药品分类
        $("#sp_type").change(function () {
            $("#sp_type").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.sp_type = $("#sp_type").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });

        //库存预警
        $("#kcyj").change(function () {
            $("#kcyj").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.kcyj = $("#kcyj").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });

        //近效期jxq
        $("#jxq").change(function () {
            $("#jxq").val($(this).find("option:selected").val());
            $("#rangegrid").datagrid('options').page = 1;
            var queryParams = $("#rangegrid").datagrid('options').queryParams;
            queryParams.jxq = $("#jxq").val();
            $("#rangegrid").datagrid('options').queryParams = queryParams;
            $("#rangegrid").datagrid('reload');
        });


    });

    //刷新
    function reloadurl()
    {
        $('#rangegrid').datagrid("uncheckAll");
        grid.datagrid('reload');
    }
</script>
</html>