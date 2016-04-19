<script type="text/javascript">
    $(function() {
        $("#league_config").click(function() {
            if(window.G_NowSiteId>0){
                window.G_TabTitle = $(this).html();
                window.G_TabUrl = '/default.php?secu=manage&mod=league&m=list&site_id=' + window.G_NowSiteId + "&tab_index=" + parent.G_TabIndex + "";
                addTab();
            }else{
                $("#dialog_box").dialog({
                    height: 140,
                    modal: true
                });
                var dialogContent = $("#dialog_content");
                dialogContent.html("站点参数不正确");
            }
        });
        $("#team_config").click(function() {
            if(window.G_NowSiteId>0){
                window.G_TabTitle = $(this).html();
                window.G_TabUrl = '/default.php?secu=manage&mod=team&m=list&site_id=' + window.G_NowSiteId + "&tab_index=" + parent.G_TabIndex + "";
                addTab();
            }else{
                $("#dialog_box").dialog({
                    height: 140,
                    modal: true
                });
                var dialogContent = $("#dialog_content");
                dialogContent.html("站点参数不正确");
            }
        });
        $("#member_config").click(function() {
            if(window.G_NowSiteId>0){
                window.G_TabTitle = $(this).html();
                window.G_TabUrl = '/default.php?secu=manage&mod=member&m=list&site_id=' + window.G_NowSiteId + "&tab_index=" + parent.G_TabIndex + "";
                addTab();
            }else{
                $("#dialog_box").dialog({
                    height: 140,
                    modal: true
                });
                var dialogContent = $("#dialog_content");
                dialogContent.html("站点参数不正确");
            }
        });
    });
</script>


<div id="div_forum_manage">
    <div id="league_config" class="link_btn line">赛事管理</div>
    <div id="team_config" class="link_btn line">队伍管理</div>
    <div id="member_config" class="link_btn line">球员管理</div>
</div>
