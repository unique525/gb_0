/**
*   文字
*/
var str='';
var siteAdUrl = '';
str+='<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}; height:{SiteAdHeight};overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType} show_once_{ShowOnce}" title="{ShowNumber}">';
    <icms id="site_ad_content" type="list">
        <item><![CDATA[
            siteAdUrl = '{f_SiteAdUrl}';
            str+='<div class="icms_ad_item" idvalue="{f_SiteAdContentId}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}">';
                if(siteAdUrl.length<=0){
                    str+='{f_SiteAdContentTitle}';
                }else{
                    str+='<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}" style="display: block;line-height: 0px">';
                    str+='{f_SiteAdContentTitle}';
                    str+='</a>';
                }
                str+='</div>';
            ]]></item>
    </icms>
str+='</div>';
$(".site_ad_{SiteAdId}").html(str);


var showOnce=getcookie('show_once_{SiteAdId}');
if(showOnce==1){
$(".site_ad_{SiteAdId}").hide();
}
setcookie('show_once_{SiteAdId}', {ShowOnce});