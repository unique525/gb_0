<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <style>
        body{background:#efefef;}
        img, object { max-width: 100%;}
    </style>
    <script type="text/javascript">
        $(function() {
            $("#do-not-say-1").attr("class","am-panel-collapse am-collapse am-in");


        });
    </script>
</head>
<body>

<div data-role="page" id="pageone">
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
                    <td style="text-align:center;cursor:pointer" id="select_date">
                        <a style="color:#000000;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}" target="_blank">
                            往期回顾
                        </a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;"><a href="/search/search.php" target="_self"><img src="/image_02/2.jpg" alt="" id="" /></a></td>
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
                                {f_NewspaperPageName}
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

</body>
</html>