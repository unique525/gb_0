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
            alert("OK");
            var forumPostDelete = $("#forum_post_delete");
            forumPostDelete.click(function(){
                alert("hao");
                var forumPostId = parseInt(Request["forum_post_id"]);
                if (forumPostId == undefined || forumPostId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    alert("1111");
                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_post&a=async_remove_to_bin",
                        data: {
                            forum_post_id: forumPostId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        alert("2222");
                        success: function(result) {

                            var resultCode = parseInt(result);
                            alert(resultCode);
                            if(resultCode > 0){
                                alert("删除成功");
                                parent.location.href = '/default.php?mod=forum_post&a=list&forum_topic_id={ForumTopicId}';
                            }else if(resultCode == -10){
                                alert("您没有权限操作此功能");
                            }else if(resultCode == -5){
                                alert("参数不正确或者没有登录");
                            }

                        }
                    });

                }
            });

        });


    </script>
    <style>
        body{background:#ffffff;}
    </style>
</head>
<body>
<div id="forum_topic_operate" >
    <div class="content">

        <h3 style="line-height:150%;">帖子管理</h3>
        <br />

        <input id="forum_post_delete" type="button" class="btn2" value="删除" />
    </div>
</div>
</body>
</html>