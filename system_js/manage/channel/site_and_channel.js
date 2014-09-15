$(function () {

    var nowTemplateName = "default";
    if (window.G_TemplateName != undefined) {
        nowTemplateName = window.G_TemplateName;
    }

    //站点管理图标设置及事件处理
    var btnSiteManage = $("#btn_site_manage");
    btnSiteManage.attr("src", "/system_template/" + nowTemplateName + "/images/manage/site_manage.jpg");
    btnSiteManage.css("cursor","pointer");
    btnSiteManage.click(function () {
        //打开站点管理页面
        window.G_TabTitle = "站点管理";
        window.G_TabUrl = '/default.php?secu=manage&mod=site&m=list';
        addTab();
    });


    $("#img_go_site_url").attr("src", "/system_template/" + nowTemplateName + "/images/manage/go.jpg");


    var divNowSelectedSite = $("#div_now_selected_site");
    var divSelectSiteList = $("#div_select_site_list");
    var btnSelectSite = $("#btn_select_site");

    btnSelectSite.click(function () {
        //var itemHeight;
        //alert($(this).attr("class"));
        if ($(this).attr("class") == "btn_select_site_normal") {
            $(this).attr("class", "btn_select_site_clicked");
            divNowSelectedSite.css("display","none");
            divSelectSiteList.css("display","block");
            //itemHeight = parseInt($(".select_site_item").css("height"));
            //var siteCount = $(".site_count").attr("idvalue");
            //var newHeight = itemHeight * siteCount;
            //$(this).css("height", newHeight + "px");
        } else {
            //itemHeight = parseInt($(".select_site_item").css("height"));
            $(this).attr("class", "btn_select_site_normal");
            divNowSelectedSite.css("display","block");
            divSelectSiteList.css("display","none");
            //$(this).css("height", itemHeight + "px");
        }
    });

    //初始化divNowSelectedSite
    if(divSelectSiteList.children.length>0){
        var firstSite = divSelectSiteList.find("div:first-child");
        if(firstSite != undefined){

            var siteId = firstSite.attr("idvalue");
            var siteName = firstSite.html();
            var siteUrl = firstSite.attr("title");

            divNowSelectedSite.attr("idvalue",siteId);
            divNowSelectedSite.html(siteName);
            divNowSelectedSite.attr("title",siteUrl);
            if (siteId.length > 0) {
                LoadSite(siteId, siteName, siteUrl);
            }
        }
    }


    //select site
    $(".select_site_item").click(function () {
        siteId = $(this).attr("idvalue");
        siteName = $(this).html();
        siteUrl = $(this).attr("title");
        LoadSite(siteId, siteName, siteUrl);

        divNowSelectedSite.attr("idvalue",siteId);
        divNowSelectedSite.attr("title",siteUrl);
        divNowSelectedSite.html(siteName);

        btnSelectSite.attr("class", "btn_select_site_normal");
        divNowSelectedSite.css("display","block");
        divSelectSiteList.css("display","none");
    });

    //create channel page
    var btnRightCreateChannel = $("#btn_right_create_channel");
    btnRightCreateChannel.click(function () {
        window.G_TabTitle = "新增频道";
        window.G_TabUrl = '/default.php?secu=manage&mod=channel&m=create&parent_id='+window.G_SelectedChannelId;
        addTab();
    });
    //modify channel page
    var btnRightModifyChannel = $("#btn_right_modify_channel");
    btnRightModifyChannel.click(function () {
        window.G_TabTitle = "编辑频道";
        window.G_TabUrl = '/default.php?secu=manage&mod=channel&m=modify&channel_id='+window.G_SelectedChannelId;
        addTab();
    });


    //property channel page
    var btnRightViewChannelProperty = $("#btn_right_view_channel_property");
    btnRightViewChannelProperty.click(function () {
        window.G_TabTitle = "频道属性";
        window.G_TabUrl = '/default.php?secu=manage&mod=channel&m=property&channel_id='+window.G_SelectedChannelId;
        addTab();
    });



    var btnRightPubChannel = $("#btn_right_pub_channel");
    btnRightPubChannel.click(function () {
        $("#dialog_box").dialog({
            height: 140,
            modal: true
        });
        var dialogContent = $("#dialog_content");
        dialogContent.html("开始发布");

        $.post("/default.php?secu=manage&mod=channel&m=publish&channel_id=" + window.G_SelectedChannelId + "", {
            resultbox: $(this).html()
        }, function(result) {
            dialogContent.html('<img src="/system_template/common/images/spinner2.gif" /> 正在发布...');
            if (parseInt(result) > 0) {

            }else if (parseInt(result) == -2) {

            }else {

            }
        });

    });


});

function LoadSite(siteId, siteName, siteUrl) {

    window.G_NowSiteId = parseInt(siteId);
    window.G_NowSiteName = siteName;
    window.G_SelectedChannelId = 0;

    if (window.G_NowSiteId > 0) {
        $("#div_manage_menu_of_column").html("<img style='margin:10px;' src='/system_template/common/images/spinner2.gif' />");
        _LoadChannelListForManage(siteId);
        //site name
        $(".site_name").html(siteName);
        if (siteUrl.length > 1) {
            $("#btn_go_site_url").attr("href", siteUrl);
        }
    } else {
        $("#div_manage_menu_of_column").html("请先增加一个站点");
    }
}

