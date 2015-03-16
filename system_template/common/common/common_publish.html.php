<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
    <script type="text/javascript">
        $(function(){

            $("#currentSiteName").val(parent.parent.G_NowSiteName);

            $("#pub_site_channel").attr("href","/default.php?secu=manage&mod=common&m=batch_publish&site_id="
                +parent.parent.G_NowSiteId+"&publish_type=1&do=1");

        });

        function SetWaitPublish(state){
            $.ajax({
                url: "/default.php?secu=manage&mod=common&m=async_set_wait_publish&site_id="
                    +parent.parent.G_NowSiteId,
                data: { State: state},
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if(data==true){
                        $("#pub_site_document_news").css("cursor","pointer");
                        $("#pub_site_document_news").css("color","#52596B");
                        $("#pub_site_document_news").attr("href","/default.php?secu=manage&mod=common&m=batch_publish&site_id="
                            +parent.parent.G_NowSiteId+"&publish_type=2&do=1");
                    }else{
                        alert("标记失败！");
                    }
                }
            });
        }

        function CancelWaitPublish(){
            $.ajax({
                url: "/default.php?secu=manage&mod=common&m=async_cancel_wait_publish&site_id="
                    +parent.parent.G_NowSiteId,
                data: {},
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    if(data==true){
                        alert("取消标记成功！");
                        $("#pub_site_document_news").css("cursor","none");
                        $("#pub_site_document_news").css("color","#bbb");
                        $("#pub_site_document_news").removeAttr("href");
                    }
                }
            });
        }
    </script>
</head>
<body>
{common_body_deal}
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <td height="30">

                当前站点: <span id="currentSiteName"></span>

            </td>

        </tr>

        <tr>
            <td style="line-height: 40px" valign="center">

                <a id="pub_site_channel" href="" class="btn2" style="padding: 5px 10px;">发布所有频道</a>
                </td></tr>
        <tr>

            <td style="line-height: 40px" valign="center">
                <select id="f_State" name="f_state" class="btn2" style="display: inline;float: left;margin: 5px 5px 0 0;border: 1px solid #CCC;height:29px;background-color: #fff" >
                    <option value="-1">请选择要标记的文档</option>
                    <option value="30" onclick="SetWaitPublish(30)">所有已发</option>
                    <option value="14" onclick="SetWaitPublish(14)">所有终审</option>
                    <option value="0" onclick="SetWaitPublish(0)">所有新稿</option>
                </select>
                <script type="text/javascript">
                    $("#f_State").find("option[value='-1']").attr("selected",true);
                </script>
                <a id="pub_site_document_news" title="请先标记" class="btn2" style="padding: 5px 10px;color:#bbb;cursor: pointer">[批量发布所有已标记文档]</a>
                <a onclick="CancelWaitPublish()" class="btn2" style="padding: 5px 10px;">重置发布标记</a><br />

            </td>
        </tr>


    </table>



    <div style="width:95%;height:335px;overflow:auto;margin:0;border:1px solid #cccccc;padding:5px;">
        发布结果：<br />
        {Result}
    </div>

</body>
</html>
