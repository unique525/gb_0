<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{BrowserTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link rel="apple-touch-icon" href="/image_02/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/image_02/logo76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/image_02/logo114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/image_02/logo120.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/image_02/logo152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/image_02/logo180.png" />


    <link rel="shortcut icon" type="image/ico" href="/image_02/favicon.ico" />
    <link rel="stylesheet" href="/system_js/amaze_ui/assets/css/amazeui.min.css" />
    <style>
        body{margin:0;}
        img, object { max-width: 100%;}
        a { text-decoration:none; color: #000000;cursor:pointer}
        .page_button{list-style: none;margin:2px;padding:5px;text-align:center;float: left;background-color: #D3D3D3;}
        #page_list{cursor: pointer}
        .icms_ad_item img{width: 100%;}
    </style>

    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/amaze_ui/assets/js/amazeui.min.js"></script>
    <script type="text/javascript" src="/system_js/hammer.js"></script>

    <script type="text/javascript">
        $(function() {

            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

                //alert($(this).css("width"));
            });

            var myElement = document.getElementById('img01');

// create a simple instance
// by default, it only adds horizontal recognizers
            var mc = new Hammer(myElement);

// listen to events...
            mc.on("swipeleft", function(ev) {
                //alert(ev.type);
                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={NextNewspaperPageId}';
                //myElement.textContent = ev.type +" gesture detected.";
            });

            mc.on("swiperight", function(ev) {

                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={PreviousNewspaperPageId}';
            });


            //$("#img01").on("swiperight",function(){
            //
            //});

            //$("#img01").on("swipeleft",function(){

            //
            //});
            $("#pages").click(function(){

                if($("#select_page").css("display")=="none")
                    $("#select_page").show();
                else
                    $("#select_page").hide();
            });
            //$(".page_button").click(function(){
                //var newspaperPageId=$(this).attr("idvalue");
                //window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id='+newspaperPageId
            //});
        });
    </script>
    <script type="text/javascript" src="/front_js/site/site_ad.js" charset="utf-8"></script>
</head>
<body>
<div>

    <div class="site_ad_268" style="z-index: 100;position: absolute;width:100%"></div><script language='javascript' src='/front_js/site_ad/2/site_ad_268.js' charset="utf-8"></script><!--落幕-->
    <header class="am-topbar am-topbar-fixed-bottom">
        <div>
            <div style="float:left;"><a href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={PreviousNewspaperPageId}"><img src="/image_02/left.png" /></a></div>
            <div style="float:right;"><a href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={NextNewspaperPageId}"><img src="/image_02/right.png" /></a></div>
            <div style="clear:both;"></div>
        </div>
    </header>

    <div style="">
        <div>
            <pre_temp id="26"></pre_temp>


            <table style="background-image: url('/image_02/top_bg.jpg');height: 35px;background-size: 100% 100%;" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                    <td style="text-align:center;cursor:pointer"><a style="color:#ffffff;" href="/default.php?mod=newspaper&a=gen_one&channel_id=15">首页</a>
                        <div id="select_page" style="position:absolute;background-color:#ebebeb;padding-bottom:80px;display:none">
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
                    <td style="text-align:center;cursor:pointer;"><a style="color:#FFE56C;" id="pages">版面</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;cursor:pointer" id="select_date">
                        <a style="color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}">
                            往期回顾
                        </a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;" ><a style="color:#FFE56C;" href="/default.php?mod=newspaper&a=gen_page_list&channel_id={ChannelId}&newspaper_id={NewspaperId}">{NewspaperPageNo}</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" style="height:20px;" alt="" id="" /></td>
                    <td style="text-align:center;"><a href="/search/search.php" target="_self"><img style="width:20px;" src="/image_02/2-2.png" alt="" id="" /></a></td>
                </tr>
            </table>
        </div>
    </div>


    <div>
        <a href="/default.php?mod=newspaper_article&a=list&newspaper_page_id={CurrentNewspaperPageId}&newspaper_id={NewspaperId}&channel_id={ChannelId}">
            <img id="img01" style="max-width:100%;width:100%;" src="{UploadFileCompressPath1}" alt="" />
        </a>
    </div>
    <div class="site_ad_265"></div><script type="text/javascript" src='/front_js/site_ad/2/site_ad_265.js' charset="utf-8"></script>

</div>
<script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>
</body>
</html>


