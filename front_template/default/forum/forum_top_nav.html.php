<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
<link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
<script>
$(function () {
    //论坛用户登录
    $("#btn_user_login_for_forum").click(function () {
        var top = document.body.clientHeight/2-500;
        var left = ($(document.body).width() - $("#dialog_user_login_for_forum_box").width())/2-50;
        var url = '/default.php?mod=user&a=login&temp=forum';
        $("#dialog_user_login_for_forum_frame").attr("src", url);
        $("#dialog_user_login_for_forum_box").dialog({
            hide: true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen: true,
           // position: [left, top],
            height: 300,
            width: 300,
            modal: true, //蒙层（弹出会影响页面大小）
            title: '论坛用户登录',
            overlay: {opacity: 0.5, background: "black", overflow: 'auto'}
        });
    });

    //论坛用户注册
    $("#btn_user_register_for_forum").click(function () {
        var top = document.body.clientHeight/2-500;
        var left = ($(document.body).width() - $("#dialog_user_register_for_forum_box").width())/2-50;
        var url = '/default.php?mod=user&a=register&temp=forum';
        $("#dialog_user_register_for_forum_frame").attr("src", url);
        $("#dialog_user_register_for_forum_box").dialog({
            hide: true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen: true,
            //position: [left, top],
            height: 370,
            width: 550,
            modal: true, //蒙层（弹出会影响页面大小）
            title: '论坛用户注册',
            overlay: {opacity: 0.5, background: "black", overflow: 'auto'}
        });
    });
});
</script>

<div id="top_nav">
    <div class="content">
        <div id="dialog_user_login_for_forum_box" title="修改密码" style="display: none;">
            <div id="dialog_user_login_for_forum_content" style="font-size: 14px;">
                <iframe id="dialog_user_login_for_forum_frame" src=""  style="border: 0; " width="100%" height="220px"></iframe>
            </div>
        </div>
        <div id="dialog_user_register_for_forum_box" title="会员注册" style="display: none;height:500px;">
            <div id="dialog_user_register_for_forum_content" style="font-size: 14px;">
                <iframe id="dialog_user_register_for_forum_frame" src=""  style="border: 0; " width="100%" height="320px"></iframe>
            </div>
        </div>
        <div class="left"><img src="/front_template/default/skins/common/logo.png" alt="logo"/></div>
        <div class="right">
            <div class="user_box">
                <span id="btn_user_login_for_forum" style="cursor:pointer">登录</span>
                / <span id="btn_user_register_for_forum" style="cursor:pointer">注册</span>
            </div>
        </div>
        <div class="spe"></div>
    </div>
</div>


