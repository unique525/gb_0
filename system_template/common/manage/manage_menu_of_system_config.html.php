<script type="text/javascript">

    $(function () {

        var btnManageUser = $("#btn_manage_user");
        btnManageUser.css("cursor","pointer");
        btnManageUser.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "后台帐号";
            window.G_TabUrl = '/default.php?secu=manage&mod=manage_user&m=list';
            addTab();
        });


        var btnManageUserGroup = $("#btn_manage_user_group");
        btnManageUserGroup.css("cursor","pointer");
        btnManageUserGroup.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "管理分组";
            window.G_TabUrl = '/default.php?secu=manage&mod=manage_user_group&m=list';
            addTab();
        });
    });



</script>
<div id="div_system_config">
    <div class="line"><div>系统参数</div></div>
    <div class="line"><div id="btn_manage_user">后台帐号</div></div>
    <div class="line"><div id="btn_manage_user_group">管理分组</div></div>
    <div class="line"><div>日志管理</div></div>
</div>