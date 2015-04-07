<script type="text/javascript">
    $(function() {
        $("#statistics_search").click(function() {
            if(window.G_NowSiteId>0){
                window.G_TabTitle = $(this).html();
                window.G_TabUrl = '/default.php?secu=manage&mod=visit&m=statistics_select&site_id=' + window.G_NowSiteId;
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
<div id="div_search_manage">
    <div id="statistics_search" class="link_btn line">统计查询</div>
</div>