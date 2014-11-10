/**
*   落幕
*/

document.write('<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;overflow:hidden;display:none" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}" >');
    <icms id="site_ad_content" type="list">
        <header><![CDATA[
            document.write('<div class="icms_ad_item pull_{f_SiteAdId}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;">');
                document.write('<a class="open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">');
                    document.write('{f_SiteAdContent}');
                    document.write('</a>');
                document.write('</div>');
            ]]></header>
    </icms>
document.write('</div>');