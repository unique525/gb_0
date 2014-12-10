/**
*   落幕
*/
str='';
str+='<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}; height:{SiteAdHeight};overflow:hidden;display:none" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}" >';
    <icms id="site_ad_content" type="list">
        <header><![CDATA[
            str+='<div class="icms_ad_item pull_{f_SiteAdId}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}; height:{SiteAdHeight};">';
                if("{f_SiteAdUrl}"==""){
                str+='{f_SiteAdContent}';
                }else{
                    str+='<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">';
                    str+='{f_SiteAdContent}';
                    str+='</a>';
                }
                str+='</div>';
            ]]></header>
    </icms>
str+='</div>';

$(".site_ad_{SiteAdId}").html(str);