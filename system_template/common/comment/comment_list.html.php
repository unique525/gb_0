<!DOCTYPE html>
<html>
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
        });
    </script>

</head>
<body>
<div class="div_list">
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td style="width:80px;text-align: center">ID</td>
            <td style="text-align: center">内容</td>
            <td style="width:100px;text-align: center">用户名</td>
            <td style="width:80px;text-align: center">状态</td>
            <td  style="width:80px;text-align: center">启用  停用</td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="comment_list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:80px;text-align: center">{f_CommentId}</td>
                            <td class="spe_line2" style="text-align: center">
                                {f_Content}
                            </td>
                            <td class="spe_line2" style="width:100px;text-align: center">
                                <div title="{f_UserId}">{f_UserName}</div>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <span class="span_state" idvalue="{f_CommentId}">{f_State}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <img class="div_start" idvalue="{f_CommentId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                &nbsp;&nbsp;
                                <img class="div_stop" idvalue="{f_CommentId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>