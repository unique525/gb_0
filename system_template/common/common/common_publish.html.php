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
            <td>

                [<a id="pub_site_channel" href="">发布当前站点下所有频道</a>] <br />
                <a id="pub_site_document_news" title="请先标记" style="color:#bbb">[发布当前站点下所有已标记的资讯文档]</a> [<a onclick="SetWaitPublish(30)">标记已发</a>] [<a onclick="SetWaitPublish(14)">标记终审</a>] [<a onclick="CancelWaitPublish()">取消所有标记</a>]<br />

            </td>
        </tr>


    </table>



    <div style="width:95%;height:150px;overflow:auto;margin:0;border:1px solid #cccccc;padding:5px;">
        发布结果：<br />
        {Result}
    </div>

</body>
</html>
