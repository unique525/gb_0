/**
 * Created by a525 on 14-9-30.
 */

$().ready(function() {
    //检查所有广告是否到期
    var today=new Date();
    var arrOfAllAdContents=document.getElementsByClassName("icms_ad_item");//取所有广告
    for(var i=0;i<arrOfAllAdContents.length;i++){
        IsInTime(arrOfAllAdContents[i], today);
    }


    //轮换
    var adId="0";
    var adItems=$(".site_ad_show_type_2");
    for(i=0;i<adItems.length;i++){
        adId=adItems[i].getAttribute("idvalue");
        adSwitch(adId, 1);
    }

});



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
 * 检查广告日期  隐藏不在时间内的广告
 * @param adContent 广告
 * @param today 当前日期
 * @return
 */
function IsInTime(adContent, today){
    var timeValue=adContent.id;
    var times=timeValue.split("_");
    var beginDateStr=times[0];
    var beginDateArr=beginDateStr.substr(0,10).split("-");
    var beginDate=new Date(beginDateArr[0],beginDateArr[1],beginDateArr[2]);
    if(beginDate<today){
        var endDateStr=times[1];
        var endDateArr=endDateStr.substr(0,10).split("-");
        var endDate=new Date(endDateArr[0],endDateArr[1],endDateArr[2]);
        if(endDate<today){  //过期
            adContent.setAttribute("idvalue","0");
            console.warn("有广告已到期！title:"+adContent.getAttribute("title")+" 广告id:"+adContent.getAttribute("idvalue"));
        }
    }else
        adContent.setAttribute("idvalue","0");
    //未开始
    adContent.setAttribute("id",times[2]);
}

