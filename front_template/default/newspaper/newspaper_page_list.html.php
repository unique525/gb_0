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
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <style>
        body{margin:0;}
        img, object { max-width: 100%;}
    </style>
    <script type="text/javascript">
        $(function() {
            //$("#do-not-say-1").attr("class","am-panel-collapse am-collapse am-in");
        });
    </script>
</head>
<body>

<div style="margin:0;">
    <div style="">
        <div>
            <pre_temp id="26"></pre_temp>
            <img src="/image_02/top_bg.jpg" style="width:100%;height:35px;" />
            <table style="position:absolute; top: 40px;" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#ffffff;" href="/default.php?mod=newspaper&a=gen_one&channel_id=15">首页</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}">往期回顾</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;"><a href="/search/search.php" target="_self"><img style="width:20px;" src="/image_02/2-2.png" alt="" id="" /></a></td>
                </tr>
            </table>
        </div>
    </div>
    <div id="" style="margin:10px auto;width:90%">


        <div class="am-panel-group" id="accordion">

            <icms id="newspaper_page_and_article" type="list" >
                <item>
                    <![CDATA[
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd">
                            <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-{c_no}'}">
                                {f_NewspaperPageName}（{f_NewspaperPageNo}）
                            </h4>
                        </div>
                        <div id="do-not-say-{c_no}" class="am-panel-collapse am-collapse">
                            {child}
                            <p></p>
                        </div>
                    </div>
                    ]]>
                </item>
                <child>
                    <![CDATA[
                    <div class="am-panel-bd">
                        <a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">
                            {f_NewspaperArticleTitle}
                        </a>
                    </div>
                    ]]>
                </child>
            </icms>
        </div>


    </div>
</div>
<script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>

</body>
</html>