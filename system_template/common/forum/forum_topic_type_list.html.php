<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{f_ForumName}帖子类型管理</title>
    {common_head}
    <script>
        $(document).ready(function(){

            window.siteId = Request["site_id"];
            window.forumId = Request["forum_id"];

            reloadUserState();

            //新建类型
            $("#btn_create").click(function(){
                var url = '/default.php?secu=manage&mod=forum_topic_type&m=create_page&forum_id=' + forumId;
                $("#dialog_frame").attr("src", url);
                $("#dialog_resultbox").dialog({
                    hide: true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                    autoOpen: true,
                    height: 100,
                    width: 300,
                    modal: true, //蒙层（弹出会影响页面大小)
                    title: '新建类型',
                    overlay: {opacity: 0.5, background: "black", overflow: 'auto'}
                });
            });

            //启用
            $(".start_type").click(function(){
                var forumTopicTypeId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + forumTopicTypeId).text();
                if (currentStates != "启用" ){
                    var state = 1;
                    modifyState(forumId,forumTopicTypeId, state);
                }
            });

            //停用
            $(".stop_type").click(function(){
                var forumTopicTypeId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + forumTopicTypeId).text();
                if (currentStates != "停用" ){
                    var state = 0;
                    modifyState(forumId,forumTopicTypeId, state);
                }

            });

            //删除
            $(".delete_type").click(function(){
                var forumTopicTypeId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + forumTopicTypeId).text();

                if (currentStates == "停用" ){
                    deleteType(forumId, forumTopicTypeId);
                }
                else{
                    alert("如果要删除该类型,请先停用该类型");
                }
            });

            //编辑按钮
            $('.edit_type').click(function(){
               var forumTopicTypeId = $(this).attr("idvalue");
               var url = '/default.php?secu=manage&mod=forum_topic_type&m=modify_page&forum_id=' +forumId+ '&forum_topic_type_id=' +forumTopicTypeId;
               $("#dialog_frame").attr("src", url);
               $("#dialog_resultbox").dialog({
                   hide: true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                   autoOpen: true,
                   height: 100,
                   width: 300,
                   modal: true, //蒙层（弹出会影响页面大小)
                   title: '修改类型',
                   overlay: {opacity: 0.5, background: "black", overflow: 'auto'}
               });
           });

            //搜索
            $('#btn_search').click(function(){
                var search_key = $("#search_key").val();
                if(search_key != ''){
                    parent.G_TabUrl = "/default.php?secu=manage&mod=forum_topic_type&m=list&forum_id=" +forumId+ "&search_key=" +search_key;
                    parent.G_TabTitle = '类型搜索';
                    parent.addTab();
                }
            });


        });

        function modifyState(forumId, forumTopicTypeId, state) {
            if(siteId > 0) {
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=forum_topic_type&m=modify_state",
                    data: {
                        forum_id : forumId,
                        forum_topic_type_id : forumTopicTypeId,
                        state : state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        if (data["result"] == 1){
                            $("#span_state_" + forumTopicTypeId).text(state.toString());
                        }else{
                            $("#span_state_" + forumTopicTypeId).text("-1");
                        }
                        reloadUserState();

                    }
                });
            }
        }

        //设置用户状态
        function reloadUserState() {
            $(".span_state").each(function() {
                var state = $(this).text();
                if(!isNaN(state)) {
                    $(this).html(_setUserState(state));
                }

            });
        }

        //格式化状态值
        function _setUserState(state) {
            state = state.toString();
            switch(state) {
                case "1":
                    return "<" + "span style='color:#00CC66'>启用<" + "/span>";
                    break;
                case "0":
                    return "<" + "span style='color:#990000'>停用<" + "/span>";
                    break;
                case "-1":
                    return "系统错误";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        function deleteType(forumId, forumTopicTypeId){
            if(forumTopicTypeId > 0) {
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=forum_topic_type&m=delete_type",
                    data: {
                        forum_id : forumId,
                        forum_topic_type_id : forumTopicTypeId
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        console.log(data["result"]);
                        if (data["result"] == 1){
                            deleteRowWhereUserDeleted(forumTopicTypeId);
                        }else{
                            $("#span_state_" + forumTopicTypeId).text("-1");
                        }
                        reloadUserState();

                    }
                });
            }
        }

        function deleteRowWhereUserDeleted(activityUserId){
            var row = "#forum_sort_" + activityUserId;
            $(row).css("display","none");
        }
    </script>
