/* 
 * 标签页
 */

$().ready(function() {
    /******************  加载标签页  **************************/
    G_Tabs = $("#tabs").tabs();
    G_Tabs.delegate("span.ui-icon-close", "click", function() {
        var panelId = $(this).closest("li").remove().attr("aria-controls");
        $("#" + panelId).remove();
        G_Tabs.tabs("refresh");
        G_TabCounter--;
    });
});

function addTab() {
    var url = "";
    var tabHeight = $(window).height() - 120;
    url = G_TabUrl + '&tab=' + G_TabCounter;
    if (G_TabTitle.length <= 0) {
        G_TabTitle = G_SelectedDocumentChannelName + "[" + G_SelectedDocumentChannelId + "]";
    }
    var tabContent = '<iframe id="iframeNewTab" frameborder="0" marginwidth="0" marginheight="0" width="100%" height="' + tabHeight + 'px" src="' + url + '"></iframe>';
    var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>";

    var id = "tabs-" + G_TabCounter;
    var li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, G_TabTitle));
    G_Tabs.find(".ui-tabs-nav").append(li);
    G_Tabs.append("<div id='" + id + "'><p>" + tabContent + "</p></div>");
    G_Tabs.tabs("refresh");
    G_Tabs.tabs("option", "active", G_TabCounter - 1);
    G_TabCounter ++;
}


