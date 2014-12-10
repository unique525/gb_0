/**
*   随机
*/
var str='';
str+='<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}; height:{SiteAdHeight};overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}">';
    <icms id="site_ad_content" type="list">
        <header><![CDATA[
            str+='<div class="icms_ad_item random_{f_SiteAdId} random_{f_SiteAdId}_{c_no}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}; height:{SiteAdHeight};display:none">';
                if("{f_SiteAdUrl}"==""){
                str+='{f_SiteAdContent}';
                }else{
                    str+='<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">';
                    str+='{f_SiteAdContent}';
                    str+='</a>';
                }
                str+='</div>';
            ]]></header>
        <item><![CDATA[
            str+='<div class="icms_ad_item random_{f_SiteAdId} random_{f_SiteAdId}_{c_no}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}; height:{SiteAdHeight};display:none">';
                if({f_SiteAdUrl}==""){
                str+='{f_SiteAdContent}';
                }else{
                    str+='<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">';
                    str+='{f_SiteAdContent}';
                    str+='</a>';
                }
                str+='</div>';
            ]]></item>
    </icms>
str+='</div>';

$(".site_ad_{SiteAdId}").html(str);