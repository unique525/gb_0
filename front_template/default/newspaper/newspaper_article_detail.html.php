<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
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
        <div style="text-align:center;background:#efefef;">
            <h4 style="text-align:center;background:#efefef;">{NewspaperArticleCiteTitle}</h4>
            <h2 style="text-align:center;background:#efefef;">{NewspaperArticleTitle}</h2>
            <h4 style="text-align:center;background:#efefef;">{NewspaperArticleSubTitle}</h4>
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
            <p style="margin:5px;background:#ffffff;line-height:130%;" id="content">{NewspaperArticleContent}</p>
        </div>

    </div>
    <div style="padding:10px;">

        <div class="bshare-custom"><div class="bsPromo bsPromo2"></div><a title="分享到微信" class="bshare-weixin" href="javascript:void(0);"></a><a title="分享到QQ空间" class="bshare-qzone"></a><a title="分享到电子邮件" class="bshare-email" href="javascript:void(0);"></a><a title="分享到手机快传" class="bshare-189share" href="javascript:void(0);"></a><a title="分享到手机" class="bshare-shouji" href="javascript:void(0);"></a><a title="分享到i贴吧" class="bshare-itieba" href="javascript:void(0);"></a><a title="分享到百度空间" class="bshare-baiduhi" href="javascript:void(0);"></a><a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到人人网" class="bshare-renren"></a><a title="分享到腾讯微博" class="bshare-qqmb"></a><a title="分享到网易微博" class="bshare-neteasemb"></a><a title="分享到复制网址" class="bshare-clipboard" href="javascript:void(0);"></a><a title="分享到凤凰微博" class="bshare-ifengmb" href="javascript:void(0);"></a><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a><span class="BSHARE_COUNT bshare-share-count" style="float: none;">33.4K</span></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script></div>


</div>

</body>
</html>