document.write('<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}">');
    <icms id="site_ad_content" type="list">
        <item><![CDATA[
            document.write('<div class="icms_ad_item switch_{f_SiteAdId} switch_{f_SiteAdId}_{c_no} {f_OpenVirtualClick}" idvalue="{f_ResidenceTime}" id="{f_BeginDate}_{f_EndDate}_{f_SiteAdContentId}" title="{f_SiteAdContentTitle}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;display:none">');
                document.write('<a href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">');
                    document.write('{f_SiteAdContent}');
                    document.write('</a>');
                document.write('</div>');
        ]]></item>
    </icms>
document.write('</div>');