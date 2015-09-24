<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <link type="text/css" href="/front_template/default/skins/gray/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/manage/tabs.js"></script>

    <link rel="archives" title="archives" href="/default.php?mod=forum&a=archiver" />
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet" />


    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>
    <script src="/system_js/tiny_mce/tiny_mce_src.js"></script>
    <script src="/system_js/tiny_mce/editor.js"></script>
    <script type="text/javascript">
        var editor;
        $(function () {

            var btnConfirm = $("#btnConfirm");
            btnConfirm.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                var forumId = Request["forum_id"];
                var siteid = Request["site_id"];
                    var forumTopicTitle = $("#f_ForumTopicTitle");
                    if (forumTopicTitle.val() == '') {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("请输入标题");
                    } else {
                        $("#mainForm").attr("action",
                            "/default.php?secu=manage&mod=forum_topic&a=modify&forum_id="+forumId+"&site_id="+siteid+"&forum_topic_id="+forumTopicId);
                        $('#mainForm').submit();
                    }
            });
        });


    </script>
</head>
<body>
<form id="mainForm" enctype="multipart/form-data" method="post">
    <div id="dialog_box" title="提示" style="display:none;">
        <div id="dialog_content">
        </div>
    </div>
    <div id="forum_topic">
        <table width="60%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="height:36px;text-align:center;">
                    标题：<input type="text" class="input_box_topic_title" id="f_ForumTopicTitle" name="f_ForumTopicTitle"
                           value="{f_ForumTopicTitle}" style="width:70%;" maxlength="300"  />
                    <input type="hidden" id="f_ForumTopicId" name="f_ForumTopicId" value="{f_ForumTopicId}"/>
                </td>
            </tr>
            <tr>
                <td style="height:700px;text-align:center;">
                    <textarea id="f_ForumPostContent" class="mceEditor" name="f_ForumPostContent"
                               style=" width: 70%;height: 600px;">{f_ForumPostContent}</textarea>
                </td>
            </tr>
        </table>
           <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="60" align="center">
                        <input id="btnConfirm" class="btn" value="确 认" type="button" />
                        <input id="btnCancel" class="btn" value="取 消" type="button" />
                    </td>
                </tr>
            </table>
    </div>
</form>
</body>
</html>