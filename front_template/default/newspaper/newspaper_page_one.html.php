<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <style>
        body{background:#efefef;}
        img, object { max-width: 100%;}
        a { text-decoration:none;}
        .page_button{list-style: none;margin:2px;padding:10px;text-align: center;float: left;background-color: #ebebeb}
    </style>

    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript">
        $(document).on("pageinit","#pageone",function(){

            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

                //alert($(this).css("width"));
            });

            $("#img01").on("swiperight",function(){
                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={PreviousNewspaperPageId}'
            });

            $("#img01").on("swipeleft",function(){

                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={NextNewspaperPageId}'
            });
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
        $(function() {

            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

                //alert($(this).css("width"));
            });
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
                    <td style="text-align:center;cursor:pointer"><a href="/">首页</a>
                        <div id="select_page" style="position:absolute;background-color:#ccc;padding-bottom:20px;display:none">
                            <ul>
                                <icms id="news_paper_page" type="list" >
                                    <item>
                                        <![CDATA[
                                        <li class="page_button" idvalue="{f_NewsPaperPageId}">
                                            <a href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_page_id={f_NewsPaperPageId}">
                                            {f_NewspaperPageName}
                                            </a>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </ul>
                        </div>


                    </td>
                    <td style="text-align:center;cursor:pointer" id="pages">版面</td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;cursor:pointer" id="select_date">
                        <a href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}">
                            往期回顾
                        </a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;">{NewspaperPageNo}</td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;"><img src="/image_02/2.jpg" alt="" id="" /></td>
                </tr>
            </table>


        </div>
    </div>

    <div>

        <a href="/default.php?mod=newspaper_article&a=list&newspaper_page_id={CurrentNewspaperPageId}">
            <img id="img01" style="max-width:100%" src="{UploadFilePath}" alt="" />
        </a>
    </div>


</div>

</body>
</html>


