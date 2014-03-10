function LoadChannelListForManage(siteId) {
    var treeSetting = {
        data: {
            simpleData: {
                enable: true
            }
        },
        view: {
            expandSpeed: ""
        },
        callback: {
            onClick: zTreeOnClick,
            onRightClick: zTreeOnRightClick
        }
    };

    var jsLoader = new JsLoader();
    jsLoader.load("/default.php?secu=manage&mod=channel&m=list_for_manage_left&siteid=" + siteId + "&ramdom=" + Math.random());
    jsLoader.onSuccess = function() {
        $.fn.zTree.init($("#div_manage_menu_of_column"), treeSetting, zNodes);
        G_zTree = $.fn.zTree.getZTreeObj("div_manage_menu_of_column");
        G_RightMenu = $("#rMenu");
    };
    jsLoader.onFailure = function() {
        $("#div_manage_menu_of_column").html("导航树加载失败");
    };
}
function zTreeOnClick(event, treeId, treeNode) {
    G_SelectedChannelId = treeNode.id;
    G_SelectedChannelName = treeNode.name;
    var channelType = treeNode.channelType;
    if (channelType === 'undefined') {
        channelType = 0;
    }
    channelType = parseInt(channelType);
    G_SelectedChannelType = channelType;

    _ChannelClick();
}
function zTreeOnRightClick(event, treeId, treeNode) {
    G_SelectedChannelId = treeNode.id;
    G_SelectedChannelName = treeNode.name;
    var channeltype = treeNode.channel_type;
    if (channeltype === 'undefined') {
        channeltype = 0;
    }
    channeltype = parseInt(channeltype);

    G_SelectedChannelType = channeltype;

    if (!treeNode && event.target.tagName.toLowerCase() !== "button" && $(event.target).parents("a").length === 0) {
        G_zTree.cancelSelectedNode();
        showRMenu("root", event.clientX, event.clientY);
    } else if (treeNode && !treeNode.noR) {
        G_zTree.selectNode(treeNode);
        showRMenu("node", event.clientX, event.clientY);
    }
}
function showRMenu(type, x, y) {
    $("#rMenu ul").show();
    x = x - 10;
    y = y - 100;
    G_RightMenu.css({
        "top": y + "px",
        "left": x + "px",
        "visibility": "visible"
    });

    $("body").bind("mousedown", onBodyMouseDown);
}
function hideRMenu() {
    if (G_RightMenu)
        G_RightMenu.css({
            "visibility": "hidden"
        });
    $("body").unbind("mousedown", onBodyMouseDown);
}
function onBodyMouseDown(event) {
    if (!(event.target.id === "rMenu" || $(event.target).parents("#rMenu").length > 0)) {
        G_RightMenu.css({
            "visibility": "hidden"
        });
    }
}

/**
 * get document list
 */
function _ChannelClick() {

    if (G_SelectedChannelId > 0) {

        if (G_SelectedChannelType === 1) { //新闻类频道

            var aw = $(window).height() - 108 - 35;
            var size = aw / 28;
            var ps = parseInt(size) - 1;

            G_TabUrl = '/default.php?secu=manage&mod=document_news&m=list&ps=' + ps + '&cid=' + G_SelectedChannelId;
            addTab();

        } else if (G_SelectedChannelType === 2) { //咨询回复类频道

        } else if (G_SelectedChannelType === 3) { //图片轮换类频道 

        } else if (G_SelectedChannelType === 4) { //产品类频道

        } else if (G_SelectedChannelType === 5) { //频道产品类频道

        } else if (G_SelectedChannelType === 6) { //活动类

        } else if (G_SelectedChannelType === 7) { //厂商品牌类

        } else if (G_SelectedChannelType === 8) { //自定义页面类

        } else if (G_SelectedChannelType === 9) { //友情链接类

        } else if (G_SelectedChannelType === 10) { //活动表单类

        } else if (G_SelectedChannelType === 11) { //文字直播类

        } else if (G_SelectedChannelType === 12) { //投票调查类

        } else if (G_SelectedChannelType === 13) { //试题类

        } else if (G_SelectedChannelType === 0) { //站点首页

        }
    }

    $(".channelname").html(G_SelectedChannelName + "[" + G_SelectedChannelId + "]");

}




