<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/image_02/apple-touch-icon-57x57-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="/image_02/apple-touch-icon-114x114-precomposed.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link rel="shortcut icon" type="image/ico" href="/image_02/favicon.ico">
    <style>
        body{background:#efefef;margin:0;}
        img, object { max-width: 100%;}
        a { text-decoration:none; color: #000000;cursor:pointer}
        .page_button{list-style: none;margin:2px;padding:5px;text-align:center;float: left;background-color: #D3D3D3;}
        #page_list{cursor: pointer}
    </style>

    <script src="/system_js/jquery-1.9.1.min.js"></script>
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
            $(".page_button").click(function(){
                var newspaperPageId=$(this).attr("idvalue");
                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id='+newspaperPageId
            });
        });
    </script>
    <script type="text/javascript" src="/front_js/site/site_ad.js" charset="utf-8"></script>
</head>
<body>
<div>
    <div style="background:#ebebeb;">
        <div><img src="/image_02/2_05.jpg" width="100%" alt="" id="img_logo" /></div>
    </div>
    <div style="background:#ebebeb;">
        <div>
            <table cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                    <td style="text-align:center;cursor:pointer"><a style="color:#000000;" href="/" target="_blank">首页</a>
                        <div id="select_page" style="position:absolute;background-color:#ebebeb;padding-bottom:20px;display:none">
                            <ul>
                                <icms id="newspaper_page" type="list" >
                                    <item>
                                        <![CDATA[
                                        <li class="page_button" idvalue="{f_NewspaperPageId}">
                                            <a href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={f_NewspaperPageId}">
                                            {f_NewspaperPageName}
                                            </a>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </ul>
                        </div>


                    </td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;cursor:pointer;"><a style="color:#ef1b27;" id="pages">版面</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;cursor:pointer" id="select_date">
                        <a style="color:#000000;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}" target="_blank">
                            往期回顾
                        </a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;" ><a style="color:#000000;" href="/default.php?mod=newspaper&a=gen_page_list&channel_id={ChannelId}&newspaper_id={NewspaperId}" target="_blank">{NewspaperPageNo}</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;"><a href="/search/search.php" target="_self"><img src="/image_02/2.jpg" alt="" id="" /></a></td>
                </tr>
            </table>


        </div>
    </div>
    <div class="site_ad_265"></div><script type="text/javascript" src='/front_js/site_ad/2/site_ad_265.js' charset="utf-8"></script>
    <div>
        <a href="/default.php?mod=newspaper_article&a=list&newspaper_page_id={CurrentNewspaperPageId}&newspaper_id={NewspaperId}&channel_id={ChannelId}" target="_blank">
            <img id="img01" style="max-width:100%;width:100%;" src="{UploadFileCompressPath1}" alt="" />
        </a>
    </div>


</div>
</body>
</html>


