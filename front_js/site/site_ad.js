/**
 * Created by a525 on 14-9-30.
 */


/**
 *
 * @param {int} siteAdId
 * @param {string} switchClassName
 */


$().ready(function() {


        //检查所有广告是否到期
        //var arrOfAllAdContents=document.getElementsByClassName("icms_ad_item");//取所有广告
        var arrOfAllAdContents=$(".icms_ad_item");//取所有广告
        for(var i=0;i<arrOfAllAdContents.length;i++){
            IsInTime(arrOfAllAdContents[i]);
        }



        //轮换
        var switchAdId="0";
        var adItems=$(".site_ad_show_type_2"); //取所有type2(轮换)广告位
        for(i=0;i<adItems.length;i++){
            switchAdId=adItems[i].getAttribute("idvalue");

            //若广告位所有广告都过期或不在时间，则保留第一条显示，取消轮换
            var arrAdContent=$(".switch_"+switchAdId);
            var oneAvailable=0;
            for(var j=0;j<arrAdContent.length;j++){
                if(parseInt(arrAdContent[j].getAttribute("idvalue"))>0){  //广告div的idvalue存储显示时间 -1为过期广告
                    oneAvailable=1
                }else{
                    arrAdContent[j].setAttribute("idvalue","0");
                }
            }
            if(oneAvailable>0){
                adSwitch(switchAdId, 1);  //有可用广告，开始轮换；
            }else{
                $(".switch_"+switchAdId+"_1").show();
            }
        }

        //下拉落幕广告
        var pullItems=$(".site_ad_show_type_4"); //取所有type4(落幕)广告位
        var lastingTime=0;
        for(i=0;i<pullItems.length;i++){
            var id=pullItems[i].getAttribute("idvalue"); //广告位div的idvalue存储广告位id
            lastingTime=$(".pull_"+id).attr("idvalue");  //广告div的idvalue存储显示时间 -1为过期广告
            if(lastingTime>0){
                $("#site_ad_"+id).show();
                setTimeout('PullUp('+id+')',parseInt(lastingTime)*1000);
            }


        }

        //随机
        var randomAdId="0";
        var randomItems=$(".site_ad_show_type_3");  //取所有type3(随机)广告位
        for(i=0;i<randomItems.length;i++){
            randomAdId=randomItems[i].getAttribute("idvalue");

            //若广告位所有广告都过期或不在时间，则第一条显示，取消随机
            var arrRandomAdContent=$(".random_"+randomAdId);
            var arrForRandom=new Array();
            var randomIndex=0;
            for(j=0;j<arrRandomAdContent.length;j++){
                if(parseInt(arrRandomAdContent[j].getAttribute("idvalue"))>=0){  //广告div的idvalue存储显示时间 -1为过期广告
                    arrForRandom[randomIndex]=j+1; // j+1={c_no}   取所有可用广告的{c_no}并加入数组
                    randomIndex++;
                }
            }
            if(randomIndex>0){ //有可用广告，随机可用；
                $(".random_"+randomAdId).hide();
                var randomNumber=parseInt(Math.random()*randomIndex);   //随机范围 0~数组长度
                $(".random_"+randomAdId+"_"+arrForRandom[randomNumber]).show();  //按数组内存储的{c_no}找到广告并显示
            }else{
                $(".random_"+randomAdId+"_1").show();
            }
        }


        //点击
        $(".open_count_1").click(function(){
            var adContentId = $(this).attr("idvalue");
            $.ajax({
                url:"/default.php",
                data:{
                    mod:"site_ad_content",
                    m:"site_ad_click",
                    id:adContentId
                },
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    $.each(data,function(i,v){
                        if (v["ReCommon"] < 0){
                            //console.warn(v["ReCommon"]+" 广告id:"+id);
                        }
                    });
                }
            });
        });

        //点击
        $.each($(".open_virtual_click_1"),function(){
            var available=$(this).closest("div") .attr("idvalue"); //找父元素div的idvalue  如果为-1则过期不做处理
            if(available>=0){
                var adUrl=$(this).attr("href");
                var adContentId = $(this).attr("idvalue");
                $.ajax({
                    url:"/default.php",
                    data:{
                        "mod":"site_ad_content",
                        "m":"site_ad_virtual_click",
                        "id":adContentId
                    },
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        $.each(data,function(i,v){
                            if (v["ReCommon"] > 0){
                                //console.warn(v["ReCommon"]+" 广告id:"+adContentId);
                                var siteAdFrame = document.createElement("iframe");
                                siteAdFrame.src = adUrl;
                                siteAdFrame.style.width = "0px";
                                siteAdFrame.style.height = "0px";
                                siteAdFrame.style.display = "none";
                                document.body.appendChild(siteAdFrame);
                            }
                        });
                    }
                });
            }
        });

});


/**
 * 收起落幕广告
 * @param id 广告位id
 * @return
 */
function PullUp(id){$("#site_ad_"+id).slideUp("slow");}


/**
 * 广告轮换
 * @param adId 广告位id
 * @param tag 当前广告排序数
 * @return
 */
function adSwitch(adId, tag){
    $(".switch_"+adId).hide()
    var arrAdContent=$(".switch_"+adId+"_"+tag);
    if(arrAdContent.length<=0){
        arrAdContent=$(".switch_"+adId+"_1");
        tag=1;
    }
    var lastingTime=arrAdContent[0].getAttribute("idvalue");
    if(lastingTime>0){
        $(".switch_"+adId+"_"+tag).show()
    }
    tag++;
    setTimeout("adSwitch("+adId+", "+tag+")", parseInt(lastingTime)*1000);
}



/**
 * 检查广告日期  标记不在时间内的广告
 * @param adContent 广告
 * @return
 */
function IsInTime(adContent){
    var today=new Date();
    var timeValue=adContent.id;
    var times=timeValue.split("_");  //id="起始时间_结束时间_广告id"
    var beginDateStr=times[0];
    var beginDateArr=beginDateStr.substr(0,10).split("-");
    var beginDate=new Date(beginDateArr[0],beginDateArr[1]-1,beginDateArr[2]);
    if(beginDate<today){
        var endDateStr=times[1];
        var endDateArr=endDateStr.substr(0,10).split("-");
        var endDate=new Date(endDateArr[0],endDateArr[1]-1,endDateArr[2]);
        if(endDate<today){  //过期
            adContent.setAttribute("idvalue","-1");   //idvalue存储广告显示时间，若过期则置-1
            //console.warn("有广告已到期！title:"+adContent.getAttribute("title")+" 广告id:"+times[2]);
        }
    }else
        adContent.setAttribute("idvalue","-1");  //未开始
    adContent.setAttribute("id",times[2]);
}

function setcookie(name, value) {
    var Days = 0.5;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
}
function getcookie(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if (arr != null)
        return unescape(arr[2]);
    return "";
}
function delCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}
