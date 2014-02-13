<script type="text/javascript">
    $(function() {        
        $("#forumconfig").click(function() {
            G_TabTitle = $(this).html();
            G_TabUrl = '/default.php?secu=manage&mod=site_config&a=set&type=1&siteid=' + G_NowSiteId;
            addTab();
        });
        $("#forumlist").click(function() {
            G_TabTitle = $(this).html();
            G_TabUrl = '/default.php?secu=manage&mod=forum&a=list&siteid=' + G_NowSiteId;
            addTab();
        });
    });
</script>


<div id="div_forum_manage">
    <div id="forum_config" class="link_btn line">论坛参数设置</div>
    <div id="forum_list" class="link_btn line">版块管理</div>
</div>
