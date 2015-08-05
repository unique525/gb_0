<script type="text/javascript">

    $(function () {

        var btnManageUserDocument = $("#btn_statistic_manage_user_document");
        btnManageUserDocument.css("cursor","pointer");
        btnManageUserDocument.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "后台帐号";
            window.G_TabUrl = '/default.php?secu=manage&mod=task&m=statistic_document_of_manage_user';
            addTab();
        });

        var btnMyDocument = $("#btn_statistic_my_document");
        btnMyDocument.css("cursor","pointer");
        btnMyDocument.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "后台帐号";
            window.G_TabUrl = '/default.php?secu=manage&mod=task&m=statistic_document_of_mine';
            addTab();
        });


        var btMyDocument = $("#btn_statistic_my_document");
        btMyDocument.css("cursor","pointer");
        btMyDocument.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "我的文档";
            window.G_TabUrl = '/default.php?secu=manage&mod=task&m=statistic_document_of_manage_user';
            addTab();
        });

        var btnGroupDocument = $("#btn_statistic_manage_user_group_document");
        btnGroupDocument.css("cursor","pointer");
        btnGroupDocument.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "部门文档";
            window.G_TabUrl = '/default.php?secu=manage&mod=task&m=statistic_document_of_manage_user_group';
            addTab();
        });

        var btnNewspaper = $("#btn_statistic_newspaper_hit_rank");
        btnNewspaper.css("cursor","pointer");
        btnNewspaper.click(function () {
            //打开站点管理页面
            window.G_TabTitle = "微报纸";
            window.G_TabUrl = '/default.php?secu=manage&mod=task&m=statistic_newspaper_hit_rank';
            addTab();
        });
    });



</script>
<div id="div_task_manage">
    <div class="task_one">
        <div class="taskline1" onclick="adminusertaskclick(1,-1,-1,1)">我的任务</div>
        <div class="taskline2" onclick="adminusertaskclick(1,0,-1,1)">未完成任务</div>
        <div class="taskline3" onclick="adminusertaskclick(1,10,-1,1)">已完成任务</div>
        <div class="taskline4" onclick="adminusertaskclick(1,100,-1,1)">已删除任务</div>
    </div>
    <div class="task_one">
        <div class="taskline1" onclick="adminusertaskclick(1,-1,-1,0)">全部任务</div>
        <div class="taskline2" onclick="adminusertaskclick(1,0,-1,0)">未完成任务</div>
        <div class="taskline3" onclick="adminusertaskclick(1,10,-1,0)">已完成任务</div>
        <div class="taskline4" onclick="adminusertaskclick(1,100,-1,0)">已删除任务</div>
        <div class="taskline4" onclick="adminusertaskclick(1,10000,-1,0)">任务统计</div>
    </div>
    <div class="task_one">
        <div class="taskline1" id="btn_statistic_manage_user_document" onclick="adminusertaskclick(1,10002,-1,0)">文档统计</div>
        <div class="taskline2" id="btn_statistic_my_document" onclick="adminusertaskclick(1,10002,-1,0)">我的文档</div>
        <div class="taskline2" id="btn_statistic_manage_user_group_document" onclick="adminusertaskclick(1,10001,-1,0)">部门文档</div>
    </div>
    <div class="task_one">
        <div class="taskline1" id="" onclick="">微报纸</div>
        <div class="taskline2" id="btn_statistic_newspaper_hit_rank" onclick="">访问统计</div>
    </div>
</div>