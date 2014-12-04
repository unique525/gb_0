<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <script type="text/javascript">
        $(document).on("pageinit","#pageone",function(){
            /**
            $(".img_title").each(function(){
                var imgSrc = $(this).attr("src");
                if(imgSrc.length<=0){
                    $(this).remove();
                }
            });
            */


            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

            });
        });

        $().ready(function(){
            $("img[src='']").hide();
        });
    </script>
</head>
<body>
<div style="background:#ebebeb;">
    <div><img src="/image_02/2_05.jpg" width="100%" alt="" id="img_logo" /></div>
</div>
<div style="background:#ebebeb;">
    <div>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr>
                <td style="text-align:center;cursor:pointer"><a style="color:#000000;" href="/" target="_blank">首页</a>
                </td>
                <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                <td style="text-align:center;cursor:pointer;color:#ef1b27;"><a style="color:#000000;" href="/default.php?mod=newspaper&a=gen_page_list&channel_id={ChannelId}&newspaper_id={NewspaperId}" target="_blank">版面</a></td>
                <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                <td style="text-align:center;cursor:pointer" id="select_date">
                    <a style="color:#000000;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}" target="_blank">
                        往期回顾
                    </a></td>
                <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                <td style="text-align:center;"><img src="/image_02/2.jpg" alt="" id="" /></td>
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
                    <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-top">
                        <div class="am-list-thumb am-u-sm-12">
                            <a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">
                                <img src="" width="100%" alt="{f_NewspaperArticleTitle}"
                                    />
                            </a>
                        </div>
                        <div class=" am-list-main">
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