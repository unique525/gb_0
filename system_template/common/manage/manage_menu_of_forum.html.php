<script type="text/javascript">
    $(function() {        
        $("#forum_config").click(function() {
            if(window.G_NowSiteId>0){
                window.G_TabTitle = $(this).html();
                window.G_TabUrl = '/default.php?secu=manage&mod=site_config&m=set&type=1&site_id=' + window.G_NowSiteId + "&tab_index=" + parent.G_TabIndex + "";
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
        $("#forum_list").click(function() {
            if(window.G_NowSiteId>0){
            window.G_TabTitle = $(this).html();
            window.G_TabUrl = '/default.php?secu=manage&mod=forum&m=list&site_id=' + window.G_NowSiteId;
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
    <div id="forum_config" class="link_btn line">论坛参数设置</div>
    <div id="forum_list" class="link_btn line">版块管理</div>
</div>
