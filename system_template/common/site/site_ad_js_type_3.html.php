document.write('<div id="site_ad_{SiteAdId}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}" >');
    <icms id="site_ad_content" type="list">
        <header><![CDATA[
            document.write('<div class="icms_ad_item {f_OpenVirtualClick}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;display:none">');
                document.write('<a href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">');
                    document.write('{f_SiteAdContent}');
                    document.write('</a>');
                document.write('</div>');
            ]]></header>
    </icms>
document.write('</div>');