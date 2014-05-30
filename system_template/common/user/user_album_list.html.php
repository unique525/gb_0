<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {common_head}
        <style type="text/css">
            body{font-family: "微软雅黑";font-size:14px}
            .photo { position:relative; font-family:arial; margin:2px 1px; text-align:center; overflow:hidden; border:1px solid #CCC; width:240px; height:320px; float:left}
            .photo .heading, .photo .caption { position:absolute; background:#000; height:50px; width:240px; opacity:0.6; filter:alpha(opacity=60)}
            .photo .heading { top:-30px; }
            .photo .caption { bottom:-70px; left:0px;height:70px }
            .photo .heading span { color:#26c3e5; top:-30px; font-weight:bold; display:block; padding:5px 0 0 10px; }
            .photo .caption span { color:#999; font-size:9px; display:block; padding:5px 10px 0 10px; }
            .photo img{width:240px;height:320px}
        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                style = 'easeOutQuart';
                $('.photo').hover(
                    function() {
                        $(this).children('div:last').stop(false,true).animate({bottom:0},{duration:200, easing: style});
                    },
                    function() {
                        $(this).children('div:last').stop(false,true).animate({bottom:-70},{duration:200, easing: style});
                    }
                );
            });
        </script>
    </head>
    <body>
        <div style="width:99%;border: 1px #ccc solid;height:auto;margin: 0 auto">
            <div id="search_div" style="width:100%;height:100px;margin: 1px;border: 1px #CCC solid">
                <form id="search_form" action="" method="post">
                    类别:
                    <select name="album_tag" id="album_tag">
                        <option value="">全部</option>
                        <icms_list id="user_album_tag_list" type="list">
                            <item><![CDATA[
                                <option value="{f_user_album_tag_value}">{f_user_album_tag_value}</option>
                                ]]>
                            </item>
                        </icms_list>
                    </select>
                    <span>作者:<input type="text" id="author" name="author" value=""/></span>
                    <span>作品名:<input type="text" id="album_name" name="album_name" value=""/></span>
                </form>
            </div>
            <div id="list_div" style="width:100%;height:auto;margin:1px;border: 1px #CCC solid">
                <icms_list id="" type="list">
                    <item>
                        <![CDATA[
                            <div class="photo">
                                <div class="heading">
                                    <span>{f_title}</span>
                                    <span>{f_author}</span>
                                </div>
                                <img src="{f_cover_pic_url}" alt="" />
                                <div class="caption">
                                    <div>{f_create_date}</div>
                                    <div>{f_state}</div>
                                    <div>{f_user_album_tag}</div>
                                    <div style="cursor:pointer">审核</div>
                                    <div style="cursor:pointer">撤销</div>
                                    <div>点击数</div>
                                    <div>评论</div>
                                </div>
                            </div>
                        ]]>
                    </item>
                </icms_list>
                <div style="clear:left"></div>
            </div>
        </div>
    </body>
</html>