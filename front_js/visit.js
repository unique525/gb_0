/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var visitConfig = encodeURIComponent("域名")||{siteid}||{channelId}||{tableType}||{tableId}||'+encodeURI("tag");
$().ready(function () {
    if(typeof visitConfig=="string")
    {
        //升成识别COOKIE
        var flagCookie = getcookie("ICMS_VISIT_FLAG_COOKIE");
        if (flagCookie == undefined || flagCookie == ""){
            //写COOKIE
            var randCode = getRandCode(1000,100000000);
            setcookie("ICMS_VISIT_FLAG_COOKIE",randCode);
            flagCookie = getcookie("ICMS_VISIT_FLAG_COOKIE");
        }
        var arrConfig=visitConfig.split("||");
        var funcUrl = decodeURIComponent(arrConfig[0]);
        var siteId = arrConfig[1];
        var channelId = arrConfig[2];
        var tableType = arrConfig[3];
        var tableId = arrConfig[4];
        var tag = arrConfig[5];
        var title = encodeURI(document.title);
        var url = decodeURIComponent(window.location.href);
        var refUrl = decodeURIComponent(document.referrer);
        Visit(funcUrl,siteId,channelId,tableType,tableId,title,tag,url,refUrl,flagCookie);
    }
});


//广告点击统计JS
function Visit(funcUrl,siteId,channelId,tableType,tableId,title,tag,url,refUrl,flagCookie) {
    //alert(funcUrl+"|"+siteId+"|"+channelId+"|"+tableType+"|"+tableId+"|"+title+"|"+tag+"|"+url);
    $.ajax({
        url:funcUrl+"/default.php",
        data:{
            mod:"visit",
            a:"create",
            site_id:siteId,
            channel_id:channelId,
            table_type:tableType,
            table_id:tableId,
            title:title,
            tag:tag,
            ref_url:refUrl,
            url:url,
            flag_cookie:flagCookie
        },
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            $.each(data,function(i,v){
                if (v["reCommon"] == 1){
            }
            });
        }
    });
}


function setcookie(name, value) {
    var Days = 10000;
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

//生成从min到max范围的一个随机数
function getRandCode(min,max)
{
   var localDate = new Date();
   var year=localDate.getFullYear();
   var month=localDate.getMonth()+1;
   var date=localDate.getDate();
   var randCode=Math.floor(Math.random()*(max-min+1)+min);
   randCode=year+"-"+month+"-"+date+"_"+randCode;
   return randCode;
}