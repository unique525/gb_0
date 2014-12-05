<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperArticleCiteTitle} - 长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/front_js/comment.js"></script>

    <style>
        body{background:#efefef;}
        img, object { max-width: 100%;}
    </style>
    <script type="text/javascript">
        $(function () {

        });
        $(document).on("pageinit","#pageone",function(){
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
                    listContent = listContent + '<div id="'+v["CommentId"]+'" style="border-bottom:2px dashed #CCC; width:98%; margin:8px 4px;">'+
                        '<table width="99%" cellpadding="0" cellspacing="0">'+
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
                var name = '<span class="guest" style="text-align:left">您还未<a href="/default.php?mod=user&a=login&re_url="' + re_url + ' style="font-weight:bold">登录</a>,目前的身份是游客</span>';
                if (result != "") {
                    if (result["NickName"] != "") {
                        username = result["NickName"];
                    } else {
                        username = result["UserName"];
                    }

                    if (username != undefined && username != "" && username != null) {
                        name = '<span class="username" style="text-align:left">' + username + '</span>';
                    }
                }
                if (tableId > 0) {
                    $('#comment').append('<form id="mainForm" action="/default.php?mod=comment&a=create" data-ajax="false" method="post">'
                        + '<table width="95%" class="">'
                        + '<tr>'
                        + '<td  align="left"><span style="float:right">已经有<span id="count">0</span>人评论</span>'
                        + name + '</td>'
                        + '</tr>'
                        + '<tr>'
                        + '<td><textarea name="content" style="width:99%" rows="5" class="comment_content"></textarea>'
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

<div data-role="page" id="pageone">
    <div data-role="content">
        <div style="text-align:center;">
            <h4 style="text-align:center;">{NewspaperArticleCiteTitle}</h4>
            <h2 style="text-align:center;">{NewspaperArticleTitle}</h2>
            <h4 style="text-align:center;">{NewspaperArticleSubTitle}</h4>
        </div>

        <div>

            <icms id="newspaper_article_{NewspaperArticleId}" type="newspaper_article_pic_list" top="100">
                <item>
                    <![CDATA[
                    <div style="text-align:center;margin-bottom:10px;"><img src="{f_UploadFilePath}" alt="{f_Remark}" /></div>
                    <div style="text-align:center;margin-top:10px;margin-bottom:10px;">{f_Remark}</div>
                    ]]>
                </item>
            </icms>


        </div>

        <div>
            <p style="margin:5px;line-height:130%;" id="content">{NewspaperArticleContent}</p>
        </div>

    </div>
    <!-----------comment------------>
    <div id="comment" idvalue="2" style="width:98%;border:1px solid #CCCCCC;margin:5px auto;{opencomment}"></div>
    <div style="border:1px solid #CCC;width:98%;margin:5px auto;{opencomment}">
        <dl id="commentmessage"></dl>
        <a name="comment"></a>
        <div id="comment_pagerbutton"></div>
    </div>

    <!-----------comment-->

    <div style="padding:10px;">

        <div class="bshare-custom"><div class="bsPromo bsPromo2"></div><a title="分享到微信" class="bshare-weixin" href="javascript:void(0);"></a><a title="分享到QQ空间" class="bshare-qzone"></a><a title="分享到电子邮件" class="bshare-email" href="javascript:void(0);"></a><a title="分享到手机快传" class="bshare-189share" href="javascript:void(0);"></a><a title="分享到手机" class="bshare-shouji" href="javascript:void(0);"></a><a title="分享到i贴吧" class="bshare-itieba" href="javascript:void(0);"></a><a title="分享到百度空间" class="bshare-baiduhi" href="javascript:void(0);"></a><a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到人人网" class="bshare-renren"></a><a title="分享到腾讯微博" class="bshare-qqmb"></a><a title="分享到网易微博" class="bshare-neteasemb"></a><a title="分享到复制网址" class="bshare-clipboard" href="javascript:void(0);"></a><a title="分享到凤凰微博" class="bshare-ifengmb" href="javascript:void(0);"></a><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a><span class="BSHARE_COUNT bshare-share-count" style="float: none;">33.4K</span></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script></div>


</div>

</body>
</html>