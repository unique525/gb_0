<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {common_head}
        <style type="text/css">
            body{font-family: "微软雅黑";font-size:14px}
            .image_div{width:240px;height:320px;border:1px #CCC solid;margin:2px;float:left;}
            .pic_div{width:100%;height:320px;position: absolute;}
            .op_div{word-wrap:break-word;margin-top:280px}
        </style>
        <script type="text/javascript">
            $(function(){
                $(".image_div").mouseenter(function(){
                    //$(this).find(".pic_div").stop();
                    $(this).find(".op_div").slideUp(800);
                });
                $(".image_div").mouseleave(function(){
                    //$(this).find(".pic_div").stop();
                    $(this).find(".op_div").slideDown(800);
                });
            })
        </script>
    </head>
    <body>
        <div style="width:99%;border: 1px #ccc solid;height:auto;margin: 0 auto">
            <div id="search_div" style="width:100%;height:100px;margin: 1px;border: 1px #CCC solid">
                <form id="search_form" action="" method="post">
                    类别:
                    <select name="albumtag" id="albumtag">
                        <option value="">全部</option>
                        <cscms id="useralbumtaglist" type="useralbumlist">
                            <item><![CDATA[
                                <option value="{f_useralbumtag_value}">{f_useralbumtag_value}</option>
                                ]]>
                            </item>
                        </cscms>
                    </select>
                    <span>作者:<input type="text" id="author" name="author" value=""/></span>
                    <span>作品名:<input type="text" id="albumname" name="albumname" value=""/></span>
                </form>
            </div>
            <div id="list_div" style="width:100%;height:auto;margin:1px;border: 1px #CCC solid">
                <div class="image_div" id_value="1">
                    <div class="pic_div">
                        <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" style="width:240px;height:320px"/>
                    </div>
                    <div class="op_div">
                            aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                    </div>
                </div>
                <div class="image_div" id_value="2">
                    <div class="pic_div">
                        <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" style="width:240px;height:320px"/>
                    </div>
                    <div class="op_div">
                        aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                    </div>
                </div>
                <div class="image_div" id_value="3">

                </div>
                <div class="image_div" id_value="4">

                </div>
                <div class="image_div" id_value="5">

                </div>
                <div class="image_div" id_value="6">

                </div>
                <div class="image_div" id_value="7">

                </div>
                <div class="image_div" id_value="8">

                </div>
                <div class="image_div" id_value="9">

                </div>
                <div style="clear:left"></div>
            </div>
        </div>
    </body>
</html>