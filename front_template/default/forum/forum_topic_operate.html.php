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
            //删帖
            var forumTopicDelete = $("#forum_topic_delete");
            forumTopicDelete.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_topic&a=async_remove_to_bin",
                        data: {
                            forum_topic_id: forumTopicId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(result) {

                            var resultCode = parseInt(result);
                            if(resultCode > 0){
                                alert("删除成功");
                                parent.location.href = '/default.php?mod=forum_topic&forum_id={ForumId}';
                            }else if(resultCode == -10){
                                alert("您没有权限操作此功能");
                            }else if(resultCode == -5){
                                alert("参数不正确或者没有登录");
                            }

                        }
                    });

                }
            });
            //置顶
            var forumTopicSetTop = $(".forum_topic_set_top");
            forumTopicSetTop.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {

                    var mode = $(this).attr("idvalue");

                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_topic&a=async_set_top",
                        data: {
                            forum_topic_id: forumTopicId,
                            mode:mode
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(result) {

                            var resultCode = parseInt(result);
                            if(resultCode > 0){
                                alert("置顶成功");
                            }else if(resultCode == -10){
                                alert("您没有权限操作此功能");
                            }else if(resultCode == -5){
                                alert("参数不正确或者没有登录");
                            }

                        }
                    });

                }
            });
            //取消置顶
            var forumTopicCancelTop = $("#forum_topic_cancel_top");
            forumTopicCancelTop.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {

                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_topic&a=async_cancel_top",
                        data: {
                            forum_topic_id: forumTopicId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(result) {

                            var resultCode = parseInt(result);
                            if(resultCode > 0){
                                alert("取消置顶成功");
                            }else if(resultCode == -10){
                                alert("您没有权限操作此功能");
                            }else if(resultCode == -5){
                                alert("参数不正确或者没有登录");
                            }

                        }
                    });

                }
            });

            //加为精华
            var forumTopicBest = $("#forum_topic_best");
            forumTopicBest.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_topic&a=async_set_best",
                        data: {
                            forum_topic_id: forumTopicId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(result) {

                            var resultCode = parseInt(result);
                            if(resultCode > 0){
                                alert("加精成功");
                                parent.location.href = '/default.php?mod=forum_topic&forum_id={ForumId}';
                            }else if(resultCode == -10){
                                alert("您没有权限操作此功能");
                            }else if(resultCode == -5){
                                alert("参数不正确或者没有登录");
                            }

                        }
                    });

                }
            });

            //取消精华
            var forumTopicCancelBest = $("#forum_topic_cancel_best");
            forumTopicCancelBest.click(function(){
                var forumTopicId = parseInt(Request["forum_topic_id"]);
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    $.ajax({
                        type: "get",
                        url: "/default.php?mod=forum_topic&a=async_cancel_best",
                        data: {
                            forum_topic_id: forumTopicId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(result) {

                            var resultCode = parseInt(result);
                            if(resultCode > 0){
                                alert("取消成功");
                                parent.location.href = '/default.php?mod=forum_topic&forum_id={ForumId}';
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
<div id="forum_topic_operate">
    <div class="content">

        <h3 style="line-height:150%;">主题管理</h3>
        <br />

        <input id="forum_topic_delete" type="button" class="btn2" value="删除" />
        <input id="forum_topic_set_top_0" type="button" class="btn2 forum_topic_set_top" idvalue="0" value="本版置顶" />
        <input id="forum_topic_set_top_1" type="button" class="btn2 forum_topic_set_top" idvalue="1" value="分区置顶" />
        <input id="forum_topic_set_top_2" type="button" class="btn2 forum_topic_set_top" idvalue="2" value="全站置顶" />
        <input id="forum_topic_cancel_top" type="button" class="btn2" value="取消置顶" />
        <input id="forum_topic_best" type="button" class="btn2 forum_topic_set_best" value="加入精华" />
        <input id="forum_topic_cancel_best" type="button" class="btn2 forum_topic_cancel_best" style="margin-top: 10px;" value="取消精华" />
    </div>
</div>
</body>
</html>