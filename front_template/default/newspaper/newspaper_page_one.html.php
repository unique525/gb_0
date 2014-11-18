<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).on("pageinit","#pageone",function(){

            $("#img_logo").load(function(){
                var screenWidth = $(document).width();

                $(this).css("width",screenWidth);

                //alert($(this).css("width"));
            });

            $("#img01").on("swiperight",function(){
                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&newspaper_page_id={PreviousNewspaperPageId}'
            });

            $("#img01").on("swipeleft",function(){

                window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&newspaper_page_id={NextNewspaperPageId}'
            });
        });
        $(function() {

            $("#showold").click(function(){
                $( "#datepicker" ).datepicker({
                    onSelect: function(dateText, inst) {
                        window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date='+dateText;

                    },
                    dateFormat: 'yy-mm-dd'
                });
            });
        });
    </script>
</head>
<body>

<div data-role="page" id="pageone">

    <div>
        <div style="float:left"><img src="/image_02/2_05.jpg" alt="" id="img_logo" /></div>
        <div style="float:right;vertical-align:middle;">{NewspaperTitle}</div>
        <div style="clear:both;padding:0;margin:0;width:0;height:0;"></div>
    </div>
    <div>
        <a href="/default.php?mod=newspaper_article&a=list&newspaper_page_id={CurrentNewspaperPageId}">
       <img id="img01" style="max-width:100%" src="{UploadFilePath}" alt="" />
        </a>
    </div>

    <div style="">
        <div id="datepicker"></div>
        <div id="showold" style="background:#eeeeee;height:40px;text-align:center;vertical-align: middle;padding-top:10px;">往期报纸</div>

    </div>


</div>

</body>
</html>