</head>
<body>
<!----jquery ui弹框----->
<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="300px"></iframe>
    </div>
</div>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 1px;">
    <tr>
        <td id="td_main_btn" width="83">
            <input id="btn_create" class="btn2" value="新建类型" title="新建一个类型" type="button"/>
        </td>

        <td id="td_main_btn" align="right">
            <div id="search_box">
                <label for="search_key"></label><input id="search_key" name="search_key" class="input_box"
                                                       type="text">
                <input id="btn_search" class="btn2" value="查 询" type="button">
                <span id="search_type" style="display: none"></span>
            </div>
        </td>
    </tr>
</table>
<table class="grid" width="100%" cellpadding="0" cellspacing="0">
    <tr class="grid_title">
        <td style="width: 40px; text-align: center; cursor: pointer;padding-left: 25px;" id="btn_select_all">全</td>
        <td style="width: 40px; text-align: center;">编辑</td>
        <td style="text-align: center;">类型</td>
        <td style="text-align: center;width: 60px;">状态</td>
        <td style="text-align: center;width: 60px;">启用</td>
        <td style="text-align: center;width: 60px;">停用</td>
        <td style="width: 60px; text-align: center;">删除</td>
    </tr>
</table>

<ul id="sort_grid">

    <icms id="forum_list_topic_type" type="list">
        <item>
            <![CDATA[
            <li id="forum_sort_{f_ForumTopicTypeId}" style="margin-left:20px;">
                <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                    <tr class="grid_item">

                        <!----------选则框--------------->
                        <td class="spe_line2" style="width:40px;text-align:center;">
                            <input class="doc_input"
                                   type="checkbox"
                                   name="doc_input"
                                   value="{f_ForumTopicTypeId}"/>
                        </td>

                        <!----------编辑按钮--------------->
                        <td class="spe_line2" style="width:40px;text-align:center;cursor:pointer">
                                <span><img class="edit_type"
                                           src="/system_template/{template_name}/images/manage/edit.gif"
                                           idvalue="{f_ForumTopicTypeId}"
                                           alt="编辑"/>
                                </span>
                        </td>

                        <!----------名称--------------->
                        <td class="spe_line2" style="text-align:center">
                            <a target="_blank" href="#">
                                <span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize};">{f_ForumTopicTypeName}</span>
                            </a>
                        </td>

                        <!----------状态--------------->
                        <td class="spe_line2" style="text-align:center;width: 60px;">
                            <a target="_blank" href="#">
                                <span id="span_state_{f_ForumTopicTypeId}" class="span_state">{f_State}</span>
                            </a>
                        </td>

                        <!----------启用--------------->
                        <td class="spe_line2" style="width:60px;text-align:center;cursor:pointer">
                                <span><img class="start_type"
                                           src="/system_template/{template_name}/images/manage/start.jpg"
                                           idvalue="{f_ForumTopicTypeId}"
                                           alt="删除"/>
                                </span>
                        </td>

                        <!----------停用--------------->
                        <td class="spe_line2" style="width:60px;text-align:center;cursor:pointer">
                                <span><img class="stop_type"
                                           src="/system_template/{template_name}/images/manage/stop.jpg"
                                           idvalue="{f_ForumTopicTypeId}"
                                           alt="删除"/>
                                </span>
                        </td>

                        <!----------删除按钮--------------->
                        <td class="spe_line2" style="width:60px;text-align:center;cursor:pointer">
                                <span><img class="delete_type"
                                           src="/system_template/{template_name}/images/manage/delete.jpg"
                                           idvalue="{f_ForumTopicTypeId}"
                                           alt="删除"/>
                                </span>
                        </td>
                    </tr>
                </table>
            </li>
            ]]>
        </item>
    </icms>
</ul>

<div>{PagerButton}</div>
</body>
</html>
