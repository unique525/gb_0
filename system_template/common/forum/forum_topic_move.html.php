<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    {common_head}

    <script>
        $(function(){
            $("#move_sure").click(function(){
                var forumIdMoveTo = null;
                $('.forum_select').each(function(){
                    if(this.checked){
                        forumIdMoveTo = $(this).val();
                    }
                });
                if(forumIdMoveTo == null){
                    alert("请选择目标板块");
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: "/default.php?secu=manage&mod=forum_topic&m=move_topic_to_other_block&site_id={siteId}&forum_ids={forumIds}",
                        data: {forum_id_move_to: forumIdMoveTo},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success: function(data){
                            console.log(data);
                            if(data["result"] == 1){
                                alert("移动成功");
                                parent.location.reload();
                            }
                            else{
                                alert("错误:"+data["message"]);
                            }
                        }
                    });
                }
            });
        });

    </script>
</head>
<body>
<div class="div_list">

    <ul id="sort_grid">
        <icms id="forum_list" type="list">
            <item>
                <![CDATA[

                <li id="forum_sort_{f_ForumId}" class="forum_rank_one">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <input class="forum_select"
                                       type="radio"
                                       name = "forum_select"
                                       value="{f_ForumId}"/>
                            </td>
                            <td class="spe_line2">
                                <a target="_blank" href="/default.php?mod=forum_topic&a=list&forum_id={f_ForumId}">
                                    <span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </li>
                {child}
                ]]>
            </item>
            <child>
                <![CDATA[
                <li id="forum_sort_{f_ForumId}" style="margin-left:20px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <input class="forum_select"
                                       type="radio"
                                       name = "forum_select"
                                       value="{f_ForumId}"/>
                            </td>
                            <td class="spe_line2">
                                <span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span>
                            </td>

                        </tr>
                    </table>
                </li>
                {third}
                ]]>
            </child>
            <third>
                <![CDATA[
                <li id="forum_sort_{f_ForumId}" style="margin-left:40px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <input class="forum_select"
                                       type="radio"
                                       name = "forum_select"
                                       value="{f_ForumId}"/>
                            </td>
                            <td class="spe_line2">
                                <a target="_blank" href="/default.php?mod=forum&a=list&forum_id={f_ForumId}">
                                    <span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </li>

                ]]>
            </third>
        </icms>
        <li id="forum_sort_{f_ForumId}" style="margin-left:40px;">
            <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                <tr class="grid_item">
                    <td style="margin-top: 5px;text-align:center"><input class="btn2" type="button" id="move_sure" value="确定"></td>
                </tr>
            </table>
        </li>

    </ul>
</div>
</body>
</html>
