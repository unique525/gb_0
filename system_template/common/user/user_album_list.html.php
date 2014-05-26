<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {common_head}
        <style type="text/css">
            body{font-family: "微软雅黑";font-size:14px}
            .photo { position:relative; font-family:arial; margin:2px 1px; text-align:center; overflow:hidden; border:1px solid #CCC; width:240px; height:320px; float:left}
            .photo .heading, .photo .caption { position:absolute; background:#000; height:30px; width:240px; opacity:0.6; filter:alpha(opacity=60)}
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
                        $(this).children('div:first').stop(false,true).animate({top:0},{duration:200, easing: style});
                        $(this).children('div:last').stop(false,true).animate({bottom:0},{duration:200, easing: style});
                    },
                    function() {
                        $(this).children('div:first').stop(false,true).animate({top:-30},{duration:200, easing: style});
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
                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg"  alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>

                <div class="photo">
                    <div class="heading"><span>源码爱好者网站</span></div>
                    <img src="http://www.image1.cn/upload/user/4417/album_194406/p18o2k0f6e4k7165mn101slj1uv2e.jpg.temp.jpg.thumb.jpg" alt="" />
                    <div class="caption"><span>提供精品开源源码、网页特效、教程文章等建站资源和素材。</span></div>
                </div>
                <div style="clear:left"></div>
            </div>
        </div>
    </body>
</html>