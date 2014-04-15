/**
 * 标签页
 */
$().ready(function() {
    /******************  加载标签页  **************************/
    window.G_Tabs = $("#tabs").tabs();
    window.G_Tabs.delegate("span.ui-icon-close", "click", function() {
        var panelId = $(this).closest("li").remove().attr("aria-controls");
        $("#" + panelId).remove();
        window.G_Tabs.tabs("refresh");
        window.G_TabCounter--;
    });
});

function addTab() {
    var tabHeight = $(window).height() - 120;
    var url = window.G_TabUrl + '&tab_count=' + window.G_TabCounter + "&ps="+ window.G_PageSize;
    if (window.G_TabTitle.length <= 0) {
        window.G_TabTitle = window.G_SelectedChannelName + "[" + window.G_SelectedChannelId + "]";
    }
    var tabContent = '<iframe id="iframeNewTab" frameborder="0" marginwidth="0" marginheight="0" width="100%" height="' + tabHeight + 'px" src="' + url + '"></iframe>';
    var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>";

    var id = "tabs-" + window.G_TabCounter;
    var li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, window.G_TabTitle));
    window.G_Tabs.find(".ui-tabs-nav").append(li);
    window.G_Tabs.append("<div id='" + id + "'><p>" + tabContent + "</p></div>");
    window.G_Tabs.tabs("refresh");
    window.G_Tabs.tabs("option", "active", window.G_TabCounter - 1);
    window.G_TabCounter ++;
}


