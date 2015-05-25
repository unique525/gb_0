<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <style type="text/css">
        .albumpic{width:155px;height:150px;margin:10px 10px 10px 20px;float:left;border:1px #CCC solid; display:block;vertical-align:middle;text-align:center;}
        .albumpic img{max-width:149px;width:expression(this.width>149?149:true);max-height:151px;height:expression(this.height>151?151:true);}

    </style>
    <script type="text/javascript">
        var width = 348;
        var height = 377;
        jQuery(function($){

            var id = trim(Request['aid']);
            getAlbumpicList(id);
        });
        function getAlbumpicList(id){
            $.ajax({
                url:"http://www.image1.cn/index.php",
                data:{a:"user",f:"useralbum",m:"albumpiclist",albumid:id,btype:0},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var result = new Array();
                    result = data["result"];
                    var templatelist =  '';
                    ReplaceList(templatelist ,result,"useralbumpiclist");
                }
            });
        }
        function getsize(a){
            a.css("margin-top",(150-a.height())/2);
        }
        function selectpic(id){
            $.ajax({
                url:"http://www.image1.cn/index.php",
                data:{a:"user",f:"useralbum",m:"createtemppic",picid:id,width:750,height:750},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){


                    var src= data["result"];
                    $("#target").attr("src","http://www.image1.cn"+src);
                    var jcrop_api, boundx, boundy;

                    var allowResize = false;
                    var minSize = [348,377];

                    if($("#target").height()<370){
                        allowResize = true;
                        minSize = [348/2,377/2];
                    }



                    $('#target').Jcrop({
                        allowSelect:false,
                        onChange: updatePreview,
                        onSelect: updatePreview,
                        aspectRatio: 12/13,
                        bgFade:true,
                        bgOpacity: .3,
                        minSize :minSize,
                        setSelect:[0,0,348,377],
                        allowResize:allowResize
                    },function(){
                        // Use the API to get the real image size
                        //this.setImage("http://www.image1.cn"+src,function(){});
                        //setTimeout('boundx = $("#img2").width();boundy = $("#img2").height();',5000);
                        //alert($("#img2").width());
                        //alert($("#img2").height());
                        //var img2_width = $("#img2").width();
                        //var img2_height = $("#img2").height();
                        //alert(img2_width);
                        //alert(img2_height);
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the API in the jcrop_api variable
                        jcrop_api = this;
                    });
                    function updatePreview(c)
                    {
                        if (parseInt(c.w) > 0)
                        {
                            var rx = width / c.w;
                            var ry = height / c.h;
                            $('#preview').css({
                                width: Math.round(rx * boundx) + 'px',
                                height: Math.round(ry * boundy) + 'px',
                                marginLeft: '-' + Math.round(rx * c.x) + 'px',
                                marginTop: '-' + Math.round(ry * c.y) + 'px'
                            });
                            $('#x').val(c.x);
                            $('#y').val(c.y);
                            $('#w').val(c.w);
                            $('#h').val(c.h);
                        }
                    };
                    $('#height').val(height);
                    $('#width').val(width);
                    $('#source').val(src);
                    $("#preview").attr("src","http://www.image1.cn"+src);
                    $("#useralbumpiclist").css("display","none");
                    $("#outer").css("display","block");
                    $("#picid").val(id);
                }
            });
        }

        function sub(){
            $("#cutimg").submit();
            //window.close();
        }
    </script>

</head>