function _LoadChannelListForManage(siteId) {
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

    var url = "/default.php?secu=manage&mod=channel&m=list_for_manage_left&site_id=" + siteId + "&ramdom=" + Math.random();

    $.post(url, {
        resultbox: $(this).html()
    }, function(result) {
        if(result.length>0){
            var zNodes = eval(result); //将json字符串转换成json对象
            $.fn.zTree.init($("#div_manage_menu_of_column"), treeSetting, zNodes);
            window.G_zTree = $.fn.zTree.getZTreeObj("div_manage_menu_of_column");
            window.G_RightMenu = $("#right_menu");
        }else{
            //error
            $("#div_manage_menu_of_column").html("加载失败");
        }
    });
}
function zTreeOnClick(event, treeId, treeNode) {
    window.G_SelectedChannelId = treeNode.id;
    window.G_SelectedChannelName = treeNode.name;
    var channelType = treeNode.channelType;
    if (channelType === 'undefined') {
        channelType = 0;
    }
    channelType = parseInt(channelType);
    window.G_SelectedChannelType = channelType;

    _ChannelClick();
}
function zTreeOnRightClick(event, treeId, treeNode) {
    window.G_SelectedChannelId = treeNode.id;
    window.G_SelectedChannelName = treeNode.name;
    var channelType = treeNode.channelType;
    if (channelType === 'undefined') {
        channelType = 0;
    }
    channelType = parseInt(channelType);

    window.G_SelectedChannelType = channelType;

    if (!treeNode && event.target.tagName.toLowerCase() !== "button" && $(event.target).parents("a").length === 0) {
        window.G_zTree.cancelSelectedNode();
        showRMenu("root", event.clientX, event.clientY);
    } else if (treeNode && !treeNode.noR) {
        window.G_zTree.selectNode(treeNode);
        showRMenu("node", event.clientX, event.clientY);
    }
}
function showRMenu(type, x, y) {
    $("#right_menu ul").show();
    x = x - 10;
    y = y - 100;
    G_RightMenu.css({
        "top": y + "px",
        "left": x + "px",
        "visibility": "visible"
    });
    //right btn

    $("body").bind("mousedown", onBodyMouseDown);
}
function hideRMenu() {
    if (window.G_RightMenu)
        window.G_RightMenu.css({
            "visibility": "hidden"
        });
    $("body").unbind("mousedown", onBodyMouseDown);
}
function onBodyMouseDown(event) {
    if (!(event.target.id === "right_menu" || $(event.target).parents("#right_menu").length > 0)) {
        window.G_RightMenu.css({
            "visibility": "hidden"
        });
    }
}

/**
 * get document list
 */
function _ChannelClick() {
    if (window.G_SelectedChannelId > 0) {
        var aw = $(window).height() - 138 - 35;
        var size = aw / 28;
        window.G_PageSize = parseInt(size) - 2;
        if (window.G_SelectedChannelType === 1) { //新闻类频道
            window.G_TabTitle = "";
            window.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=list&channel_id=' + window.G_SelectedChannelId;
            addTab();
        } else if (window.G_SelectedChannelType === 2) { //咨询回复类频道

        } else if (window.G_SelectedChannelType === 3) { //图片轮换类频道

        } else if (window.G_SelectedChannelType === 4) { //产品类频道
            window.G_TabTitle = "";
            window.G_TabUrl = '/default.php?secu=manage&mod=product&m=list&channel_id=' + window.G_SelectedChannelId;
            addTab();
        } else if (window.G_SelectedChannelType === 5) { //频道产品类频道

        } else if (window.G_SelectedChannelType === 6) { //活动类
            window.G_TabTitle = "";
            window.G_TabUrl = '/default.php?secu=manage&mod=activity&m=list&site_id='+ window.G_NowSiteId +'&channel_id=' + window.G_SelectedChannelId;
            addTab();

        } else if (window.G_SelectedChannelType === 7) { //厂商品牌类

        } else if (window.G_SelectedChannelType === 8) { //自定义页面类

        } else if (window.G_SelectedChannelType === 9) { //友情链接类

        } else if (window.G_SelectedChannelType === 10) { //活动表单类
            window.G_TabTitle = "";
            window.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=list&site_id='+ window.G_NowSiteId +'&channel_id=' + window.G_SelectedChannelId;
            addTab();
        } else if (window.G_SelectedChannelType === 11) { //文字直播类

        } else if (window.G_SelectedChannelType === 12) { //投票调查类
            window.G_TabTitle = "";
            window.G_TabUrl = '/default.php?secu=manage&mod=vote&m=list&site_id='+ window.G_NowSiteId +'&channel_id=' + window.G_SelectedChannelId;
            addTab();
        } else if (window.G_SelectedChannelType === 13) { //试题类

        } else if (window.G_SelectedChannelType === 0) { //站点首页

        }
    }

    $(".channel_name").html(window.G_SelectedChannelName + "[" + window.G_SelectedChannelId + "]");

}

/**
 * 处理站点列表页的js
 */
$(function () {

    //格式化站点状态
    $(".span_state").each(function(){
        $(this).text(FormatSiteState($(this).text()));
    });

    //开启站点
    $(".img_open_site").click(function(){
        var siteId = parseInt($(this).attr("idvalue"));
        var state = 0; //开启状态
        if(siteId>0){
            $.ajax({
                type: "get",
                url: "default.php?secu=manage&mod=site&m=async_modify_state",
                data: {
                    site_id: siteId,
                    state:state
                },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function(data) {
                    alert(data);
                }
            });
        }
    });

});

/**
 * 格式化站点状态值
 * @return {string}
 */
function FormatSiteState(state){
    switch (state){
        case "0":
            return "启用";
            break;
        case "100":
            return "<"+"span style='color:#990000'>停用<"+"/span>";
            break;
        default :
            return "未知";
            break;
    }
}




