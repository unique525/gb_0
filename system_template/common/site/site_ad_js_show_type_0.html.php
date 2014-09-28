/*
 * 图片广告(0) id=广告位ID,showtitle 轮换时是否显示轮换标题 1为显示 否则不显示
 */
/*********************显示图片广告js****icms_{SiteAdId}***********start*********************/

var v_ad_codename_{SiteAdId} = "{SiteAdId}";
var v_ad_widthnum_{SiteAdId} = "{SiteAdWidth}";
var v_ad_heightnum_{SiteAdId} = "{SiteAdHeight}";
var v_ad_iddivcont_{SiteAdId} = "";
var v_ad_adjson_{SiteAdId} = {SiteAdJson};

$().ready(function() {
    $.each(v_ad_adjson_{SiteAdId},function(i,v){
        if(parseInt(v["OpenVirtualClick"],10) ==1 && parseInt(v["VirtualClickLimit"],10) > 0)
        {
            $.ajax({
                url:"{WEBAPP_DOMAIN}/index.php",
                data:{
                    "a":"ad",
                    "m":"vclick",
                    "id":parseInt(v["id"],10)
                },
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    if(parseInt(data[0].recommon,10) > 0){
                      var cscmsadifr_{SiteAdId} = document.createElement("iframe");
                      cscmsadifr_{SiteAdId}.src = v["adurl"];
                      cscmsadifr_{SiteAdId}.style.width = "0px";
                      cscmsadifr_{SiteAdId}.style.height = "0px";
                      document.body.appendChild(cscmsadifr_{SiteAdId});
                    }
                }
            });
        }
    });
});

v_ad_iddivcont_{SiteAdId} +="<div id='ad_{SiteAdId}' style='width:{SiteAdWidth}px; height:{SiteAdHeight}px;'>";
v_ad_iddivcont_{SiteAdId} +="<div class='icms_ad_item' id='{SiteAdContentId}_{AddedVirtualClickCount}_{ResidenceTime}' style='width:{SiteAdWidth}px; height:{SiteAdHeight}px;'><a href='{SiteAdUrl}' target='_blank' title='{SiteAdContentTitle}'>{SiteAdContentInJs}</a></div>";
v_ad_iddivcont_{SiteAdId} +="<div style='clear:left;font-size:0;overflow:hidden;margin:0;padding:0;border:0;line-height:0;'></div>";
v_ad_iddivcont_{SiteAdId} +="</div>";
if($("#cscmsad_{SiteAdId}").length>0){
	$("#cscmsad_{SiteAdId}").append(v_ad_iddivcont_{SiteAdId});
}
