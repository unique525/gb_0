
document.write('<div id="site_ad_{SiteAdId}" idvalue="{SiteAdId}" style="width:{SiteAdWidth}px; height:{SiteAdHeight}px;overflow:hidden;" class="icms_site_ad site_ad_show_type_{ShowType}" title="{ShowNumber}">');
    <icms id="site_ad_content" type="list">
        <item><![CDATA[
            document.write('<div class="icms_ad_item open_virtual_click_{f_OpenVirtualClick} open_count_{f_OpenCount}" idvalue="{f_SiteAdContentId}" id="{f_BeginDate}_{f_EndDate}" title="{f_SiteAdContentTitle}">');
                document.write('<a href="{f_SiteAdUrl}" target="_blank" title="{f_SiteAdContentTitle}">');
                    document.write('{f_SiteAdContentTitle}');
                    document.write('</a>');
                document.write('</div>');
            ]]></item>
    </icms>
document.write('</div>');