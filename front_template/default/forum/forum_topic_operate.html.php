<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>{cfg_ForumIeTitle}</title>
    <meta name="keywords" content="{cfg_ForumIeKeywords}"/>
    <meta name="description" content="{cfg_ForumIeDescription}"/>
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS"/>
    <meta name="author" content="{cfg_MetaAuthor}"/>
    <meta name="copyright" content="{cfg_MetaCopyright}"/>
    <meta name="application-name" content="{cfg_MetaApplicationName}"/>
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}"/>
    <link rel="archives" title="archives" href="/archiver/"/>
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>

    <script type="text/javascript">


        $(function(){
            var btnConfirm = $("#btnConfirm");
            btnConfirm.click(function(){
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    var forumPostContent = $("#f_ForumPostContent");
                    if (forumPostContent.val() == '') {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("回复内容不能为空");
                    } else {

                        $("#mainForm").attr("action",
                            "/default.php?mod=forum_post&a=reply&forum_topic_id={ForumTopicId}");
                        $('#mainForm').submit();
                    }
                }
            });


        });


    </script>
    <style>
        body{background:#ffffff;}
    </style>
</head>
<body>
<div id="forum_topic_operate">
    <div class="content">

        <h3 style="line-height:150%;">主题管理</h3>
        <br />

        <input id="forum_topic_delete" type="button" class="btn2" value="删除" />
        <input id="forum_topic_set_top_1" type="button" class="btn2" value="本版置顶" />
        <input id="forum_topic_set_top_2" type="button" class="btn2" value="分区置顶" />
        <input id="forum_topic_set_top_3" type="button" class="btn2" value="全站置顶" />
        <input id="forum_topic_cancel_top" type="button" class="btn2" value="取消置顶" />
    </div>
</div>
</body>
</html>