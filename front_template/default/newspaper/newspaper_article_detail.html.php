<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperArticleTitle} - 长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <link rel="apple-touch-icon" href="/image_02/logo57.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/image_02/logo76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/image_02/logo114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/image_02/logo120.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/image_02/logo152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/image_02/logo180.png" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/comment.js"></script>
    <script type="text/javascript" src="/front_js/site/site_ad.js" charset="utf-8"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <style>
        body{background:#efefef;margin:0;}
        img, object { max-width: 100%;}
        .icms_ad_item img{width: 100%;}
    </style>
    <script type="text/javascript">
        $(function () {

            $.post("/default.php?mod=newspaper_article&a=add_hit_count&newspaper_article_id={NewspaperArticleId}", {
                resultbox: $(this).html()
            }, function(result) {

            });


            var content = $("#content").html().replaceAll("\n","<br /><br />");

            $("#content").html(content);

            window.CommentShowCallBack = function(data){
                var listContent = "";
                var pagerButton = data["page_button"];
                comment_count = data["count"];
                var result = data["result"];
                $.each(result, function (i, v) {
                    if (v["UserName"] == "" && v["UserName"] != null) {
                        v["UserName"] = "游客";
                    }
                    listContent = listContent + '<div id="'+v["CommentId"]+'" style="border-bottom:2px dashed #CCC; width:100%; margin:8px 4px;">'+
                        '<table width="100%" cellpadding="0" cellspacing="0">'+
                        '<tr>'+
                        '<td style="padding:5px;">'+
                        '<div style="text-align:left;line-height:180%;">'+v["UserName"]+'&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#666666;font-size:10px;">'+v["CreateDate"]+'</span>&nbsp;&nbsp;&nbsp;&nbsp;</div>'+
                        '<div class="commentcontent" style="color:#666;text-align:left;line-height:180%;font-size:14px;"><table width="100%" style="table-layout:fixed"><tr><td style="word-wrap:break-word">'+v["Content"]+'</td></tr></table></div>'+
                        '</td></tr></table>'+
                        '</div>';

                });

                $("#commentmessage").html(listContent);
                $("#comment_pagerbutton").html(pagerButton);
                $("#count").html(comment_count);
            };

            window.CreateLongCommentCallback = function(data,tableId,tableType,channelId){
                var re_url = window.location.href;
                var username = "";
                var result = data["result"];
                var name = '';//'<span class="guest" style="text-align:left">您还未<a href="/default.php?mod=user&a=login&re_url="' + re_url + ' style="font-weight:bold">登录</a>,目前的身份是游客</span>';

                if (result != "") {
                    if (result["NickName"] != "") {
                        username = result["NickName"];
                    } else {
                        username = result["UserName"];
                    }

                    if (username != undefined && username != "" && username != null) {
                        //name = '<span class="username" style="text-align:left">' + username + '</span>';
                    }
                }

                if (tableId > 0) {
                    $('#comment').append('<form id="mainForm" action="/default.php?mod=comment&a=create" data-ajax="false" method="post">'
                        + '<table width="100%" class="">'
                        + '<tr>'
                        + '<td  align="left"><span style="float:right">已经有<span id="count">0</span>人评论</span>'
                        + name + '</td>'
                        + '</tr>'
                        + '<tr>'
                        + '<td><textarea name="content" style="width:100%" rows="5" class="comment_content"></textarea>'
                        + '<input type="hidden" value="' + tableId + '" name="table_id"/>'
                        + '<input type="hidden" value="' + tableType + '" name="table_type"/>'
                        + '<input type="hidden" name="channel_id" value="' + channelId + '"/>'
                        + '<input type="hidden" id="url" name="url" value="' + getURL() + '"/></td>'
                        + '</tr>'
                        + '<tr><td colspan="2" align="right"><input onclick="sub_comment()" class="publish" type="button" value="发表评论"/></td></tr>'
                        + '</table></form>');
                }


            };

            CreateLongComment({NewspaperArticleId},7,{ChannelId},true);
            CommentShow(0,{NewspaperArticleId},7,"",true);

        });


        /**
         * 全文搜索替换
         */
        String.prototype.replaceAll = function(s1, s2) {
            return this.replace(new RegExp(s1, "gm"), s2);
        }
    </script>
</head>
<body>
<div style="margin:0;">
    <div style="">
        <div>
            <img src="/image_02/top_bg.jpg" style="width:100%;" />
            <table style="position:absolute; top: 10px;" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#ffffff;" href="/">首页</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}">往期回顾</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;"><a href="/search/search.php" target="_self"><img style="width:20px;" src="/image_02/2-2.png" alt="" id="" /></a></td>
                </tr>
            </table>
        </div>
    </div>
<div class="site_ad_266"></div><script language='javascript' src='/front_js/site_ad/2/site_ad_266.js' charset="utf-8"></script>


        <div style="text-align:left;margin:5px;">
            <h4 style="text-align:left;">{NewspaperArticleCiteTitle}</h4>
            <h2 style="text-align:left;">{NewspaperArticleTitle}</h2>
            <h4 style="text-align:left;">{NewspaperArticleSubTitle}</h4>
        </div>


    <div>
            <icms id="newspaper_article_{NewspaperArticleId}" type="newspaper_article_pic_list" top="100">
                <item>
                    <![CDATA[
                    <figure data-am-widget="figure" class="am am-figure am-figure-default "
                            data-am-figure="{  autoZoom: 1 }">
                        <img src="{f_UploadFilePath}" data-rel=""
                             alt="{f_Remark}" />
                        <figcaption class="am-figure-capition-btm">{f_Remark}</figcaption>
                    </figure>
                    ]]>
                </item>
            </icms>

        </div>

        <div data-am-widget="slider" class="am-slider am-slider-a4" data-am-slider='{&quot;directionNav&quot;:false}'>
            <ul class="am-slides">
            <icms id="newspaper_article_slider_{NewspaperArticleId}" type="newspaper_article_pic_list_slider" top="100">
                <item>
                    <![CDATA[
                    <li>
                        <img src="{f_UploadFilePath}" alt="{f_Remark}" style="max-height:300px;" />
                        <br />
                        {f_Remark}
                    </li>
                    ]]>
                </item>
            </icms>
            </ul>
        </div>

        <div>
            <p style="margin:10px;line-height:150%;font-size:120%;" id="content">{NewspaperArticleContent}</p>
        </div>


    <!-----------comment------------>
    <div id="comment" idvalue="2" style="width:98%;margin:5px auto;{OpenComment}"></div>
    <div style="width:98%;margin:5px auto;{OpenComment}">
        <dl id="commentmessage"></dl>
        <a name="comment"></a>
        <div id="comment_pagerbutton"></div>
    </div>

    <!-----------comment-->
    <img src="/image_02/logo50.jpg" alt="长沙晚报" style="width:0;height:0;" />

</div>
</body>
</html>