/**
*   文字
*/
var str='';
str+='<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}; height:{SiteAdHeight};overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}">';
    <icms id="site_ad_content" type="list">
        <item><![CDATA[
            str+='<div class="icms_ad_item" idvalue="{f_SiteAdContentId}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}">';
                if("{f_SiteAdUrl}"==""){
                    str+='{f_SiteAdContentTitle}';
                }else{
                    str+='<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">';
                    str+='{f_SiteAdContentTitle}';
                    str+='</a>';
                }
                str+='</div>';
            ]]></item>
    </icms>
str+='</div>';
$(".site_ad_{SiteAdId}").html(str);