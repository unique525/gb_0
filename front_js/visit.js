/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var visitConfig = encodeURIComponent("域名")||{siteid}||{channelId}||{tableType}||{tableId}||'+encodeURI("tag");
$().ready(function () {
    if(typeof visitConfig=="string")
    {
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
        Visit(funcUrl,siteId,channelId,tableType,tableId,title,tag,url,refUrl);
    }
});


//广告点击统计JS
function Visit(funcUrl,siteId,channelId,tableType,tableId,title,tag,url,refUrl) {
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
            url:url
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