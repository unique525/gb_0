<!DOCTYPE html>
<html>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
<link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        function FormatState(state,idvalue){
            var result;
            switch(state){
                case "0":
                    result = '<span class="span_state" id="State_'+idvalue+'">未审核</span>';
                    break;
                case "10":
                    result = '<span class="span_state" id="State_'+idvalue+'">先审后发</span>';
                    break;
                case "20":
                    result = '<span class="span_state" id="State_'+idvalue+'">先发后审</span>';
                    break;
                case "30":
                    result = '<span class="span_state" id="State_'+idvalue+'">已审</span>';
                    break;
                case "100":
                    result = '<span class="span_state" id="State_'+idvalue+'">已否</span>';
                    break;
                default:
                    result = '<span class="span_state"  id="State_'+idvalue+'">启用</span>';
                    break;
            }
            return result;
        }

        function ChangeState(idvalue,state){
            $.ajax({
                url:"/default.php?secu=manage&mod=comment&m=async_modify_state",
                data:{comment_id:idvalue,state:state},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    if(data["result"] > 0){
                        var state_div = $("#State_"+idvalue);
                        state_div.html(""+FormatState(state,idvalue)+"");
                    }else{
                        alert("修改失败");
                    }
                }
            });
        }
        $(function(){
            $(".div_start").click(function(){
                var commentId = $(this).attr("idvalue");
                ChangeState(commentId,"30");
            });

            $(".div_stop").click(function(){
                var commentId = $(this).attr("idvalue");
                ChangeState(commentId,"100");
            });

            $(".span_state").each(function(){
                var state = $(this).html();
                var idvalue = $(this).attr("idvalue");
                $(this).html(FormatState(state,idvalue));
            });
            //评论回复
            $(".btn_Comment").click(function () {
                var commentId = $(this).attr("idvalue");
                //var top = document.documentElement.clientWidth/2;
                //var left = ($(document.body).width() - $("#dialog_manage_comment_answer_box").width())/2;
                var url = '/default.php?secu=manage&mod=comment&m=reply&commentid='+ commentId+'&site_id={siteId}';
                alert(url);
                $("#dialog_manage_comment_reply_frame").attr("src", url);
                $("#dialog_manage_comment_reply_box").dialog({
                    hide: true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
                    autoOpen: true,
                    position: [400, 100],
                    height: 400,
                    width: 500,
                    modal: true, //蒙层（弹出会影响页面大小）
                    title: '评论回复',
                    overlay: {opacity: 0.5, background: "black", overflow: 'auto'}
                });
            });
        });

    </script>

</head>
<body>
{common_body_deal}
<div class="div_list">
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td style="width:80px;text-align: center">ID</td>
            <td style="width:80px;text-align: center">状态</td>
            <td  style="width:80px;text-align: center">启用  停用</td>
            <td style="text-align: left;padding-left: 4px">内容</td>
            <td style="width:220px;text-align: center">会员帐号/手机帐号/邮箱帐号</td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="comment_list" type="comment_list" where="parent">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:80px;text-align: center">{f_CommentId}</td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <span class="span_state" idvalue="{f_CommentId}">{f_State}</span> | <span class="btn_Comment" idvalue="{f_CommentId}" style="cursor: pointer">回复</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <img class="div_start" idvalue="{f_CommentId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                &nbsp;&nbsp;
                                <img class="div_stop" idvalue="{f_CommentId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                            <td class="spe_line2" style="text-align: left;padding-left: 4px">
                                <a href="{f_SourceUrl}" target="_blank">{f_Content}</a>
                                <table>
                                    <tr><td width="100px"></td><td>{child}</td></tr>
                                </table>

                                <div class="spe"></div>
                            </td>
                            <td class="spe_line2" style="width:220px;text-align: center">
                                <div title="{f_UserId}">{f_UserName}<br />{f_UserMobile}<br />{f_UserEmail}</div>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
            <child>
                <![CDATA[
                <div class="comment_two_item{itemflag}">
                    回复：{f_Content}
                </div>
                ]]>
            </child>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
<div id="dialog_manage_comment_reply_box" title="评论回复" style="display: none;height:350px;">
    <div id="dialog_manage_comment_reply_content" style="font-size: 14px;">
        <iframe id="dialog_manage_comment_reply_frame" src=""  style="border: 0; " width="100%" height="320px"></iframe>
    </div>
</div>

</body>
</html>