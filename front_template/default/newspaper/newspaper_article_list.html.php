<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/image_02/logo57.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/image_02/logo76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/image_02/logo114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/image_02/logo120.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/image_02/logo152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/image_02/logo180.png" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <script type="text/javascript">
        $().ready(function(){
            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

            });
            $("img[src='']").hide();
        });
    </script>
    <style>
        body{background:#efefef;margin:0;}
        img, object { max-width: 100%;}
    </style>
</head>
<body>
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

<div data-am-widget="list_news" class="am-list-news am-list-news-default">
    <!--列表标题-->
    <div class="am-list-news-bd">
        <ul class="am-list">
            <icms id="newspaper_page_{NewspaperPageId}" type="newspaper_article_list" top="100">
                <item>
                    <![CDATA[
                    <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-top"
                        style="margin-bottom:5px;"
                        >
                        <div class="am-list-thumb am-u-sm-12">
                            <a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">
                                <img src="{f_UploadFilePath}" width="100%" alt="{f_NewspaperArticleTitle}"
                                    />
                            </a>
                        </div>
                        <div class=" am-list-main" style="padding:0 5px">
                            <h3 class="am-list-item-hd">
                                <a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">{f_NewspaperArticleTitle}</a>
                            </h3>
                            <div class="am-list-item-text">{f_NewspaperArticleSubTitle}</div>
                        </div>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
    </div>
</div>
</body>
</html>