<script type="text/javascript">
    $(function() {        
        $("#forumconfig").click(function() {
            G_TabTitle = $(this).html();
            G_TabUrl = '/default.php?secu=manage&mod=siteconfig&a=set&type=1&siteid=' + G_NowSiteId;
            addTab();
        });
        $("#forumlist").click(function() {
            G_TabTitle = $(this).html();
            G_TabUrl = '/default.php?secu=manage&mod=forum&a=list&siteid=' + G_NowSiteId;
            addTab();
        });
    });
</script>


<div id="forummanage">
    <div id="forumconfig" class="linkbtn">论坛参数设置</div>
    <div id="forumlist" class="linkbtn">版块管理</div>
</div>
