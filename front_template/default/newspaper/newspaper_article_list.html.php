<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{BrowserTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
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

            $("#pages").click(function(){

                if($("#select_page").css("display")=="none")
                    $("#select_page").show();
                else
                    $("#select_page").hide();
            });
        });
    </script>
    <style>
        body{margin:0;}
        img, object { max-width: 100%;}
        .page_button{list-style: none;margin:2px;padding:5px;text-align:center;float: left;background-color: #D3D3D3;}
        #page_list{cursor: pointer}
        .icms_ad_item img{width: 100%;}
    </style>
</head>
<body>
<div style="">
    <div>
        <pre_temp id="26"></pre_temp>
        <img src="/image_02/top_bg.jpg" style="width:100%;height:35px;" />
        <table style="position:absolute; top: 40px;" cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr>
                <td style="text-align:center;">
                <a style="color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_one&newspaper_page_id={NewspaperPageId}">
                    返回
                </a>
                    <div id="select_page" style="position:absolute;background-color:#ebebeb;padding-bottom:80px;display:none;z-index:999;">
                        <ul>
                            <icms id="newspaper_page" type="list" >
                                <item>
                                    <![CDATA[
                                    <li class="page_button" idvalue="{f_NewspaperPageId}">
                                        <a href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={f_NewspaperPageId}">
                                            {f_NewspaperPageName}({f_NewspaperPageNo})
                                        </a>
                                    </li>
                                    ]]>
                                </item>
                            </icms>
                        </ul>
                    </div>
                </td>
                <td style="text-align:center;"><a style="text-decoration:none;color:#ffffff;" href="/default.php?mod=newspaper&a=gen_one&channel_id=15">首页</a>

                </td>
                <td style="text-align:center;cursor:pointer;"><a style="color:#FFE56C;" id="pages">版面</a></td>
                <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                <td style="text-align:center;"><a style="text-decoration:none;color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}">往期回顾</a></td>
                <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                <td style="text-align:center;"><a href="/search/search.php" target="_self"><img style="width:20px;" src="/image_02/2-2.png" alt="" id="" /></a></td>
            </tr>
        </table>
    </div>
</div>

<div style="line-height:150%;margin:10px;"> 长沙晚报 {PublishDate} {NewspaperPageName}（{NewspaperPageNo}） </div>

<div data-am-widget="list_news" class="am-list-news am-list-news-default">
    <!--列表标题-->
    <div class="am-list-news-bd">
        <ul class="am-list">
            <icms id="newspaper_page_{NewspaperPageId}" type="newspaper_article_list" top="100">
                <item>
                    <![CDATA[
                    <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-top"
                        style="margin-bottom:5px;padding-top:0;"
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
<script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>

</body>
</html>