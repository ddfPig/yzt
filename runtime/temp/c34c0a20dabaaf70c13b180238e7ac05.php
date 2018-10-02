<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\index\index.html";i:1537966514;s:69:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\common\nav.html";i:1537366832;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>医诊通V1.0.0</title>
<link rel="stylesheet" href="/public/static/css/bootstrap.css" />
<link rel="stylesheet" href="/public/static/css/css.css" />
<link href="/public/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/public/static/images/favicon.ico" />
<script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="/public/static/easyui/themes/icon.css">
<script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
    <script type="text/javascript" src="/public/static/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script>

        $(window).ready(function () {
            ResetIframe();
        });
        $(window).resize(function () {
            ResetIframe();
        });
        $(document).ready(function () {
            ResetIframe();
        });
        function ResetIframe() {
            var top_btn = 40 + 36;
            if ($('body > div.margin-bottom-5') != undefined && $('body > div.margin-bottom-5') != null && $('body > div.margin-bottom-5').height() != undefined && $('body > div.margin-bottom-5').height() != null) {
                top_btn = 40 + 36 + parseFloat($('body > div.margin-bottom-5').height()) + 5;
            }
            var page_height = $(window).height() - top_btn;
            if ($('body > div#div_data') != undefined && $('body > div#div_data') != null) {
                $('body > div#div_data').height(page_height);
            }
            $('body > div#div_data').css({ 'overflow': 'auto' });
            $('body > div#div_data > form').css({ 'margin': '0px' });

        }


    </script>
</head>
<body>
<div class="header">
    <div class="logo"> <img src="/public/static/images/logo2.png"/></div>
    <div class="header-right">
        <style>
            .ul {
                list-style-type: none;
            }

            .ul li {
                height: 40px;
                margin: 0 0;
                float: left;
                color: #fff;
            }

            .ul li a {
                display: block;
                line-height: 40px;
                cursor: pointer;
                text-align: center;
                color: #fff;
                font-size: 14px;
                font-family: '微软雅黑';
                text-decoration: none;
                padding: 0 10px;
            }

            .ul li:hover {
                text-decoration: none;
                background-color: #1BBC9B;
                color: #fff !important;
            }

            .ul li a:hover {
                text-decoration: none;
                color: #fff !important;
            }
        </style>
        <ul class="ul">
            <li><a href="<?php echo url('index/index/index'); ?>"><i class="fa fa-home fa-lg"></i> <span class="title">首页</span></a></li>

            <li style="margin-right:0">
                <a onclick="SignOut();" style="width:54px;">
                    <i class="fa fa-power-off fa-lg"></i>
                    <span class="title">退出</span></a>
            </li>
        </ul>
    </div>
</div>
<div id="middle">
    <div class="left">
        <script type="text/javascript">
            var myMenu;
            window.onload = function () {
                myMenu = new SDMenu("my_menu");
                myMenu.init();
            };
        </script>
        <a id="menuVersion" flag="1" style="cursor: pointer;"></a>
        <div id="my_menu" class="sdmenu">
            
        <!--一级菜单遍历开始-->

        <?php if(is_array($menus) || $menus instanceof \think\Collection || $menus instanceof \think\Paginator): if( count($menus)==0 ) : echo "" ;else: foreach($menus as $key=>$v): ?>
        <div sort="">
             <span sort="">
               <?php echo $v['title']; ?>
            </span>
        <?php if(!empty($v['_child'])): ?>

            <!--二级菜单遍历开始-->
            <?php if(is_array($v['_child']) || $v['_child'] instanceof \think\Collection || $v['_child'] instanceof \think\Paginator): if( count($v['_child'])==0 ) : echo "" ;else: foreach($v['_child'] as $key=>$vv): if(!empty($vv['_child'])): ?>

                    <a attr_id="sonMenu" href="<?php echo url($vv['name']); ?>" target="mainFrame" onclick="OnListeniframeDocumentReady(<?php echo $vv['id']; ?>);"> <?php echo $vv['title']; ?></a>
                    <!--三级菜单遍历开始-->
                        <?php if(is_array($vv['_child']) || $vv['_child'] instanceof \think\Collection || $vv['_child'] instanceof \think\Paginator): if( count($vv['_child'])==0 ) : echo "" ;else: foreach($vv['_child'] as $key=>$vvv): ?>
                        <a attr_id="sonMenu" href="<?php echo url($vvv['name']); ?>" target="mainFrame" onclick="OnListeniframeDocumentReady(<?php echo $vvv['id']; ?>);"><?php echo $vvv['title']; ?></a>

                        <?php endforeach; endif; else: echo "" ;endif; ?><!--三级菜单遍历结束-->

                <?php else: ?>

                    <a attr_id="sonMenu" href="<?php if($vv['id'] ==49): ?>javascript:void(0);<?php else: ?><?php echo url($vv['name']); endif; ?>" target="mainFrame"  <?php if($vv['id'] ==49): ?>onclick="SignOut();"<?php else: ?>onclick="OnListeniframeDocumentReady(<?php echo $vv['id']; ?>);"<?php endif; ?>> <?php echo $vv['title']; ?></a>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?><!--二级菜单遍历结束-->

        <?php else: ?>
            <a attr_id="sonMenu" href="<?php echo url($v['name']); ?>" target="mainFrame" onclick="OnListeniframeDocumentReady(<?php echo $v['id']; ?>);"><?php echo $v['title']; ?></a>
        <?php endif; ?>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?><!--一级菜单遍历结束-->

        </div>
    </div>
    <div class="Switch"></div>
    <script type="text/javascript">
        var myMenu;
        $(document).ready(function (e) {
            $(".Switch").click(function () {
                $(".left").toggle();
            });
            $('.sdmenu a').click(function () {
                var $this = this;
                $('.sdmenu a').each(function () {
                    $(this).attr("class", "");
                })
                $($this).attr("class", "current");
            });
        });
    </script>
    <div class="right">
        <div class="right_cont" style="margin:0px; padding:0px;overflow: hidden;height: 100%;">
            <iframe id="mainFrame" name="mainFrame" src="<?php echo url('index/main/updsystemintro'); ?>" frameborder="0" width="99%" height="98%" marginheight="0" marginwidth="0" scrolling="no" style="margin-left:0.5%; margin-top:0.7%;height: 100%;"></iframe>
        </div>
    </div>
</div>
<script>

    function SignOut(){
        $.messager.confirm('退出提示', '您确定退出系统吗?', function(r){
            if (r){
                window.location.href = "<?php echo url('index/login/logout'); ?>";
            }
        });
    }


    function OnListeniframeDocumentReady(id) {
        var iframe = document.getElementById("mainFrame");
        if (iframe.attachEvent) {
            iframe.attachEvent("onload", function () {
                OnLoadHelpInfo(id);
            });
        } else {
            iframe.onload = function () {
                OnLoadHelpInfo(id);
            };
        }
    }
    var AddDialogHelpInfo;
    function OnLoadHelpInfo(id) {
        var RoleID = '', MenuID = id;
        var iframedo = document.getElementById('mainFrame').contentDocument;
        $(iframedo).ready(function () {

        });
    }
</script>
</body>

<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b0b43df301cdbd90d19cc7148fe91a20";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</html>