<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{BrowserTitle}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link rel="apple-touch-icon" href="/image_02/logo57.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/image_02/logo76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/image_02/logo114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/image_02/logo120.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/image_02/logo152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/image_02/logo180.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css">
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <style>
        body{margin:0;}
        img, object { max-width: 100%;}
        #datepicker {margin:0 auto;position:relative}
    </style>
    <script type="text/javascript">
        $(function() {
            $( "#datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    var today=new Date();
                    var dateArray=dateText.substr(0,10).split("-");
                    var date=new Date(dateArray[0],dateArray[1]-1,dateArray[2]);
                    if(date<=today){
                        window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date='+dateText;
                    }

                },
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>

</head>
<body>


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

    <div id="datepicker" style="margin:10px;width:100%"></div>
    <script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>

</body>
</html>