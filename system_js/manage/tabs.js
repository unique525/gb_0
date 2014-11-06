/**
 * 标签页
 */
$().ready(function() {
    /******************  加载标签页  **************************/
    window.G_Tabs = $("#tabs").tabs({
            activate: function( event, ui ) {

                //设置当前的来源tab id
                window.G_RefererTabId = ui.oldPanel.attr("id");

            }
        }
    );
    window.G_Tabs.delegate("span.ui-icon-close", "click", function() {
        var panelId = $(this).closest("li").remove().attr("aria-controls");
        $("#" + panelId).remove();
        window.G_Tabs.tabs("refresh");
        window.G_TabCounter--;
    });



});

/**
 * 新增一个标签
 */
function addTab() {
    window.G_TabIndex++;
    window.G_TabCounter++;
    var tabHeight = $(window).height() - 120;
    var url = window.G_TabUrl + '&tab_index=' + window.G_TabIndex + "&ps="+ window.G_PageSize;
    if (window.G_TabTitle.length <= 0) {
        window.G_TabTitle = window.G_SelectedChannelName + "[" + window.G_SelectedChannelId + "]";
    }
    var tabContent = '<'+'iframe id="iframeNewTab" frameborder="0" marginwidth="0" marginheight="0" width="100%" height="'
        + tabHeight + 'px" src="' + url + '"><'+'/iframe>';
    var tabTemplate = '<'+'li><'+'a id="tabs_title-'+window.G_TabIndex+'" href="#{href}">#{label}<'+'/a> <'
        +'span class="ui-icon ui-icon-close" role="presentation">Remove Tab<'+'/span><'+'/li>';

    var id = "tabs-" + window.G_TabIndex;
    var li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, window.G_TabTitle));
    window.G_Tabs.find(".ui-tabs-nav").append(li);
    window.G_Tabs.append('<'+'div id="' + id + '"><'+'p>' + tabContent + '<'+'/p><'+'/div>');
    window.G_Tabs.tabs("refresh");
    window.G_Tabs.tabs("option", "active", window.G_TabCounter-1);
}

/**
 * 用于子页面中关闭标签页的按钮事件
 */
function closeTab(){


    if(Request["tab_index"] != undefined){
        var nowTableIndex = parseInt(Request["tab_index"]);

        if(nowTableIndex >=0){
            parent.G_TabCounter--;
            parent.$("#tabs_title-"+nowTableIndex).closest("li").remove();
            var panelId = parent.G_RefererTabId.replace(/tabs-/i,"");// "tabs-"+nowTabCounter;
            parent.G_Tabs.tabs("option", "active", parseInt(panelId)-1);
            //parent.G_Tabs.tabs("option", "active", parent.G_TabCounter-1);
            parent.G_Tabs.tabs( "refresh" );
            //刷新内容

            parent.$("#iframeNewTab").attr("src",parent.$("#iframeNewTab").attr("src"));

        }else{
            //alert("nowTableIndex小于0");
        }
    }else{
        //alert("tab_index未定义");
    }

}

function closeAllTab(){

}