<body>
<div style=" background:#FFFFFF; margin:0px auto;height:100%; overflow:hidden; padding-top:20px; width:1080px;">
    <div id="box1000">
        <div class="mainBox">
            <div class="mainBor overhidden minheight">
                <pretemp id="447"></pretemp>
                <div style="font-size:14px; margin:15px 0px 0px 20px;">请选择一张图片作为封面:</div>
                <div id="useralbumpiclist">

                    <div class="albumpic" id="pic_1007153" style="cursor:pointer" onclick="selectpic(1007153)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g2njk1s2gh3pv177ccvqm3.JPG.temp.jpg.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007153" value="/upload/user/23594/album_239059/p19m4g2njk1s2gh3pv177ccvqm3.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007154" style="cursor:pointer" onclick="selectpic(1007154)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g38qc1qmlt5o220m0t9034.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007154" value="/upload/user/23594/album_239059/p19m4g38qc1qmlt5o220m0t9034.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007155" style="cursor:pointer" onclick="selectpic(1007155)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g3mkd1trh1alfabdmjk1hk15.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007155" value="/upload/user/23594/album_239059/p19m4g3mkd1trh1alfabdmjk1hk15.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007156" style="cursor:pointer" onclick="selectpic(1007156)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g5a52dp9lsh13271mjmj1e6.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007156" value="/upload/user/23594/album_239059/p19m4g5a52dp9lsh13271mjmj1e6.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007157" style="cursor:pointer" onclick="selectpic(1007157)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g78j61pf311kcdto1js28hg7.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007157" value="/upload/user/23594/album_239059/p19m4g78j61pf311kcdto1js28hg7.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007158" style="cursor:pointer" onclick="selectpic(1007158)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g7kjh1hqu1nvr1l7t3971v0i8.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007158" value="/upload/user/23594/album_239059/p19m4g7kjh1hqu1nvr1l7t3971v0i8.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007159" style="cursor:pointer" onclick="selectpic(1007159)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g8l0v1dvgh31mjq18h81en09.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007159" value="/upload/user/23594/album_239059/p19m4g8l0v1dvgh31mjq18h81en09.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007160" style="cursor:pointer" onclick="selectpic(1007160)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g957oh5t1qji1im613m0vv7a.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007160" value="/upload/user/23594/album_239059/p19m4g957oh5t1qji1im613m0vv7a.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007161" style="cursor:pointer" onclick="selectpic(1007161)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4g9qp4jf7ofc1ssn22if8eb.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007161" value="/upload/user/23594/album_239059/p19m4g9qp4jf7ofc1ssn22if8eb.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007162" style="cursor:pointer" onclick="selectpic(1007162)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4ga79618sv1jfo1pdi1q4f16m7c.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007162" value="/upload/user/23594/album_239059/p19m4ga79618sv1jfo1pdi1q4f16m7c.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007163" style="cursor:pointer" onclick="selectpic(1007163)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4gajgvh491l4i1ctf17ju1gbrd.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007163" value="/upload/user/23594/album_239059/p19m4gajgvh491l4i1ctf17ju1gbrd.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007164" style="cursor:pointer" onclick="selectpic(1007164)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4gbauus8adct1gvj6j81i0ae.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007164" value="/upload/user/23594/album_239059/p19m4gbauus8adct1gvj6j81i0ae.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007165" style="cursor:pointer" onclick="selectpic(1007165)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4gbo8rlp29251vj012i010aqf.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007165" value="/upload/user/23594/album_239059/p19m4gbo8rlp29251vj012i010aqf.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007166" style="cursor:pointer" onclick="selectpic(1007166)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4gc23i19mt7c61g6b68i1ehlg.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007166" value="/upload/user/23594/album_239059/p19m4gc23i19mt7c61g6b68i1ehlg.JPG"/>
                    </div>

                    <div class="albumpic" id="pic_1007167" style="cursor:pointer" onclick="selectpic(1007167)">
                        <img class="img" src="http://www.image1.cn/upload/user/23594/album_239059/p19m4gceml1u3c1s9isg21gk1q8fh.JPG.thumb.jpg" onload="getsize($(this));" />
                        <input type="hidden" id="url_1007167" value="/upload/user/23594/album_239059/p19m4gceml1u3c1s9isg21gk1q8fh.JPG"/>
                    </div>

                </div>
                <div style="clear:left"></div>
                <div id="outer" style="display:none">
                    <div class="jcExample">
                        <div class="article">
                            <table>
                                <tr>
                                    <td>
                                        <img src="" id="target" alt="Flowers"/>
                                    </td>
                                    <td>
                                        <div style="width:348px;height:377px;overflow:hidden;">
                                            <img src="" id="preview" alt="Preview" class="jcrop-preview" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <form action="http://www.image1.cn/index.php?a=user&f=useralbum&m=createmainpic&returnmessage=1" id="cutimg" method="post" runat="server">
                                <iframe name="abc"   width="0px"   height="0px"   frameborder="0"    style="display:none"> </iframe>
                                <input type="hidden" id="x" name="x" />
                                <input type="hidden" id="y" name="y" />
                                <input type="hidden" id="w" name="w" />
                                <input type="hidden" id="h" name="h" />
                                <input type="hidden" value="" id="height" name="height"/>
                                <input type="hidden" value="" id="width" name="width"/>
                                <input type="hidden" value="" id="source" name="source"/>
                                <input type="hidden" value="" id="picid" name="picid"/>
                                <input type="button" class="btn" onclick="sub();" value="确定" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blank10px"></div></div>
</body>
</html>

